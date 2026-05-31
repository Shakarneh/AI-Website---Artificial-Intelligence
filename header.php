<?php
header("Content-Type: text/html; charset=utf-8");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($pageTitle)) {
    $pageTitle = "Искусственный интеллект";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page-container">
    <header class="site-header">
        <div class="logo-box">
            <img src="images/logo.jpg" alt="logo">
            <h1>Искусственный интеллект</h1>
        </div>

        <div class="user-panel">
            <?php if (isset($_SESSION['user'])): ?>
                <span>Привет, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
                <a href="logout.php">Выйти</a>
            <?php else: ?>
                <a href="register.php">Регистрация</a>
                <a href="login.php">Вход</a>
            <?php endif; ?>
        </div>
    </header>