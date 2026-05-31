<nav class="main-nav">
    <a href="index.php">Главная</a>
    <a href="history.php">История</a>
    <a href="types.php">Виды</a>
    <a href="applications.php">Применение</a>
    <a href="about.php">Размер файлов</a>
    <a href="reviews.php">Отзывы</a>

    <div class="dropdown" onmouseover="showDropdown()" onmouseout="hideDropdown()">
        <span class="dropdown-btn">Дополнительно</span>
        <div class="dropdown-content" id="dropdownMenu">
            <a href="types.php">Типы ИИ</a>
            <a href="applications.php">Области применения</a>
            <a href="about.php">Размер сайта</a>
        </div>
    </div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <a href="/admin/">Админка</a>
<?php endif; ?>
</nav>