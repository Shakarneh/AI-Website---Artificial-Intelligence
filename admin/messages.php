<?php
session_start();

// только администратор
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require '../db.php';

// удаление сообщения
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM messages WHERE id = $id");
    $_SESSION['message'] = 'Сообщение удалено.';
    header('Location: messages.php');
    exit;
}

// читаем все сообщения
$result = mysqli_query($conn, "SELECT * FROM messages ORDER BY id DESC");
$messages = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ — Сообщения</title>
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
        .alert-success {
            background-color: #d4edda;
            border: 1px solid #a3d9a5;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }
        .btn-delete {
            padding: 6px 14px;
            background-color: #b30000;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-delete:hover { background-color: #7a0000; }
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
        <a href="users.php">Пользователи</a>
        <a href="pages.php">Страницы</a>
        <a href="messages.php">Сообщения</a>
        <a href="reviews.php">Отзывы</a>
    </nav>

    <div class="admin-content">

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="content-box">
            <h2>Все сообщения (<?php echo count($messages); ?>)</h2>

            <?php if (empty($messages)): ?>
                <p>Сообщений пока нет.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Дата</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Возраст</th>
                            <th>Комментарий</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $msg): ?>
                            <tr>
                                <td><?php echo intval($msg['id']); ?></td>
                                <td><?php echo htmlspecialchars($msg['date']); ?></td>
                                <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                <td><?php echo htmlspecialchars($msg['age']); ?></td>
                                <td><?php echo htmlspecialchars(mb_substr($msg['comment'], 0, 80)) . (mb_strlen($msg['comment']) > 80 ? '...' : ''); ?></td>
                                <td>
                                    <a class="btn-delete"
                                       href="messages.php?delete=<?php echo intval($msg['id']); ?>"
                                       onclick="return confirm('Удалить сообщение?')">
                                        Удалить
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>

    <footer class="site-footer">
        <p>2026 Все права защищены</p>
    </footer>
</div>
</body>
</html>
