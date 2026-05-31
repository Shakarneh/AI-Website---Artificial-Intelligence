<?php
session_start();

// только администратор
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require '../db.php';

// считаем статистику
$usersCount    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
$reviewsCount  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM reviews"))[0];
$messagesCount = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM messages"))[0];
$pagesCount    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pages"))[0];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ — Главная</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-header {
            background-color: #1a1a2e;
            color: white;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 { font-size: 20px; }
        .admin-header a { color: #f5a623; text-decoration: none; font-size: 14px; }
        .admin-header a:hover { text-decoration: underline; }
        .admin-nav {
            background-color: #162447;
            display: flex;
            gap: 20px;
            padding: 12px 24px;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }
        .admin-nav a:hover { color: #f5a623; }
        .admin-content { padding: 25px; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        .stat-card {
            background-color: #1a1a2e;
            color: white;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
        }
        .stat-card h3 { font-size: 14px; margin-bottom: 10px; opacity: 0.8; }
        .stat-card .stat-number { font-size: 40px; font-weight: bold; color: #f5a623; }
        .quick-links { display: flex; gap: 15px; flex-wrap: wrap; }
        .quick-link {
            padding: 12px 24px;
            background-color: #162447;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }
        .quick-link:hover { background-color: #1f4068; }
    </style>
</head>
<body>
<div class="page-container">

    <div class="admin-header">
        <h1>Панель администратора</h1>
        <div>
            <a href="../index.php">← На сайт</a>
            &nbsp;&nbsp;
            <a href="../logout.php">Выйти</a>
        </div>
    </div>

    <nav class="admin-nav">
        <a href="index.php">Главная</a>
        <a href="users.php">Пользователи</a>
        <a href="pages.php">Страницы</a>
        <a href="messages.php">Сообщения</a>
        <a href="reviews.php">Отзывы</a>
    </nav>

    <div class="admin-content">

        <div class="content-box" style="margin-bottom: 25px;">
            <h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
        </div>

        <!-- статистика -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Пользователи</h3>
                <div class="stat-number"><?php echo $usersCount; ?></div>
            </div>
            <div class="stat-card">
                <h3>Отзывы</h3>
                <div class="stat-number"><?php echo $reviewsCount; ?></div>
            </div>
            <div class="stat-card">
                <h3>Сообщения</h3>
                <div class="stat-number"><?php echo $messagesCount; ?></div>
            </div>
            <div class="stat-card">
                <h3>Страницы</h3>
                <div class="stat-number"><?php echo $pagesCount; ?></div>
            </div>
        </div>

        <!-- быстрые ссылки -->
        <div class="content-box">
            <h2 style="margin-bottom: 16px;">Быстрый доступ</h2>
            <div class="quick-links">
                <a class="quick-link" href="users.php">Управление пользователями</a>
                <a class="quick-link" href="pages.php">Управление страницами</a>
                <a class="quick-link" href="messages.php">Управление сообщениями</a>
                <a class="quick-link" href="reviews.php">Управление отзывами</a>
            </div>
        </div>

    </div>

    <footer class="site-footer">
        <p>2026 Все права защищены</p>
    </footer>
</div>
</body>
</html>
