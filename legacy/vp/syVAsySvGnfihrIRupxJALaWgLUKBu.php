<?php
require "bss.php";
session_start();

// Конфигурация базы данных
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'volunteering');

// Конфигурация администратора
define('ADMIN_USERNAME', 'secure_admin');
define('ADMIN_PASSWORD_HASH', password_hash('complex_password123!', PASSWORD_BCRYPT));
define('ALLOWED_IPS', ['127.0.0.1', '192.168.1.1']);
define('MAX_LOGIN_ATTEMPTS', 3);

// Подключение к базе данных
function getDBConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->set_charset("utf8");
    }
    return $conn;
}

// Проверка аутентификации администратора
function is_admin_authenticated() {
    if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
        return false;
    }
    
    if (defined('ALLOWED_IPS') && !empty(ALLOWED_IPS) && !in_array($_SERVER['REMOTE_ADDR'], ALLOWED_IPS)) {
        return false;
    }
    
    if (!isset($_SESSION['admin_user_agent']) || $_SESSION['admin_user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        return false;
    }
    
    if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity'] > 1800)) {
        return false;
    }
    
    $_SESSION['admin_last_activity'] = time();
    return true;
}

// Логирование действий
function log_action($action, $details = '') {
    $log = sprintf(
        "[%s] %s: %s %s (%s)\n",
        date('Y-m-d H:i:s'),
        $_SESSION['admin_username'] ?? 'unknown',
        $action,
        $details,
        $_SERVER['REMOTE_ADDR']
    );
    file_put_contents(__DIR__ . '/admin_actions.log', $log, FILE_APPEND);
}

// Генерация CSRF-токена
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Проверка CSRF-токена
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Получение списка пользователей с поддержкой поиска
function get_users($search = '') {
    $conn = getDBConnection();
    $sql = "SELECT id, username, email, created_at FROM users";
    
    if (!empty($search)) {
        $sql .= " WHERE username LIKE ? OR email LIKE ?";
        $searchTerm = "%$search%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    } else {
        $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Удаление пользователя
function delete_user($id) {
    $conn = getDBConnection();
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Обработка входа
if (isset($_GET['action']) && $_GET['action'] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }
    
    if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        $error = "Слишком много попыток входа. Попробуйте позже.";
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['admin_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['admin_last_activity'] = time();
            $_SESSION['login_attempts'] = 0;
            
            log_action("Успешный вход");
            header('Location: ?action=dashboard');
            exit;
        } else {
            $_SESSION['login_attempts']++;
            $error = "Неверные учетные данные. Попыток осталось: " . (MAX_LOGIN_ATTEMPTS - $_SESSION['login_attempts']);
            log_action("Неудачная попытка входа", "Логин: $username");
        }
    }
}

// Обработка выхода
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    log_action("Выход из системы");
    session_unset();
    session_destroy();
    header('Location: ?action=login');
    exit;
}

// Проверка доступа для защищенных страниц
$protected_actions = ['dashboard', 'users', 'settings'];
if (isset($_GET['action']) && in_array($_GET['action'], $protected_actions) && !is_admin_authenticated()) {
    header('Location: ?action=login');
    exit;
}

// Действия администратора
if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && is_admin_authenticated()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die("Ошибка проверки CSRF-токена");
    }
    
    $userId = (int)($_POST['user_id'] ?? 0);
    if (delete_user($userId)) {
        log_action("Удаление пользователя", "ID: $userId");
        $message = "Пользователь #$userId удален";
    } else {
        $message = "Ошибка при удалении пользователя";
    }
}

