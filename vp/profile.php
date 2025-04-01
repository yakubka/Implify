<?php
require "bss.php";
session_start();

// Включение отображения ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['user_id'])) {
        $_SESSION['user_id'] = $_COOKIE['user_id'];
        $_SESSION['username'] = $_COOKIE['username'];
    } else {
        header('Location: login.php');
        exit;
    }
}

// Подключение к БД
$host = 'localhost';
$dbname = 'volunteering';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Получение данных пользователя
$user = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die("Пользователь не найден");
    }
} catch (PDOException $e) {
    die("Ошибка запроса: " . $e->getMessage());
}

// Обработка формы профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username'] ?? '');
    $newCountry = trim($_POST['country'] ?? '');
    
    if (!empty($newUsername)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, country = ? WHERE id = ?");
            $stmt->execute([$newUsername, $newCountry, $_SESSION['user_id']]);
            
            $_SESSION['username'] = $newUsername;
            $user['username'] = $newUsername;
            $user['country'] = $newCountry;
            
            setcookie('username', $newUsername, time() + 60*24*60*60, '/');
        } catch (PDOException $e) {
            die("Ошибка обновления: " . $e->getMessage());
        }
    }
}

// Обработка подтверждения/отклонения волонтеров
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['response_action'])) {
    $responseId = $_POST['response_id'];
    $action = $_POST['response_action'];
    
    try {
        // Проверяем, что текущий пользователь - владелец оффера
        $stmt = $pdo->prepare("
            UPDATE offer_responses r
            JOIN offers o ON r.offer_id = o.id
            SET r.status = ?
            WHERE r.id = ? AND o.user_id = ?
        ");
        $stmt->execute([$action, $responseId, $_SESSION['user_id']]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'message' => 'Статус отклика успешно обновлен'
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'error',
                'message' => 'Не удалось обновить статус отклика'
            ];
        }
        
        header("Location: profile.php");
        exit;
    } catch (PDOException $e) {
        die("Ошибка обновления статуса: " . $e->getMessage());
    }
}

