<?php
require "bss.php";
session_start();

// Обработка регистрации
/*<if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $dbname = 'volunteering';
    $username = 'root';
    $password = '';
    $resp = api_request(
        'POST',
        'http://localhost:8080/api/auth/login',
        $data
      );
      if (isset($resp['message']) && strpos($resp['message'],'success')!==false) {
        header('Location: login.php'); exit;
      } else {
        $error = $resp['message'] ?? 'Ошибка регистрации';
      }
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::Eif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = trim($_POST['username'] ?? '');
            $pass = $_POST['password'] ?? '';
        
            // Формируем тело запроса
            $data = ['username' => $user, 'password' => $pass];
        
            // Посылаем в Java-микросервис
            $resp = api_request('POST', 'http://localhost:8080/api/auth/signin', $data);
        
            if (!empty($resp['token'])) {
                $_SESSION['token'] = $resp['token'];
                header('Location: main.php');
                exit;
            } else {
                $error = $resp['message'] ?? 'Bad credentials';
            }
        }RRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }

    $formUsername = trim($_POST['username'] ?? '');
    $formEmail = trim($_POST['email'] ?? '');
    $formPassword = $_POST['password'] ?? '';
    $formConfirmPassword = $_POST['confirm_password'] ?? '';
    $formCountry = $_POST['country'] ?? '';
    
    $errors = [];
    
    // Проверка сложности пароля
    $passwordStrength = checkPasswordStrength($formPassword);
    if ($passwordStrength < 3) {
        $errors[] = 'Пароль слишком слабый. Используйте буквы в разных регистрах, цифры и спецсимволы';
    }
    
    if (empty($formUsername)) {
        $errors[] = 'Имя пользователя обязательно';
    } elseif (strlen($formUsername) < 3) {
        $errors[] = 'Имя пользователя должно содержать минимум 3 символа';
    }
    
    if (empty($formEmail)) {
        $errors[] = 'Email обязателен';
    } elseif (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный формат email';
    }
    
    if (empty($formPassword)) {
        $errors[] = 'Пароль обязателен';
    } elseif (strlen($formPassword) < 8) {
        $errors[] = 'Пароль должен содержать минимум 8 символов';
    } elseif ($formPassword !== $formConfirmPassword) {
        $errors[] = 'Пароли не совпадают';
    }
    
    if (empty($formCountry)) {
        $errors[] = 'Выберите страну';
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$formEmail, $formUsername]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = 'Пользователь с таким email или именем уже существует';
        }
    }
    
    if (empty($errors)) {
        $hashedPassword = password_hash($formPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, country, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$formUsername, $formEmail, $hashedPassword, $formCountry])) {
            $_SESSION['registration_success'] = true;
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Ошибка при регистрации';
        }
    }
}*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    // формировака тела запроса
    $data = ['username' => $user, 'password' => $pass];

    // Посылаем в Java-микросервис
    $resp = api_request('POST', 'http://localhost:8080/api/auth/login', $data);

    if (!empty($resp['token'])) {
        $_SESSION['token'] = $resp['token'];
        header('Location: main.php');
        exit;
    } else {
        $error = $resp['message'] ?? 'Bad credentials';
    }
}

// Функция проверки сложности пароля
function checkPasswordStrength($password) {
    $strength = 0;
    
    if (strlen($password) >= 8) $strength += 1;
    if (strlen($password) >= 12) $strength += 1;
    if (preg_match('/\d/', $password)) $strength += 1;
    if (preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) $strength += 1;
    if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) $strength += 1;
    
    return $strength;
}

// Полный список из 195 стран
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
<!--HTML Форма -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация | Minimal Auth</title>
    <style>
        :root {
            --primary-color: #ffffff;
            --secondary-color: #000000;
            --accent-color: #333333;
            --border-color: #e0e0e0;
            --error-color: #ff4d4d;
            --warning-color: #ff9800;
            --success-color: #4CAF50;
            --transition-speed: 0.3s;
        }
        .logo {
            width: 30px;
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
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            animation: fadeIn 0.5s ease;
            margin-top: 300px;
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

        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem 0;
            font-size: 1rem;
            border: none;
            border-bottom: 1px solid var(--border-color);
            background-color: transparent;
            outline: none;
            transition: border-color var(--transition-speed);
        }

        .form-group select {
            appearance: none;
            padding: 0.8rem 0.5rem;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background-color: var(--primary-color);
        }

        .form-group input:focus, .form-group select:focus {
            border-bottom-color: var(--secondary-color);
        }

        .form-group label {
            position: absolute;
            top: -0.8rem;
            left: 0;
            color: var(--secondary-color);
            font-size: 0.8rem;
            pointer-events: none;
            transition: all var(--transition-speed);
        }

        .form-group select + label {
            top: -1.2rem;
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

        .password-strength-container {
            margin-top: 0.5rem;
        }
        
        .password-strength-bar {
            height: 4px;
            background-color: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.3rem;
        }
        
        .password-strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
        }
        
        .password-strength-text {
            font-size: 0.75rem;
            color: var(--accent-color);
            text-align: right;
        }
        
        .requirements-list {
            margin-top: 0.5rem;
            padding-left: 1.2rem;
            font-size: 0.8rem;
            color: var(--accent-color);
        }
        
        .requirement {
            margin-bottom: 0.2rem;
            position: relative;
        }
        
        .requirement::before {
            content: "•";
            position: absolute;
            left: -1rem;
        }
        
        .requirement.fulfilled {
            color: var(--success-color);
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

        .auth-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background-color: var(--border-color);
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
    <img src="imgs/logo.png" class="logo">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Создать аккаунт</h1>
                <p>Присоединяйтесь к нашему сообществу</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="notification error show">
                    <?= htmlspecialchars(implode('<br>', $errors)) ?>
                </div>
            <?php endif; ?>
            
            <form id="registerForm" class="auth-form" method="POST">
                <div class="form-group">
                    <input type="text" id="username" name="username" value="<?= htmlspecialchars($formUsername ?? '') ?>" required>
                    <label for="username">Имя пользователя</label>
                    <div class="underline"></div>
                </div>
                
                <div class="form-group">
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($formEmail ?? '') ?>" required>
                    <label for="email">Email</label>
                    <div class="underline"></div>
                </div>
                
                <div class="form-group">
                    <select id="country" name="country" required>
                        <option value="" disabled selected>Выберите страну</option>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?= htmlspecialchars($country) ?>" <?= ($formCountry ?? '') === $country ? 'selected' : '' ?>>
                                <?= htmlspecialchars($country) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="country">Страна</label>
                </div>
                
                <div class="form-group">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Пароль</label>
                    <div class="underline"></div>
                    
                    <div class="password-strength-container">
                        <div class="password-strength-bar">
                            <div class="password-strength-fill" id="passwordStrengthFill"></div>
                        </div>
                        <div class="password-strength-text" id="passwordStrengthText">Сложность пароля: низкая</div>
                        
                        <div class="requirements-list">
                            <div class="requirement" id="lengthReq">Минимум 8 символов</div>
                            <div class="requirement" id="digitReq">Содержит цифры</div>
                            <div class="requirement" id="specialReq">Содержит спецсимволы</div>
                            <div class="requirement" id="caseReq">Буквы в разных регистрах</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <label for="confirm_password">Подтвердите пароль</label>
                    <div class="underline"></div>
                </div>
                
                <button type="submit" class="auth-btn" id="submitBtn">Зарегистрироваться</button>
            </form>
            
            <div class="auth-footer">
                <p>Уже есть аккаунт? <a href="index.php" class="auth-link">Войти</a></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordStrengthFill = document.getElementById('passwordStrengthFill');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            const submitBtn = document.getElementById('submitBtn');
            const countrySelect = document.getElementById('country');
            
            // Элементы требований к паролю
            const lengthReq = document.getElementById('lengthReq');
            const digitReq = document.getElementById('digitReq');
            const specialReq = document.getElementById('specialReq');
            const caseReq = document.getElementById('caseReq');
            
            // Минимальная требуемая сложность пароля
            const MIN_PASSWORD_STRENGTH = 3;
            let currentPasswordStrength = 0;
            
            // Проверка пароля в реальном времени
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                currentPasswordStrength = checkPasswordStrength(password);
                updatePasswordStrengthUI(password, currentPasswordStrength);
                updateSubmitButton();
            });
            
            // Проверка выбора страны
            countrySelect.addEventListener('change', function() {
                updateSubmitButton();
            });
            
            // Функция проверки сложности пароля
            function checkPasswordStrength(password) {
                let strength = 0;
                
                // Длина пароля
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;
                
                // Наличие цифр
                if (/\d/.test(password)) strength += 1;
                
                // Наличие спецсимволов
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength += 1;
                
                // Буквы в разных регистрах
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 1;
                
                return strength;
            }
            
            // Обновление интерфейса сложности пароля
            function updatePasswordStrengthUI(password, strength) {
                // Обновление прогресс-бара
                const width = strength * 20;
                passwordStrengthFill.style.width = `${width}%`;
                
                // Обновление цвета
                if (strength < 2) {
                    passwordStrengthFill.style.backgroundColor = 'var(--error-color)';
                    passwordStrengthText.textContent = 'Сложность пароля: низкая';
                    passwordStrengthText.style.color = 'var(--error-color)';
                } else if (strength < 4) {
                    passwordStrengthFill.style.backgroundColor = 'var(--warning-color)';
                    passwordStrengthText.textContent = 'Сложность пароля: средняя';
                    passwordStrengthText.style.color = 'var(--warning-color)';
                } else {
                    passwordStrengthFill.style.backgroundColor = 'var(--success-color)';
                    passwordStrengthText.textContent = 'Сложность пароля: высокая';
                    passwordStrengthText.style.color = 'var(--success-color)';
                }
                
                // Обновление списка требований
                lengthReq.classList.toggle('fulfilled', password.length >= 8);
                digitReq.classList.toggle('fulfilled', /\d/.test(password));
                specialReq.classList.toggle('fulfilled', /[!@#$%^&*(),.?":{}|<>]/.test(password));
                caseReq.classList.toggle('fulfilled', /[a-z]/.test(password) && /[A-Z]/.test(password));
            }
            
            // Обновление состояния кнопки отправки
            function updateSubmitButton() {
                const isCountrySelected = countrySelect.value !== '';
                
                if (currentPasswordStrength < MIN_PASSWORD_STRENGTH || !isCountrySelected) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('disabled');
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('disabled');
                }
            }
            
            // Инициализация
            updateSubmitButton();
            
            // Валидация подтверждения пароля
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.parentElement.classList.add('shake');
                    setTimeout(() => {
                        this.parentElement.classList.remove('shake');
                    }, 400);
                }
            });
            
            // Валидация формы перед отправкой
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                if (currentPasswordStrength < MIN_PASSWORD_STRENGTH) {
                    e.preventDefault();
                    alert('Пароль слишком слабый. Пожалуйста, усильте пароль.');
                    passwordInput.focus();
                }
                
                if (passwordInput.value !== confirmPasswordInput.value) {
                    e.preventDefault();
                    alert('Пароли не совпадают.');
                    confirmPasswordInput.focus();
                }
                
                if (!countrySelect.value) {
                    e.preventDefault();
                    alert('Пожалуйста, выберите страну.');
                    countrySelect.focus();
                }
            });
        });
    </script>
</body>
</html>