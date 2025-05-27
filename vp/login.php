<?php
require "bss.php";
session_start();

// Если уже залогинен — переходим на main.php
if (!empty($_SESSION['token'])) {
    header('Location: main.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Сбор данных из формы
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Валидация
    $errors = [];
    if (empty($email)) {
        $errors[] = 'Email обязателен';
    }
    if (empty($password)) {
        $errors[] = 'Пароль обязателен';
    }

    if (empty($errors)) {
        // Подготовка и отправка запроса к Java-REST
        $data = ['email' => $email, 'password' => $password];
        $resp = api_request('POST', AUTH_URL . '/signin', $data);

        if (!empty($resp['token'])) {
            // Сохраняем токен
            $_SESSION['token'] = $resp['token'];
            setcookie('token', $resp['token'], time() + 60*24*60*60, '/', '', false, true);
            header('Location: main.php');
            exit;
        } else {
            $error = $resp['message'] ?? 'Неправильный email или пароль';
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход | Volunteering</title>
    <style>
        :root {
            --primary-color: #ffffff;
            --secondary-color: #000000;
            --accent-color: #333333;
            --border-color: #e0e0e0;
            --error-color: #ff4d4d;
            --success-color: #4CAF50;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
            padding-top: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-card {
            background-color: var(--primary-color);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h1 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .auth-header p {
            color: var(--accent-color);
            font-size: 0.9rem;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem 0;
            font-size: 1rem;
            border: none;
            border-bottom: 1px solid var(--border-color);
            background-color: transparent;
            outline: none;
            transition: border-color var(--transition-speed);
        }

        .form-group input:focus {
            border-bottom-color: var(--secondary-color);
        }

        .form-group label {
            position: absolute;
            top: 0.8rem;
            left: 0;
            color: var(--accent-color);
            font-size: 1rem;
            pointer-events: none;
            transition: all var(--transition-speed);
        }

        .form-group input:focus ~ label,
        .form-group input:valid ~ label {
            top: -0.8rem;
            font-size: 0.8rem;
            color: var(--secondary-color);
        }

        .underline {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--secondary-color);
            transition: width var(--transition-speed);
        }

        .form-group input:focus ~ .underline {
            width: 100%;
        }

        .auth-btn {
            background-color: var(--secondary-color);
            color: var(--primary-color);
            border: none;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all var(--transition-speed);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .auth-btn:hover {
            background-color: var(--accent-color);
            transform: translateY(-2px);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--accent-color);
        }

        .auth-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color var(--transition-speed);
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateX(150%);
            transition: transform 0.4s ease;
            z-index: 1000;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.error {
            background-color: var(--error-color);
        }

        .notification.success {
            background-color: var(--success-color);
        }

        .forgot-password {
            text-align: right;
            margin-top: -1rem;
            font-size: 0.8rem;
        }

        .forgot-password a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color var(--transition-speed);
        }

        .forgot-password a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.4s ease;
        }

        @media (max-width: 600px) {
            .auth-container {
                padding: 1rem;
            }
            
            .auth-card {
                padding: 1.5rem;
            }
            
            .auth-header h1 {
                font-size: 1.5rem;
            }
        }

        .logo {
            position: fixed;
            top: 0;
            left: 0;
            padding: 10px;
            width: 70px;
        }  
    </style>
</head>
<body>
    <img src="imgs/logo.png" class="logo" alt="Логотип">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Вход в аккаунт</h1>
                <p>Введите свои данные для входа</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="notification error show">
                    <?= htmlspecialchars(implode('<br>', $errors)) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['logout_message'])): ?>
                <div class="notification success show">
                    <?= htmlspecialchars($_SESSION['logout_message']) ?>
                </div>
                <?php unset($_SESSION['logout_message']); ?>
            <?php endif; ?>
            
            <form id="loginForm" class="auth-form" method="POST">
                <div class="form-group">
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    <label for="email">Email</label>
                    <div class="underline"></div>
                </div>
                
                <div class="form-group">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Пароль</label>
                    <div class="underline"></div>
                    <div class="forgot-password">
                        <a href="forgot-password.php">Забыли пароль?</a>
                    </div>
                </div>
                
                <button type="submit" class="auth-btn">Войти</button>
            </form>
            
            <div class="auth-footer">
                <p>Еще нет аккаунта? <a href="signup.php" class="auth-link">Зарегистрироваться</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const notifications = document.querySelectorAll('.notification');

            // Автоматическое скрытие уведомлений через 5 секунд
            notifications.forEach(notification => {
                if (notification.classList.contains('show')) {
                    setTimeout(() => {
                        notification.classList.remove('show');
                    }, 5000);
                }
            });

            // Анимация при фокусе на полях ввода
            const inputs = document.querySelectorAll('.form-group input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('.underline').style.width = '100%';
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.querySelector('.underline').style.width = '0';
                    }
                });
                
                // Валидация при вводе
                input.addEventListener('input', function() {
                    if (this.value) {
                        this.parentElement.classList.remove('shake');
                    }
                });
            });

            // Валидация формы
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Проверка email
                const email = document.getElementById('email');
                if (!email.value) {
                    email.parentElement.classList.add('shake');
                    isValid = false;
                }
                
                // Проверка пароля
                const password = document.getElementById('password');
                if (!password.value) {
                    password.parentElement.classList.add('shake');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Убираем анимацию через время
                    setTimeout(() => {
                        document.querySelectorAll('.shake').forEach(el => {
                            el.classList.remove('shake');
                        });
                    }, 400);
                }
            });
        });
    </script>
</body>
</html>