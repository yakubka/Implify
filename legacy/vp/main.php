<?php
require "bss.php";
session_start();

// Восстановление токена из куки
if (empty($_SESSION['token']) && !empty($_COOKIE['token'])) {
    $_SESSION['token'] = $_COOKIE['token'];
}

if (empty($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}
// Получаем список стран из оферов
$countries_stmt = $pdo->query("SELECT DISTINCT country FROM offers ORDER BY country");
$db_countries = $countries_stmt->fetchAll(PDO::FETCH_COLUMN);

// Полный список всех стран мира
$all_countries = [
    'Russia', 'USA', 'Germany', 'UK', 'France', 'Italy', 'Spain', 'Ukraine', 'Poland', 'Canada',
    'Australia', 'Japan', 'China', 'India', 'Brazil', 'Argentina', 'Mexico', 'South Africa', 'Egypt',
    'Algeria', 'Nigeria', 'Kenya', 'Ethiopia', 'Morocco', 'Ghana', 'Angola', 'Uganda', 'Tanzania',
    'Albania', 'Andorra', 'Armenia', 'Austria', 'Azerbaijan', 'Belarus', 'Belgium', 'Bosnia and Herzegovina',
    'Bulgaria', 'Croatia', 'Cyprus', 'Czech Republic', 'Denmark', 'Estonia', 'Finland', 'Georgia', 'Greece',
    'Hungary', 'Iceland', 'Ireland', 'Israel', 'Kazakhstan', 'Latvia', 'Liechtenstein', 'Lithuania', 'Luxembourg',
    'Malta', 'Moldova', 'Monaco', 'Montenegro', 'Netherlands', 'North Macedonia', 'Norway', 'Portugal', 'Romania',
    'San Marino', 'Serbia', 'Slovakia', 'Slovenia', 'Sweden', 'Switzerland', 'Turkey', 'Afghanistan', 'Bahrain',
    'Bangladesh', 'Bhutan', 'Brunei', 'Cambodia', 'East Timor', 'Indonesia', 'Iran', 'Iraq', 'Jordan', 'Kuwait',
    'Kyrgyzstan', 'Laos', 'Lebanon', 'Malaysia', 'Maldives', 'Mongolia', 'Myanmar', 'Nepal', 'Oman', 'Pakistan',
    'Palestine', 'Philippines', 'Qatar', 'Saudi Arabia', 'Singapore', 'Sri Lanka', 'Syria', 'Tajikistan', 'Thailand',
    'Turkmenistan', 'United Arab Emirates', 'Uzbekistan', 'Vietnam', 'Yemen', 'Antigua and Barbuda', 'Bahamas',
    'Barbados', 'Belize', 'Costa Rica', 'Cuba', 'Dominica', 'Dominican Republic', 'El Salvador', 'Grenada',
    'Guatemala', 'Haiti', 'Honduras', 'Jamaica', 'Nicaragua', 'Panama', 'Saint Kitts and Nevis', 'Saint Lucia',
    'Saint Vincent and the Grenadines', 'Trinidad and Tobago', 'Bolivia', 'Chile', 'Colombia', 'Ecuador', 'Guyana',
    'Paraguay', 'Peru', 'Suriname', 'Uruguay', 'Venezuela', 'Fiji', 'Kiribati', 'Marshall Islands', 'Micronesia',
    'Nauru', 'New Zealand', 'Palau', 'Papua New Guinea', 'Samoa', 'Solomon Islands', 'Tonga', 'Tuvalu', 'Vanuatu'
];

// Объединяем страны из БД с полным списком (уникальные значения)
$all_countries = array_unique(array_merge($db_countries, $all_countries));
sort($all_countries);

// Обработка фильтра
$selected_country = $_GET['country'] ?? $user['country'];
$show_only_my_country = isset($_GET['my_country']) && $_GET['my_country'] === '1';

// Запрос для получения оферов с учетом фильтра
if ($show_only_my_country) {
    $offers_stmt = $pdo->prepare("SELECT * FROM offers WHERE country = ? ORDER BY created_at DESC");
    $offers_stmt->execute([$user['country']]);
} elseif ($selected_country !== 'all') {
    $offers_stmt = $pdo->prepare("SELECT * FROM offers WHERE country = ? ORDER BY created_at DESC");
    $offers_stmt->execute([$selected_country]);
} else {
    $offers_stmt = $pdo->prepare("SELECT * FROM offers ORDER BY created_at DESC");
    $offers_stmt->execute();
}

$offers = $offers_stmt->fetchAll(PDO::FETCH_ASSOC);

// Обработка отклика на оффер
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['offer_id'])) {
    $offer_id = $_POST['offer_id'];
    $message = $_POST['message'] ?? '';
    
    try {
        // Проверяем, не откликался ли уже пользователь на этот оффер
        $check_stmt = $pdo->prepare("SELECT id FROM offer_responses WHERE offer_id = ? AND user_id = ?");
        $check_stmt->execute([$offer_id, $_SESSION['user_id']]);
        
        if ($check_stmt->fetch()) {
            $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Вы уже откликались на это предложение'];
        } else {
            // Создаем новый отклик
            $stmt = $pdo->prepare("INSERT INTO offer_responses (offer_id, user_id, message) VALUES (?, ?, ?)");
            $stmt->execute([$offer_id, $_SESSION['user_id'], $message]);
            
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Ваш отклик успешно отправлен'];
        }
        
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (PDOException $e) {
        die("Ошибка при обработке отклика: " . $e->getMessage());
    }
}

// Получение профиля через API

$profile = api_request('GET', AUTH_URL . '/profile', null, $_SESSION['token']);
if (!empty($profile['error']) && $profile['error'] === 'HTTP 401') {
    session_destroy();
    setcookie('token', '', time() - 3600, '/');
    header('Location: login.php');
    exit;
}
// Получаем информацию об откликах пользователя

$user_responses = [];
if (isset($_SESSION['user_id'])) {
    $responses_stmt = $pdo->prepare("SELECT offer_id, status FROM offer_responses WHERE user_id = ?");
    $responses_stmt->execute([$_SESSION['user_id']]);
    $user_responses = $responses_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Функция для генерации стиля аватарки
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
    return "
        background: linear-gradient({$angle}deg, {$palette[0]}, {$palette[1]}, {$palette[2]});
        color: " . getContrastColor($palette[1]) . ";
    ";
}

function getContrastColor($hexColor) {
    $r = hexdec(substr($hexColor, 1, 2));
    $g = hexdec(substr($hexColor, 3, 2));
    $b = hexdec(substr($hexColor, 5, 2));
    $brightness = ($r * 299 + $g * 587 + $b * 114) / 1000;
    return $brightness > 128 ? '#000000' : '#FFFFFF';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная | Volunteering</title>
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

        /* Хедер */
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

        /* Меню пользователя */
        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all var(--transition-speed);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .burger-menu {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 10px;
        }

        .burger-line {
            width: 22px;
            height: 1.5px;
            background-color: var(--accent-color);
            transition: all 0.3s;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background-color: var(--primary-color);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-15px);
            transition: all 0.3s;
            z-index: 1000;
            border: var(--border);
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(5px);
        }

        .menu-item {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--accent-color);
            text-decoration: none;
            transition: all var(--transition-speed);
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        }

        .menu-item:hover {
            background-color: var(--hover-color);
            padding-left: 22px;
            color: var(--secondary-color);
        }

        .menu-item.logout {
            color: var(--danger-color);
            font-weight: 500;
        }

        /* Основной контент */
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
            animation: fadeIn 0.6s;
        }

        .welcome-message {
            background-color: var(--primary-color);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            border: var(--border);
        }

        .welcome-message h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        /* Секция оферов */
        .offers-section {
            margin-top: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
        }

        .section-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 8px;
            background-color: var(--info-color);
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-speed);
            border: none;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--info-color);
            color: var(--info-color);
        }

        .btn-outline:hover {
            background: rgba(52, 152, 219, 0.1);
        }

        .btn.active {
            background-color: var(--success-color);
        }

        .btn.disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .filter-form {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background-color: var(--primary-color);
            color: var(--secondary-color);
            font-size: 0.9rem;
            min-width: 180px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--info-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .offer-card {
            background-color: var(--primary-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all var(--transition-speed);
            border: var(--border);
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }

        .offer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .offer-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .offer-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .offer-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .offer-description {
            color: var(--accent-color);
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }

        .offer-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .offer-country {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .offer-actions {
            margin-top: auto;
        }

        .response-form {
            display: none;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .response-form.active {
            display: block;
        }

        .response-textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
            resize: vertical;
            min-height: 80px;
        }

        .response-status {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 10px;
            display: inline-block;
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

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            color: var(--accent-color);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: rgba(0, 0, 0, 0.1);
        }

        .flash-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1100;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s, fadeOut 0.5s 3s forwards;
        }

        .flash-success {
            background-color: var(--success-color);
            color: white;
        }

        .flash-error {
            background-color: var(--danger-color);
            color: white;
        }

        /* Модальное окно */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: var(--primary-color);
            border-radius: 16px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transform: translateY(20px);
            transition: all var(--transition-speed);
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--accent-color);
            transition: color var(--transition-speed);
        }

        .modal-close:hover {
            color: var(--danger-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .modal-description {
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .modal-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .modal-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--accent-color);
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Анимации */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }

        /* Адаптивность */
        @media (max-width: 768px) {
            header {
                padding: 1rem;
            }
            
            .container {
                padding: 0 1rem;
                margin: 1.5rem auto;
            }
            
            .offers-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-controls {
                width: 100%;
            }

            .filter-form {
                width: 100%;
            }

            .filter-select {
                width: 100%;
            }
            
            .modal-content {
                width: 95%;
            }
            
            .modal-meta {
                flex-direction: column;
                gap: 0.5rem;
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
        
        <div class="user-menu">
            <div class="user-avatar float" style="<?= generateAvatarStyle($user['username']) ?>">
                <?= strtoupper(substr($user['username'], 0, 1)) ?>
            </div>
            <div class="burger-menu" id="burgerMenu">
                <div class="burger-line"></div>
                <div class="burger-line"></div>
                <div class="burger-line"></div>
            </div>
            
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="profile.php" class="menu-item">
                    <i class="fas fa-user-circle"></i> Мой профиль
                </a>
                <a href="settings.php" class="menu-item">
                    <i class="fas fa-sliders-h"></i> Настройки
                </a>
                <div class="menu-divider"></div>
                <a href="logout.php" class="menu-item logout">
                    <i class="fas fa-sign-out-alt"></i> Выйти
                </a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message flash-<?= $_SESSION['flash_message']['type'] ?>">
                <i class="fas fa-<?= $_SESSION['flash_message']['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= $_SESSION['flash_message']['message'] ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <div class="welcome-message">
            <h1>Добро пожаловать, <?= htmlspecialchars($user['username']) ?>!</h1>
            <p>Мы рады видеть вас в нашем сообществе волонтеров. Здесь вы можете найти интересные проекты, присоединиться к мероприятиям и сделать мир лучше.</p>
        </div>
        
        <div class="offers-section">
            <div class="section-header">
                <h2 class="section-title">Волонтерские предложения</h2>
                
                <div class="section-controls">
                    <form method="GET" class="filter-form">
                        <select name="country" class="filter-select" onchange="this.form.submit()">
                            <option value="all" <?= $selected_country === 'all' ? 'selected' : '' ?>>Все страны</option>
                            <option value="<?= htmlspecialchars($user['country']) ?>" <?= $selected_country === $user['country'] && !$show_only_my_country ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user['country']) ?> (моя страна)
                            </option>
                            <optgroup label="Другие страны">
                                <?php foreach ($all_countries as $country): ?>
                                    <?php if ($country !== $user['country']): ?>
                                        <option value="<?= htmlspecialchars($country) ?>" <?= $selected_country === $country ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($country) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </optgroup>
                        </select>
                        
                        <a href="?country=<?= htmlspecialchars($user['country']) ?>&my_country=1" 
                           class="btn btn-outline <?= $show_only_my_country ? 'active' : '' ?>">
                            <i class="fas fa-map-marker-alt"></i> Только моя страна
                        </a>
                    </form>
                    
                    <a href="create_offer.php" class="btn">
                        <i class="fas fa-plus"></i> Предложить офер
                    </a>
                </div>
            </div>
            
            <div class="offers-grid">
                <?php if (empty($offers)): ?>
                    <div class="empty-state">
                        <i class="fas fa-hands-helping"></i>
                        <h3>Нет предложений по выбранному фильтру</h3>
                        <p>Попробуйте изменить параметры поиска или создайте свое предложение</p>
                        <a href="create_offer.php" class="btn" style="margin-top: 1rem;">
                            <i class="fas fa-plus"></i> Создать офер
                        </a>
                    </div>
                <?php else: ?>
                    <?php foreach ($offers as $offer): ?>
                        <div class="offer-card" data-offer-id="<?= $offer['id'] ?>">
                            <?php if ($offer['image_path']): ?>
                                <img src="<?= htmlspecialchars($offer['image_path']) ?>" alt="<?= htmlspecialchars($offer['title']) ?>" class="offer-image">
                            <?php else: ?>
                                <div style="height: 180px; background: linear-gradient(135deg, #f5f7fa, #c3cfe2); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-hands-helping" style="font-size: 3rem; color: rgba(0,0,0,0.1);"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="offer-content">
                                <h3 class="offer-title"><?= htmlspecialchars($offer['title']) ?></h3>
                                <p class="offer-description"><?= htmlspecialchars($offer['description']) ?></p>
                                <div class="offer-meta">
                                    <span class="offer-date">
                                        <i class="far fa-calendar-alt"></i> 
                                        <?= date('d.m.Y', strtotime($offer['created_at'])) ?>
                                    </span>
                                    <span class="offer-country">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?= htmlspecialchars($offer['country']) ?>
                                    </span>
                                </div>
                                
                                <div class="offer-actions">
                                    <?php if (isset($user_responses[$offer['id']])): ?>
                                        <?php 
                                        $status = $user_responses[$offer['id']];
                                        $status_class = "status-$status";
                                        $status_text = [
                                            'pending' => 'Ожидает рассмотрения',
                                            'accepted' => 'Принято',
                                            'rejected' => 'Отклонено'
                                        ][$status];
                                        ?>
                                        <div class="response-status <?= $status_class ?>">
                                            <i class="fas fa-<?= 
                                                $status === 'accepted' ? 'check-circle' : 
                                                ($status === 'rejected' ? 'times-circle' : 'clock')
                                            ?>"></i> 
                                            <?= $status_text ?>
                                        </div>
                                    <?php else: ?>
                                        <button class="btn response-btn" data-offer-id="<?= $offer['id'] ?>">
                                            <i class="fas fa-hand-paper"></i> Откликнуться
                                        </button>
                                        
                                        <form method="POST" class="response-form" id="response-form-<?= $offer['id'] ?>">
                                            <input type="hidden" name="offer_id" value="<?= $offer['id'] ?>">
                                            <textarea name="message" class="response-textarea" placeholder="Напишите сопроводительное письмо..."></textarea>
                                            <button type="submit" class="btn">
                                                <i class="fas fa-paper-plane"></i> Отправить
                                            </button>
                                            <button type="button" class="btn btn-outline cancel-btn" style="margin-left: 10px;">
                                                Отмена
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Модальное окно для просмотра оффера -->
    <div class="modal-overlay" id="offerModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalOfferTitle">Загрузка...</h3>
                <button class="modal-close" id="modalClose">&times;</button>
            </div>
            <div class="modal-body">
                <img src="" alt="" class="modal-image" id="modalOfferImage" style="display: none;">
                <div class="modal-description" id="modalOfferDescription"></div>
                <div class="modal-meta">
                    <div class="modal-meta-item">
                        <i class="fas fa-user"></i>
                        <span id="modalOfferAuthor"></span>
                    </div>
                    <div class="modal-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="modalOfferCountry"></span>
                    </div>
                    <div class="modal-meta-item">
                        <i class="far fa-calendar-alt"></i>
                        <span id="modalOfferDate"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modalOfferActions">
                <!-- Кнопки действий будут добавлены динамически -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Меню пользователя
            const burgerMenu = document.getElementById('burgerMenu');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            burgerMenu.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
                dropdownMenu.classList.toggle('active');
            });
            
            document.addEventListener('click', function() {
                burgerMenu.classList.remove('active');
                dropdownMenu.classList.remove('active');
            });
            
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Обработка откликов
            const responseButtons = document.querySelectorAll('.response-btn');
            responseButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const offerId = this.getAttribute('data-offer-id');
                    const form = document.getElementById(`response-form-${offerId}`);
                    form.classList.add('active');
                    this.style.display = 'none';
                });
            });

            const cancelButtons = document.querySelectorAll('.cancel-btn');
            cancelButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const form = this.closest('.response-form');
                    form.classList.remove('active');
                    form.previousElementSibling.style.display = 'inline-flex';
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

            // Модальное окно для просмотра офферов
            const offerModal = document.getElementById('offerModal');
            const modalClose = document.getElementById('modalClose');
            const offerCards = document.querySelectorAll('.offer-card');

            // Обработка кликов по карточкам офферов
            offerCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Игнорируем клики по кнопкам и формам внутри карточки
                    if (e.target.closest('.response-btn') || 
                        e.target.closest('.response-form') || 
                        e.target.closest('.cancel-btn')) {
                        return;
                    }
                    
                    const offerId = this.getAttribute('data-offer-id');
                    showOfferModal(offerId);
                });
            });
            
            // Закрытие модального окна
            modalClose.addEventListener('click', closeModal);
            
            // Закрытие при клике на оверлей
            offerModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
            
            // Закрытие при нажатии Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && offerModal.classList.contains('active')) {
                    closeModal();
                }
            });

            // Функция показа модального окна
            function showOfferModal(offerId) {
                // Показываем лоадер
                document.getElementById('modalOfferTitle').textContent = 'Загрузка...';
                document.getElementById('modalOfferDescription').textContent = '';
                document.getElementById('modalOfferCountry').textContent = '';
                document.getElementById('modalOfferDate').textContent = '';
                document.getElementById('modalOfferAuthor').textContent = '';
                document.getElementById('modalOfferActions').innerHTML = '';
                
                const image = document.getElementById('modalOfferImage');
                image.src = '';
                image.style.display = 'none';
                
                offerModal.classList.add('active');
                
                // Загружаем данные оффера
                fetchOfferData(offerId)
                    .then(offer => {
                        // Заполняем модальное окно данными
                        document.getElementById('modalOfferTitle').textContent = offer.title;
                        document.getElementById('modalOfferDescription').textContent = offer.description;
                        document.getElementById('modalOfferCountry').textContent = offer.country;
                        document.getElementById('modalOfferDate').textContent = formatDate(offer.created_at);
                        
                        // Устанавливаем изображение
                        if (offer.image_path) {
                            image.src = offer.image_path;
                            image.style.display = 'block';
                            image.alt = offer.title;
                        }
                        
                        // Загружаем данные автора
                        return fetchUserData(offer.user_id);
                    })
                    .then(user => {
                        document.getElementById('modalOfferAuthor').textContent = user.username;
                        
                        // Добавляем кнопки действий
                        setupModalActions(offerId);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('modalOfferTitle').textContent = 'Ошибка';
                        document.getElementById('modalOfferDescription').textContent = 'Не удалось загрузить данные оффера. Пожалуйста, попробуйте позже.';
                    });
            }

            // Функция закрытия модального окна
            function closeModal() {
                offerModal.classList.remove('active');
            }

            // Форматирование даты
            function formatDate(dateString) {
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Date(dateString).toLocaleDateString('ru-RU', options);
            }

            // Настройка кнопок действий в модальном окне
            function setupModalActions(offerId) {
                const actionsContainer = document.getElementById('modalOfferActions');
                actionsContainer.innerHTML = '';
                
                // Проверяем, откликался ли пользователь
                const userResponses = <?= json_encode($user_responses ?? []) ?>;
                const responseStatus = userResponses[offerId];
                
                if (responseStatus) {
                    // Показываем статус отклика
                    const statusText = {
                        'pending': 'Ожидает рассмотрения',
                        'accepted': 'Принято',
                        'rejected': 'Отклонено'
                    }[responseStatus];
                    
                    const statusClass = {
                        'pending': 'status-pending',
                        'accepted': 'status-accepted',
                        'rejected': 'status-rejected'
                    }[responseStatus];
                    
                    const statusDiv = document.createElement('div');
                    statusDiv.className = `response-status ${statusClass}`;
                    statusDiv.innerHTML = `
                        <i class="fas fa-${responseStatus === 'accepted' ? 'check-circle' : 
                          (responseStatus === 'rejected' ? 'times-circle' : 'clock')}"></i> 
                        ${statusText}
                    `;
                    actionsContainer.appendChild(statusDiv);
                } else {
                    // Добавляем кнопку отклика
                    const responseBtn = document.createElement('button');
                    responseBtn.className = 'btn';
                    responseBtn.innerHTML = '<i class="fas fa-hand-paper"></i> Откликнуться';
                    responseBtn.addEventListener('click', function() {
                        closeModal();
                        const form = document.querySelector(`#response-form-${offerId}`);
                        if (form) {
                            form.classList.add('active');
                            const responseBtn = form.previousElementSibling;
                            if (responseBtn) {
                                responseBtn.style.display = 'none';
                            }
                        }
                    });
                    actionsContainer.appendChild(responseBtn);
                }
            }

            // Функция для загрузки данных оффера
            function fetchOfferData(offerId) {
                return new Promise((resolve, reject) => {
                    // В реальном приложении здесь был бы fetch к серверу
                    // Для демонстрации используем данные из PHP
                    const offers = <?= json_encode($offers) ?>;
                    const offer = offers.find(o => o.id == offerId);
                    
                    if (offer) {
                        // Имитируем задержку сети
                        setTimeout(() => resolve(offer), 300);
                    } else {
                        reject(new Error('Offer not found'));
                    }
                });
            }

            // Функция для загрузки данных пользователя
            function fetchUserData(userId) {
                return new Promise((resolve, reject) => {
                    // В реальном приложении здесь был бы fetch к серверу
                    // Для демонстрации используем текущего пользователя
                    const user = <?= json_encode($user) ?>;
                    
                    if (user) {
                        // Имитируем задержку сети
                        setTimeout(() => resolve({
                            id: user.id,
                            username: user.username,
                            email: user.email
                        }), 300);
                    } else {
                        reject(new Error('User not found'));
                    }
                });
            }
        });
    </script>
</body>
</html>

<?php
require "footer.php";
