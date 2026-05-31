<?php
session_start();

// только администратор
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require '../db.php';

// удаление страницы
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM pages WHERE id = $id");
    $_SESSION['message'] = 'Страница удалена.';
    header('Location: pages.php');
    exit;
}

// сохранение редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id      = intval($_POST['id']);
    $title   = mysqli_real_escape_string($conn, trim($_POST['title']));
    $content = mysqli_real_escape_string($conn, trim($_POST['content']));
    mysqli_query($conn, "UPDATE pages SET title='$title', content='$content' WHERE id=$id");
    $_SESSION['message'] = 'Страница сохранена.';
    header('Location: pages.php');
    exit;
}

// добавление новой страницы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $title   = mysqli_real_escape_string($conn, trim($_POST['title']));
    $content = mysqli_real_escape_string($conn, trim($_POST['content']));
    $slug    = mysqli_real_escape_string($conn, trim($_POST['slug']));
    mysqli_query($conn, "INSERT INTO pages (title, content, slug) VALUES ('$title', '$content', '$slug')");
    $_SESSION['message'] = 'Страница добавлена.';
    header('Location: pages.php');
    exit;
}

// редактирование
$editPage = null;
if (isset($_GET['edit'])) {
    $id     = intval($_GET['edit']);
    $res    = mysqli_query($conn, "SELECT * FROM pages WHERE id = $id");
    $editPage = mysqli_fetch_assoc($res);
}

// читаем все страницы
$result = mysqli_query($conn, "SELECT * FROM pages ORDER BY id ASC");
$pages  = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ — Страницы</title>
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
        .btn-edit {
            padding: 6px 14px;
            background-color: #162447;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-edit:hover { background-color: #1f4068; }
        .edit-form {
            background-color: #f8f9ff;
            border: 1px solid #d0d8f0;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
        }
        .edit-form h3 { margin-bottom: 16px; color: #1a1a2e; }
        .edit-form input, .edit-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 12px;
            box-sizing: border-box;
        }
        .edit-form textarea { height: 120px; resize: vertical; }
        .btn-save {
            padding: 10px 24px;
            background-color: #1a1a2e;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }
        .btn-save:hover { background-color: #2d2d4e; }
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

        <!-- форма редактирования -->
        <?php if ($editPage): ?>
        <div class="edit-form">
            <h3>Редактировать страницу</h3>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo intval($editPage['id']); ?>">
                <label>Заголовок</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($editPage['title']); ?>">
                <label>Содержимое</label>
                <textarea name="content"><?php echo htmlspecialchars($editPage['content']); ?></textarea>
                <button type="submit" name="save" class="btn-save">Сохранить</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- форма добавления -->
        <div class="edit-form">
            <h3>Добавить страницу</h3>
            <form method="post">
                <label>Заголовок</label>
                <input type="text" name="title" placeholder="Название страницы">
                <label>Slug (уникальный ключ)</label>
                <input type="text" name="slug" placeholder="например: about">
                <label>Содержимое</label>
                <textarea name="content" placeholder="Текст страницы..."></textarea>
                <button type="submit" name="add" class="btn-save">Добавить</button>
            </form>
        </div>

        <!-- список страниц -->
        <div class="content-box">
            <h2>Все страницы (<?php echo count($pages); ?>)</h2>
            <?php if (empty($pages)): ?>
                <p>Страниц пока нет.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Заголовок</th>
                            <th>Slug</th>
                            <th>Содержимое</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                            <tr>
                                <td><?php echo intval($page['id']); ?></td>
                                <td><?php echo htmlspecialchars($page['title']); ?></td>
                                <td><?php echo htmlspecialchars($page['slug']); ?></td>
                                <td><?php echo htmlspecialchars(mb_substr($page['content'], 0, 60)) . '...'; ?></td>
                                <td>
                                    <a class="btn-edit" href="pages.php?edit=<?php echo intval($page['id']); ?>">
                                        Редактировать
                                    </a>
                                    &nbsp;
                                    <a class="btn-delete"
                                       href="pages.php?delete=<?php echo intval($page['id']); ?>"
                                       onclick="return confirm('Удалить страницу?')">
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
