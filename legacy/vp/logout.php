<?php
// Начинаем сессию
session_start();

// 1. Удаляем все данные сессии
$_SESSION = array();

// 2. Удаляем сессионную куку
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Уничтожаем сессию
session_destroy();

// 4. Удаляем пользовательские куки (которые мы устанавливали на 2 месяца)
setcookie('username', '', time() - 3600, '/');
setcookie('email', '', time() - 3600, '/');
setcookie('user_id', '', time() - 3600, '/');

// 5. Перенаправляем на страницу входа с сообщением о успешном выходе
$_SESSION['logout_message'] = 'Вы успешно вышли из системы';
header('Location: main.php');
exit;
?>