// Получаем список пользователей для страницы управления с учетом поиска
$users = [];
$search = '';
if (isset($_GET['action']) && $_GET['action'] === 'users' && is_admin_authenticated()) {
    $search = trim($_GET['search'] ?? '');
    $users = get_users($search);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-bg: #ffffff;
            --secondary-bg: #f8f9fa;
            --text-color: #212529;
            --accent-color: #000000;
            --hover-color: #343a40;
            --border-color: #dee2e6;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --success-color: #28a745;
            --error-color: #dc3545;
        }
        
        * {
            box-sizing: border-box;
            transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: var(--primary-bg);
            color: var(--text-color);
            overflow-x: hidden;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: var(--primary-bg);
            color: var(--text-color);
            padding: 20px 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px var(--shadow-color);
            position: relative;
            overflow: hidden;
        }
        
        header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent-color), transparent);
            animation: headerLine 3s infinite;
        }
        
        @keyframes headerLine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        header h1 {
            margin: 0;
            padding: 0 20px;
            font-weight: 300;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
        }
        
        header h1::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 20px;
            width: 50px;
            height: 2px;
            background-color: var(--accent-color);
            animation: underlinePulse 2s infinite;
        }
        
        @keyframes underlinePulse {
            0% { width: 50px; }
            50% { width: 70px; }
            100% { width: 50px; }
        }
        
        nav {
            background-color: var(--primary-bg);
            padding: 15px 20px;
            box-shadow: 0 2px 10px var(--shadow-color);
            margin-bottom: 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        nav a {
            color: var(--text-color);
            text-decoration: none;
            margin-right: 20px;
            font-weight: 500;
            position: relative;
            padding: 5px 0;
        }
        
        nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background-color: var(--accent-color);
            transition: width 0.3s ease;
        }
        
        nav a:hover::after {
            width: 100%;
        }
        
        nav a:hover {
            color: var(--hover-color);
        }
        
        .content {
            background-color: var(--primary-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px var(--shadow-color);
            margin-bottom: 30px;
            animation: fadeIn 0.5s ease;
            border: 1px solid var(--border-color);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-form {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: var(--primary-bg);
            border-radius: 8px;
            box-shadow: 0 10px 30px var(--shadow-color);
            animation: bounceIn 0.6s ease;
            border: 1px solid var(--border-color);
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .login-form h2 {
            margin-top: 0;
            text-align: center;
            font-weight: 300;
            letter-spacing: 1px;
            animation: textGlow 3s infinite alternate;
        }
        
        @keyframes textGlow {
            from { text-shadow: 0 0 5px rgba(0, 0, 0, 0.1); }
            to { text-shadow: 0 0 10px rgba(0, 0, 0, 0.2); }
        }
        
        .login-form input {
            width: 100%;
            padding: 12px 15px;
            margin: 15px 0;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
            background-color: var(--secondary-bg);
            transition: all 0.3s ease;
        }
        
        .login-form input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .login-form button {
            width: 100%;
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .login-form button:hover {
            background-color: var(--hover-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px var(--shadow-color);
        }
        
        .login-form button:active {
            transform: translateY(0);
        }
        
        .error {
            color: var(--error-color);
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 3px solid var(--error-color);
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        .success {
            color: var(--success-color);
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 3px solid var(--success-color);
            animation: fadeInUp 0.5s ease;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            animation: fadeIn 0.8s ease;
        }
        
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        table th {
            background-color: var(--secondary-bg);
            font-weight: 600;
            letter-spacing: 0.5px;
            position: relative;
        }
        
        table th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent-color), transparent);
            animation: tableHeaderLine 3s infinite;
        }
        
        @keyframes tableHeaderLine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        table tr:hover td {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: var(--accent-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background-color: var(--hover-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px var(--shadow-color);
        }
        
        .btn-danger {
            background: var(--error-color);
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        form {
            animation: fadeIn 0.6s ease;
        }
        
        input[type="number"], input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background-color: var(--secondary-bg);
            transition: all 0.3s ease;
        }
        
        input[type="number"]:focus, input[type="text"]:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }
        
        button[type="submit"] {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        button[type="submit"]:hover {
            background-color: var(--hover-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px var(--shadow-color);
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }
        
        .rotate {
            animation: rotate 10s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .stats-card {
            background: var(--secondary-bg);
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 8px var(--shadow-color);
            border-left: 4px solid var(--accent-color);
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px var(--shadow-color);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            border-top: 1px solid var(--border-color);
            animation: fadeIn 1s ease;
        }
        
        .loading-bar {
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-bg));
            animation: loading 2s infinite;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Стили для поиска */
        .search-box {
            background: var(--secondary-bg);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px var(--shadow-color);
            margin-bottom: 20px;
        }
        
        .search-box input {
            margin: 0 !important;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .search-form .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <?php if (!isset($_GET['action']) || $_GET['action'] === 'login'): ?>
        <?php if (!is_admin_authenticated()): ?>
            <div class="loading-bar"></div>
            <div class="login-form floating-element">
                <h2 class="pulse">Вход в админ-панель</h2>
                <?php if (isset($error)): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="?action=login">
                    <input type="text" name="username" placeholder="Логин" required>
                    <input type="password" name="password" placeholder="Пароль" required>
                    <button type="submit" class="animate__pulse animate__infinite">Войти</button>
                </form>
            </div>
        <?php else: ?>
            <?php header('Location: ?action=dashboard'); exit; ?>
        <?php endif; ?>
    
    <?php elseif (is_admin_authenticated()): ?>
        <div class="loading-bar"></div>
        <header class="animate__animated animate__fadeInDown">
            <div class="container">
                <h1>Админ-панель</h1>
            </div>
        </header>
        
        <nav class="animate__animated animate__fadeIn">
            <div class="container">
                <a href="?action=dashboard" class="animate__animated animate__fadeInLeft">Главная</a>
                <a href="?action=users" class="animate__animated animate__fadeInLeft animate__delay-1s">Пользователи</a>
                <a href="?action=settings" class="animate__animated animate__fadeInLeft animate__delay-2s">Настройки</a>
                <a href="?action=logout" class="animate__animated animate__fadeInRight animate__delay-3s">Выйти</a>
            </div>
        </nav>
        
        <div class="container">
            <div class="content">
                <?php if (isset($message)): ?>
                    <div class="success animate__animated animate__bounceIn"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                
                <?php if ($_GET['action'] === 'dashboard'): ?>
                    <h2 class="animate__animated animate__fadeIn">Главная панель</h2>
                    <p class="animate__animated animate__fadeIn animate__delay-1s">Добро пожаловать, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</p>
                    
                    <div class="stats-grid">
                        <div class="stats-card animate__animated animate__fadeInUp">
                            <h3>Статистика</h3>
                            <p>Последний вход: <?= date('Y-m-d H:i:s', $_SESSION['admin_last_activity']) ?></p>
                            <div class="rotate" style="width: 20px; height: 20px; background: var(--accent-color);"></div>
                        </div>
                        
                        <div class="stats-card animate__animated animate__fadeInUp animate__delay-1s">
                            <h3>Активность</h3>
                            <p>Ваш IP: <?= htmlspecialchars($_SERVER['REMOTE_ADDR']) ?></p>
                        </div>
                        
                        <div class="stats-card animate__animated animate__fadeInUp animate__delay-2s">
                            <h3>Система</h3>
                            <p>Время сервера: <?= date('Y-m-d H:i:s') ?></p>
                        </div>
                    </div>
                
                <?php elseif ($_GET['action'] === 'users'): ?>
                    <h2 class="animate__animated animate__fadeIn">Управление пользователями</h2>
                    
                    <!-- Форма поиска пользователей -->
                    <div class="search-box animate__animated animate__fadeIn animate__delay-1s">
                        <form method="GET" action="" class="search-form">
                            <input type="hidden" name="action" value="users">
                            <div class="form-group">
                                <input type="text" name="search" placeholder="Поиск по имени или email" 
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <button type="submit" class="btn">Найти</button>
                            <?php if (!empty($search)): ?>
                                <a href="?action=users" class="btn btn-danger">Сбросить</a>
                            <?php endif; ?>
                        </form>
                    </div>
                    
                    <?php if (!empty($search)): ?>
                        <div class="animate__animated animate__fadeIn">
                            <p>Результаты поиска по запросу: "<strong><?= htmlspecialchars($search) ?></strong>"</p>
                            <p>Найдено пользователей: <?= count($users) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <table class="animate__animated animate__fadeIn animate__delay-1s">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя пользователя</th>
                                <th>Email</th>
                                <th>Дата регистрации</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center;">
                                        <?= empty($search) ? 'Нет пользователей' : 'Пользователи не найдены' ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                <tr class="animate__animated animate__fadeIn">
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                                    <td>
                                        <a href="#" class="btn animate__animated animate__pulse animate__infinite">Редактировать</a>
                                        <form method="POST" action="?action=delete_user" style="display: inline;">
                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                                            <button type="submit" class="btn-danger animate__animated animate__pulse animate__infinite animate__delay-1s">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                
                <?php elseif ($_GET['action'] === 'settings'): ?>
                    <h2 class="animate__animated animate__fadeIn">Настройки системы</h2>
                    <form method="POST" class="animate__animated animate__fadeIn animate__delay-1s">
                        <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                        <div style="margin-bottom: 20px;">
                            <label>Лимит попыток входа:</label>
                            <input type="number" name="login_attempts" value="<?= MAX_LOGIN_ATTEMPTS ?>">
                        </div>
                        <button type="submit" name="save_settings" class="animate__animated animate__pulse animate__infinite">Сохранить</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <footer class="animate__animated animate__fadeInUp animate__delay-1s">
            <div class="container">
                <p>Админ-панель &copy; <?= date('Y') ?></p>
            </div>
        </footer>
    <?php endif; ?>
    
    <script>
        // Добавляем анимации при скролле
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll();
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animation = element.getAttribute('data-animation');
                        if (animation) {
                            element.classList.add(animation);
                        }
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(element => {
                observer.observe(element);
            });
            
            // Анимация кнопок при наведении
            const buttons = document.querySelectorAll('.btn, button');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', () => {
                    button.classList.add('animate__rubberBand');
                });
                button.addEventListener('mouseleave', () => {
                    setTimeout(() => {
                        button.classList.remove('animate__rubberBand');
                    }, 1000);
                });
            });
        });
    </script>
</body>
</html>

