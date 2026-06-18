<?php
require "bss.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Подключение к БД
$host = 'localhost';
$dbname = 'volunteering';
$db_username = 'root';
$db_password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Список всех стран мира на русском
$countries = [
    'Россия', 'Афганистан', 'Албания', 'Алжир', 'Андорра', 'Ангола', 'Антигуа и Барбуда', 
    'Аргентина', 'Армения', 'Австралия', 'Австрия', 'Азербайджан', 'Багамские Острова', 
    'Бахрейн', 'Бангладеш', 'Барбадос', 'Беларусь', 'Бельгия', 'Белиз', 'Бенин', 
    'Бутан', 'Боливия', 'Босния и Герцеговина', 'Ботсвана', 'Бразилия', 'Бруней', 
    'Болгария', 'Буркина-Фасо', 'Бурунди', 'Кабо-Верде', 'Камбоджа', 'Камерун', 
    'Канада', 'Центральноафриканская Республика', 'Чад', 'Чили', 'Китай', 'Колумбия', 
    'Коморские Острова', 'Конго', 'Коста-Рика', 'Хорватия', 'Куба', 'Кипр', 
    'Чехия', 'Дания', 'Джибути', 'Доминика', 'Доминиканская Республика', 'Эквадор', 
    'Египет', 'Сальвадор', 'Экваториальная Гвинея', 'Эритрея', 'Эстония', 'Эсватини', 
    'Эфиопия', 'Фиджи', 'Финляндия', 'Франция', 'Габон', 'Гамбия', 'Грузия', 
    'Германия', 'Гана', 'Греция', 'Гренада', 'Гватемала', 'Гвинея', 'Гвинея-Бисау', 
    'Гайана', 'Гаити', 'Гондурас', 'Венгрия', 'Исландия', 'Индия', 'Индонезия', 
    'Иран', 'Ирак', 'Ирландия', 'Израиль', 'Италия', 'Кот-д\'Ивуар', 'Ямайка', 
    'Япония', 'Иордания', 'Казахстан', 'Кения', 'Кирибати', 'Кувейт', 'Кыргызстан', 
    'Лаос', 'Латвия', 'Ливан', 'Лесото', 'Либерия', 'Ливия', 'Лихтенштейн', 
    'Литва', 'Люксембург', 'Мадагаскар', 'Малави', 'Малайзия', 'Мальдивы', 'Мали', 
    'Мальта', 'Маршалловы Острова', 'Мавритания', 'Маврикий', 'Мексика', 'Микронезия', 
    'Молдова', 'Монако', 'Монголия', 'Черногория', 'Марокко', 'Мозамбик', 'Мьянма', 
    'Намибия', 'Науру', 'Непал', 'Нидерланды', 'Новая Зеландия', 'Никарагуа', 'Нигер', 
    'Нигерия', 'КНДР', 'Северная Македония', 'Норвегия', 'Оман', 'Пакистан', 'Палау', 
    'Панама', 'Папуа — Новая Гвинея', 'Парагвай', 'Перу', 'Филиппины', 'Польша', 
    'Португалия', 'Катар', 'Румыния', 'Руанда', 'Сент-Китс и Невис', 'Сент-Люсия', 
    'Сент-Винсент и Гренадины', 'Самоа', 'Сан-Марино', 'Сан-Томе и Принсипи', 
    'Саудовская Аравия', 'Сенегал', 'Сербия', 'Сейшельские Острова', 'Сьерра-Леоне', 
    'Сингапур', 'Словакия', 'Словения', 'Соломоновы Острова', 'Сомали', 'ЮАР', 
    'Южный Судан', 'Испания', 'Шри-Ланка', 'Судан', 'Суринам', 'Швеция', 'Швейцария', 
    'Сирия', 'Таджикистан', 'Танзания', 'Таиланд', 'Тимор-Лешти', 'Того', 'Тонга', 
    'Тринидад и Тобаго', 'Тунис', 'Турция', 'Туркменистан', 'Тувалу', 'Уганда', 
    'Украина', 'ОАЭ', 'Великобритания', 'США', 'Уругвай', 'Узбекистан', 'Вануату', 
    'Ватикан', 'Венесуэла', 'Вьетнам', 'Йемен', 'Замбия', 'Зимбабве'
];

