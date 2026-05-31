<?php
session_start();
require 'db.php';

$pageTitle = "Искусственный интеллект";

// загрузка контента из таблицы pages (slug='index')
$pageRow = null;
$res = mysqli_query($conn, "SELECT * FROM pages WHERE slug = 'index' LIMIT 1");
if ($res) {
    $pageRow = mysqli_fetch_assoc($res);
}

require 'header.php';
require 'menu.php';
?>

<main class="main-content">
    <section class="content-box">
        <h2>Информация</h2>
        <p id="welcomeMessage"></p>
        <p><strong>Текущая дата:</strong> <span id="currentDate"></span></p>
        <p><strong>Текущее время:</strong> <span id="clock">00:00:00</span></p>
    </section>

    <section class="content-box">
        <h2><?php echo $pageRow ? htmlspecialchars($pageRow['title']) : 'Что такое ИИ?'; ?></h2>
        <p>
            <?php if ($pageRow): ?>
                <?php echo nl2br(htmlspecialchars($pageRow['content'])); ?>
            <?php else: ?>
                Искусственный интеллект — это раздел информатики,
                который занимается созданием систем, способных
                выполнять задачи, требующие человеческого интеллекта.
            <?php endif; ?>
        </p>
    </section>

    <section class="content-box">
        <h2 onmouseover="changeTextColor(this)" onmouseout="resetTextColor(this)">Почему ИИ важен?</h2>
        <p>
            Искусственный интеллект используется в медицине,
            образовании, транспорте, промышленности и бизнесе.
        </p>
    </section>

    <section class="content-box image-box">
        <h2>Иллюстрация темы</h2>
        <p>Нажмите на изображение, чтобы изменить его.</p>

        <img src="images/ai.jpg"
             alt="ai image"
             id="mainImage"
             onclick="changeImage()"
             onmouseover="makeTransparent(this)"
             onmouseout="resetTransparency(this)">
    </section>
</main>

<?php require 'footer.php'; ?>