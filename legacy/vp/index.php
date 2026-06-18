<?php
// Включим механизм сессий
session_start();

// Проверим, авторизован ли пользователь (через сессию или куки)
$is_logged_in = isset($_SESSION['user_id']) || isset($_COOKIE['user_id']);

// Если пользователь не авторизован - перенаправляем на страницу входа
if (!$is_logged_in) {
    header("Location: login.php");
    exit;
}

// Если авторизован - перенаправляем на главную страницу (main.php)
header("Location: main.php");
exit;
?>