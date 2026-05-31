<?php
// запуск сессии
session_start();

// проверка: только администратор
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// подключение к базе
require '../db.php';

// удаление отзыва
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // получаем имя файла фото перед удалением
    $res = mysqli_query($conn, "SELECT image FROM reviews WHERE id = $id");
    $row = mysqli_fetch_assoc($res);

    if ($row && !empty($row['image'])) {
        $filePath = __DIR__ . '/../images/' . $row['image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // удаляем запись из базы
    mysqli_query($conn, "DELETE FROM reviews WHERE id = $id");

    $_SESSION['message'] = 'Отзыв удалён.';
    header('Location: reviews.php');
    exit;
}

// читаем все отзывы
$result  = mysqli_query($conn, "SELECT * FROM reviews ORDER BY id DESC");
$reviews = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
}

// вспомогательная функция звёзд
function buildStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return $stars;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ — Отзывы</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* стили админки */

        .admin-header {
            background-color: #1a1a2e;
            color: white;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            font-size: 20px;
        }

        .admin-header a {
            color: #f5a623;
            text-decoration: none;
            font-size: 14px;
        }

        .admin-header a:hover {
            text-decoration: underline;
        }

        .admin-content {
            padding: 25px;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #a3d9a5;
            color: #155724;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .review-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
        }

        .stars-cell {
            color: #f5a623;
            font-size: 16px;
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

        .btn-delete:hover {
            background-color: #7a0000;
        }

        .no-reviews {
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="page-container">

    <div class="admin-header">
        <h1>Панель администратора — Отзывы</h1>
        <div>
            <a href="../reviews.php">← На сайт</a>
            &nbsp;&nbsp;
            <a href="../logout.php">Выйти</a>
        </div>
    </div>

    <div class="admin-content">

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert-success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="content-box">
            <h2>Все отзывы (<?php echo count($reviews); ?>)</h2>

            <?php if (empty($reviews)): ?>
                <p class="no-reviews">Отзывов пока нет.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Имя</th>
                            <th>Оценка</th>
                            <th>Текст</th>
                            <th>Фото</th>
                            <th>Дата</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td><?php echo intval($review['id']); ?></td>
                                <td><?php echo htmlspecialchars($review['username']); ?></td>
                                <td class="stars-cell">
                                    <?php echo buildStars(intval($review['rating'])); ?>
                                </td>
                                <td><?php echo htmlspecialchars(mb_substr($review['text'], 0, 80)) . (mb_strlen($review['text']) > 80 ? '...' : ''); ?></td>
                                <td>
                                    <?php
                                    $imgPath = __DIR__ . '/../images/' . $review['image'];
                                    if (!empty($review['image']) && file_exists($imgPath)):
                                    ?>
                                        <img class="review-thumb"
                                             src="../images/<?php echo htmlspecialchars($review['image']); ?>"
                                             alt="фото">
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                                <td>
                                    <a class="btn-delete"
                                       href="reviews.php?delete=<?php echo intval($review['id']); ?>"
                                       onclick="return confirm('Удалить этот отзыв?')">
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