// Получение уведомлений (откликов на офферы пользователя)
$notifications = [];
try {
    $stmt = $pdo->prepare("
        SELECT 
            r.id as response_id,
            r.message as response_message,
            r.status as response_status,
            r.created_at as response_date,
            o.id as offer_id,
            o.title as offer_title,
            u.id as volunteer_id,
            u.username as volunteer_name
        FROM offer_responses r
        JOIN offers o ON r.offer_id = o.id
        JOIN users u ON r.user_id = u.id
        WHERE o.user_id = ?
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка получения уведомлений: " . $e->getMessage());
}

function generateAvatarStyle($username) {
    $colors = [
        ['#FFD1DC', '#FF85A2', '#FF4785'],
        ['#D1E8FF', '#85B8FF', '#478CFF'],
        ['#D1FFD7', '#85FF94', '#47FF5E'],
        ['#FFF2D1', '#FFD785', '#FFB847'],
        ['#E8D1FF', '#B885FF', '#8847FF'],
        ['#D1F7FF', '#85E8FF', '#47D8FF'],
    ];
    
    $hash = array_reduce(str_split($username), function($acc, $char) {
        return ord($char) + $acc;
    }, 0);
    $palette = $colors[$hash % count($colors)];
    
    $angle = rand(0, 360);
    return "background: linear-gradient({$angle}deg, {$palette[0]}, {$palette[1]}, {$palette[2]}); color: " . getContrastColor($palette[1]);
}

function getContrastColor($hexColor) {
    $r = hexdec(substr($hexColor, 1, 2));
    $g = hexdec(substr($hexColor, 3, 2));
    $b = hexdec(substr($hexColor, 5, 2));
    $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
    return $brightness > 128 ? '#000000' : '#FFFFFF';
}

$countries = [
    'Афганистан', 'Албания', 'Алжир', 'Андорра', 'Ангола',
    'Антигуа и Барбуда', 'Аргентина', 'Армения', 'Австралия', 'Австрия',
    'Азербайджан', 'Багамы', 'Бахрейн', 'Бангладеш', 'Барбадос',
    'Беларусь', 'Бельгия', 'Белиз', 'Бенин', 'Бутан',
    'Боливия', 'Босния и Герцеговина', 'Ботсвана', 'Бразилия', 'Бруней',
    'Болгария', 'Буркина-Фасо', 'Бурунди', 'Кабо-Верде', 'Камбоджа',
    'Камерун', 'Канада', 'Центральноафриканская Республика', 'Чад', 'Чили',
    'Китай', 'Колумбия', 'Коморы', 'Конго', 'Коста-Рика',
    'Хорватия', 'Куба', 'Кипр', 'Чехия', 'Дания',
    'Джибути', 'Доминика', 'Доминиканская Республика', 'Эквадор', 'Египет',
    'Сальвадор', 'Экваториальная Гвинея', 'Эритрея', 'Эстония', 'Эсватини',
    'Эфиопия', 'Фиджи', 'Финляндия', 'Франция', 'Габон',
    'Гамбия', 'Грузия', 'Германия', 'Гана', 'Греция',
    'Гренада', 'Гватемала', 'Гвинея', 'Гвинея-Бисау', 'Гайана',
    'Гаити', 'Гондурас', 'Венгрия', 'Исландия', 'Индия',
    'Индонезия', 'Иран', 'Ирак', 'Ирландия', 'Израиль',
    'Италия', 'Кот-д\'Ивуар', 'Ямайка', 'Япония', 'Иордания',
    'Казахстан', 'Кения', 'Кирибати', 'Кувейт', 'Кыргызстан',
    'Лаос', 'Латвия', 'Ливан', 'Лесото', 'Либерия',
    'Ливия', 'Лихтенштейн', 'Литва', 'Люксембург', 'Мадагаскар',
    'Малави', 'Малайзия', 'Мальдивы', 'Мали', 'Мальта',
    'Маршалловы Острова', 'Мавритания', 'Маврикий', 'Мексика', 'Микронезия',
    'Молдова', 'Монако', 'Монголия', 'Черногория', 'Марокко',
    'Мозамбик', 'Мьянма', 'Намибия', 'Науру', 'Непал',
    'Нидерланды', 'Новая Зеландия', 'Никарагуа', 'Нигер', 'Нигерия',
    'КНДР', 'Северная Македония', 'Норвегия', 'Оман', 'Пакистан',
    'Палау', 'Панама', 'Папуа — Новая Гвинея', 'Парагвай', 'Перу',
    'Филиппины', 'Польша', 'Португалия', 'Катар', 'Румыния',
    'Россия', 'Руанда', 'Сент-Китс и Невис', 'Сент-Люсия', 'Сент-Винсент и Гренадины',
    'Самоа', 'Сан-Марино', 'Сан-Томе и Принсипи', 'Саудовская Аравия', 'Сенегал',
    'Сербия', 'Сейшелы', 'Сьерра-Леоне', 'Сингапур', 'Словакия',
    'Словения', 'Соломоновы Острова', 'Сомали', 'ЮАР', 'Южный Судан',
    'Испания', 'Шри-Ланка', 'Судан', 'Суринам', 'Швеция',
    'Швейцария', 'Сирия', 'Таджикистан', 'Танзания', 'Таиланд',
    'Тимор-Лешти', 'Того', 'Тонга', 'Тринидад и Тобаго', 'Тунис',
    'Турция', 'Туркменистан', 'Тувалу', 'Уганда', 'Украина',
    'ОАЭ', 'Великобритания', 'США', 'Уругвай', 'Узбекистан',
    'Вануату', 'Ватикан', 'Венесуэла', 'Вьетнам', 'Йемен',
    'Замбия', 'Зимбабве'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль | Volunteering</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #fff;
            --secondary: #000;
            --accent: #333;
            --hover: #f5f5f5;
            --danger: #e74c3c;
            --success: #2ecc71;
            --info: #3498db;
            --warning: #f39c12;
            --active: #e0f7fa;
            --transition: 0.3s;
            --sidebar: 250px;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background: #fafafa;
            color: var(--secondary);
        }

        /* Сайдбар */
        .sidebar {
            width: var(--sidebar);
            background: var(--primary);
            border-right: 1px solid rgba(0,0,0,0.1);
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .avatar-base {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            overflow: hidden;
            text-align: center;
            line-height: 1;
        }

        .avatar {
            background: #ccc;
        }

        .avatar, .profile-avatar {
            position: relative;
        }

        .avatar span, .profile-avatar span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            transform: scale(0.85);
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-item {
            margin-bottom: 5px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            text-decoration: none;
            color: var(--accent);
            border-radius: 8px;
            transition: var(--transition);
        }

        .menu-link:hover, .menu-link.active {
            background: var(--active);
            color: var(--secondary);
        }

        .menu-link i {
            width: 20px;
            text-align: center;
        }

        /* Основное содержимое */
        .main {
            flex: 1;
            margin-left: var(--sidebar);
            padding: 30px;
        }

        .section {
            display: none;
            animation: fadeIn 0.5s;
            margin-left: 20px;
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .card {
            background: var(--primary);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            max-width: 800px;
        }

        /* Стили профиля */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .profile-info {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.8);
            border-radius: 8px;
            max-width: 300px;
        }

        .profile-avatar {
            <?= generateAvatarStyle($user['username']) ?>
        }

        /* Форма настроек */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
        }

        button:hover {
            background: var(--accent);
        }

        /* Уведомления */
        .notification {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            background: var(--primary);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .notification-icon {
            font-size: 20px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 14px;
            color: var(--accent);
        }

        .notification-date {
            color: #777;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--accent);
            color: var(--accent);
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .volunteer-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .volunteer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }

        .volunteer-name {
            font-weight: 500;
        }

        .offer-link {
            color: var(--info);
            text-decoration: none;
        }

        .offer-link:hover {
            text-decoration: underline;
        }

        .response-message {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 14px;
        }

        /* Flash сообщения */
        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1100;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s, fadeOut 0.5s 3s forwards;
        }

        .flash-success {
            background: var(--success);
            color: white;
        }

        .flash-error {
            background: var(--danger);
            color: white;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
            }
            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Сайдбар -->
    <aside class="sidebar">
        <div class="user-card">
            <div class="avatar avatar-base"><span><?= strtoupper(substr($user['username'], 0, 1)) ?></span></div>
            <div>
                <h3><?= htmlspecialchars($user['username']) ?></h3>
                <small>Участник</small>
            </div>
        </div>
        
        <ul class="menu">
            <li class="menu-item">
                <a href="#" class="menu-link active" data-section="profile">
                    <i class="fas fa-user"></i>
                    <span>Профиль</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link" data-section="settings">
                    <i class="fas fa-cog"></i>
                    <span>Настройки</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link" data-section="notifications">
                    <i class="fas fa-bell"></i>
                    <span>Уведомления</span>
                    <?php if (count($notifications) > 0): ?>
                        <span style="margin-left: auto; background: var(--danger); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                            <?= count($notifications) ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="menu-item">
                <a href="logout.php" class="menu-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Выйти</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Основное содержимое -->
    <main class="main">
        <!-- Профиль -->
        <section id="profile" class="section active">
            <h1>Мой профиль</h1>
            <div class="card">
                <div class="profile-header">
                    <div class="profile-avatar avatar-base"><span><?= strtoupper(substr($user['username'], 0, 1)) ?></span></div>
                    <div>
                        <h2><?= htmlspecialchars($user['username']) ?></h2>
                        <p><?= $user['email'] ?></p>
                    </div>
                </div>
                <div>
                    <h3>Информация</h3>
                    <p>Страна: <?= $user['country'] ?? 'Не указана' ?></p>
                    <p>Участник с: <?= date('d.m.Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </section>

        <!-- Настройки -->
        <section id="settings" class="section">
            <h1>Настройки профиля</h1>
            <div class="card">
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Имя пользователя</label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="country">Страна</label>
                        <select id="country" name="country">
                            <option value="">Выберите страну</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?= htmlspecialchars($country) ?>" <?= ($user['country'] ?? '') === $country ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($country) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="update_profile">Сохранить</button>
                </form>
            </div>
        </section>

        <!-- Уведомления -->
        <section id="notifications" class="section">
            <h1>Уведомления</h1>
            <div class="card">
                <?php if (empty($notifications)): ?>
                    <div class="notification">
                        <div class="notification-icon">
                            <i class="fas fa-info-circle" style="color: var(--info);"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Нет уведомлений</div>
                            <p>Здесь будут появляться уведомления о новых откликах на ваши проекты</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification">
                            <div class="notification-icon">
                                <?php if ($notification['response_status'] === 'pending'): ?>
                                    <i class="fas fa-clock" style="color: var(--warning);"></i>
                                <?php elseif ($notification['response_status'] === 'accepted'): ?>
                                    <i class="fas fa-check-circle" style="color: var(--success);"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle" style="color: var(--danger);"></i>
                                <?php endif; ?>
                            </div>
                            <div class="notification-content">
                                <div class="volunteer-info">
                                    <div class="volunteer-avatar" style="<?= generateAvatarStyle($notification['volunteer_name']) ?>">
                                        <?= strtoupper(substr($notification['volunteer_name'], 0, 1)) ?>
                                    </div>
                                    <div class="volunteer-name"><?= htmlspecialchars($notification['volunteer_name']) ?></div>
                                </div>
                                
                                <div class="notification-title">
                                    Хочет присоединиться к вашему проекту 
                                    <a href="offer.php?id=<?= $notification['offer_id'] ?>" class="offer-link">
                                        "<?= htmlspecialchars($notification['offer_title']) ?>"
                                    </a>
                                </div>
                                
                                <?php if (!empty($notification['response_message'])): ?>
                                    <div class="response-message">
                                        <strong>Сообщение:</strong> <?= htmlspecialchars($notification['response_message']) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="notification-meta">
                                    <div class="notification-date">
                                        <?= date('d.m.Y H:i', strtotime($notification['response_date'])) ?>
                                        <span class="status-badge status-<?= $notification['response_status'] ?>">
                                            <?= $notification['response_status'] === 'pending' ? 'Ожидает подтверждения' : 
                                               ($notification['response_status'] === 'accepted' ? 'Принято' : 'Отклонено') ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if ($notification['response_status'] === 'pending'): ?>
                                    <div class="notification-actions">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="response_id" value="<?= $notification['response_id'] ?>">
                                            <input type="hidden" name="response_action" value="accepted">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Принять
                                            </button>
                                        </form>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="response_id" value="<?= $notification['response_id'] ?>">
                                            <input type="hidden" name="response_action" value="rejected">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-times"></i> Отклонить
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="flash-message flash-<?= $_SESSION['flash_message']['type'] ?>">
            <i class="fas fa-<?= $_SESSION['flash_message']['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= $_SESSION['flash_message']['message'] ?>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Переключение между разделами
            const links = document.querySelectorAll('.menu-link[data-section]');
            
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Удаляем активный класс у всех ссылок
                    document.querySelectorAll('.menu-link').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Добавляем активный класс текущей ссылке
                    this.classList.add('active');
                    
                    // Скрываем все разделы
                    document.querySelectorAll('.section').forEach(section => {
                        section.classList.remove('active');
                    });
                    
                    // Показываем выбранный раздел
                    const sectionId = this.getAttribute('data-section');
                    document.getElementById(sectionId).classList.add('active');
                });
            });

            // Автоматическое скрытие flash-сообщений
            const flashMessage = document.querySelector('.flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.style.animation = 'fadeOut 0.5s forwards';
                    setTimeout(() => flashMessage.remove(), 500);
                }, 3000);
            }
        });
    </script>
</body>
</html>