// Обработка формы
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $country = trim($_POST['country'] ?? '');
    
    // Валидация
    if (empty($title)) {
        $errors['title'] = 'Название обязательно';
    }
    
    if (empty($description)) {
        $errors['description'] = 'Описание обязательно';
    }
    
    if (empty($country)) {
        $errors['country'] = 'Страна обязательна';
    }
    
    // Обработка изображения
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        
        if (in_array($file_type, $allowed_types)) {
            $upload_dir = 'uploads/offers/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Получаем расширение файла
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            
            // Сначала сохраняем офер без изображения, чтобы получить его ID
            try {
                $pdo->beginTransaction();
                
                $stmt = $pdo->prepare("
                    INSERT INTO offers (user_id, title, description, country)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user['id'],
                    $title,
                    $description,
                    $country
                ]);
                
                // Получаем ID только что созданного офера
                $offer_id = $pdo->lastInsertId();
                
                // Формируем новое имя файла
                $file_name = $offer_id . '.' . $file_ext;
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                    $image_path = $file_path;
                    
                    // Обновляем офер с путем к изображению
                    $update_stmt = $pdo->prepare("
                        UPDATE offers SET image_path = ? WHERE id = ?
                    ");
                    $update_stmt->execute([$image_path, $offer_id]);
                    
                    $pdo->commit();
                    $success = true;
                } else {
                    $pdo->rollBack();
                    $errors['image'] = 'Ошибка загрузки изображения';
                }
            } catch (PDOException $e) {
                $pdo->rollBack();
                $errors['database'] = 'Ошибка сохранения: ' . $e->getMessage();
            }
        } else {
            $errors['image'] = 'Недопустимый тип файла';
        }
    } else {
        // Если изображение не загружено, просто сохраняем офер
        try {
            $stmt = $pdo->prepare("
                INSERT INTO offers (user_id, title, description, country)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $user['id'],
                $title,
                $description,
                $country
            ]);
            
            $success = true;
        } catch (PDOException $e) {
            $errors['database'] = 'Ошибка сохранения: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать офер | Volunteering</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #ffffff;
            --secondary-color: #000000;
            --accent-color: #333333;
            --hover-color: #f5f5f5;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --info-color: #3498db;
            --transition-speed: 0.4s;
            --shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            --border: 1px solid rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #fafafa;
            color: var(--secondary-color);
            line-height: 1.6;
        }

        header {
            background-color: var(--primary-color);
            color: var(--secondary-color);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
            border-bottom: var(--border);
            transition: all var(--transition-speed);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s;
        }

        .logo {
            width: 30px;
            transition: all 0.5s;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .site-name {
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            background: linear-gradient(90deg, #000, #444);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
            animation: fadeIn 0.6s;
        }

        .form-card {
            background-color: var(--primary-color);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border: var(--border);
        }

        .form-title {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            transition: all var(--transition-speed);
            background-color: var(--primary-color);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--info-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 8px;
            background-color: var(--info-color);
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-speed);
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid transparent;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            border-left-color: var(--success-color);
            color: var(--success-color);
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.1);
            border-left-color: var(--danger-color);
            color: var(--danger-color);
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload-btn {
            width: 100%;
            padding: 12px;
            border: 2px dashed rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .file-upload-btn:hover {
            border-color: var(--info-color);
            background-color: rgba(52, 152, 219, 0.05);
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-preview {
            margin-top: 15px;
            display: none;
            max-width: 100%;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .form-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="imgs/logo.png" class="logo" alt="Логотип">
            <div class="site-name">Implify</div>
        </div>
        
        <a href="index.php" class="btn" style="background-color: var(--accent-color);">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </header>
    
    <div class="container">
        <div class="form-card">
            <h1 class="form-title">Создать волонтерское предложение</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Ваше предложение успешно создано!
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 1500);
                </script>
            <?php elseif (!empty($errors['database'])): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?= $errors['database'] ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title" class="form-label">Название предложения</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                    <?php if (!empty($errors['title'])): ?>
                        <div class="error-message"><?= $errors['title'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="description" class="form-label">Подробное описание</label>
                    <textarea id="description" name="description" class="form-control" required><?= 
                        htmlspecialchars($_POST['description'] ?? '') 
                    ?></textarea>
                    <?php if (!empty($errors['description'])): ?>
                        <div class="error-message"><?= $errors['description'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="country" class="form-label">Страна</label>
                    <select id="country" name="country" class="form-control" required>
                        <option value="">Выберите страну</option>
                        <?php foreach ($countries as $countryOption): ?>
                            <option value="<?= htmlspecialchars($countryOption) ?>" 
                                <?= (($_POST['country'] ?? '') === $countryOption ? 'selected' : '') ?>>
                                <?= htmlspecialchars($countryOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['country'])): ?>
                        <div class="error-message"><?= $errors['country'] ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Изображение (необязательно)</label>
                    <div class="file-upload">
                        <div class="file-upload-btn">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; margin-bottom: 10px;"></i>
                            <div>Нажмите для загрузки изображения</div>
                            <div style="font-size: 0.8rem; color: var(--accent-color); margin-top: 5px;">
                                Максимальный размер: 5MB (JPG, PNG, GIF)
                            </div>
                        </div>
                        <input type="file" name="image" class="file-upload-input" accept="image/*">
                    </div>
                    <img id="imagePreview" class="file-upload-preview" alt="Предпросмотр">
                    <?php if (!empty($errors['image'])): ?>
                        <div class="error-message"><?= $errors['image'] ?></div>
                    <?php endif; ?>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-paper-plane"></i> Опубликовать предложение
                </button>
            </form>
        </div>
    </div>

    <script>
        // Предпросмотр изображения
        const fileInput = document.querySelector('.file-upload-input');
        const preview = document.getElementById('imagePreview');
        
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>