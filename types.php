<?php
session_start();
require 'db.php';

$pageTitle = "Виды искусственного интеллекта";

// загрузка контента из таблицы pages (slug='types')
$pageRow = null;
$res = mysqli_query($conn, "SELECT * FROM pages WHERE slug = 'types' LIMIT 1");
if ($res) {
    $pageRow = mysqli_fetch_assoc($res);
}

require 'header.php';
require 'menu.php';
?>

<main class="main-content">
    <section class="content-box">
        <h2><?php echo $pageRow ? htmlspecialchars($pageRow['title']) : 'Виды искусственного интеллекта'; ?></h2>
        <p>
            <?php if ($pageRow): ?>
                <?php echo nl2br(htmlspecialchars($pageRow['content'])); ?>
            <?php else: ?>
                Искусственный интеллект можно разделить на несколько видов
                в зависимости от его возможностей и уровня развития.
            <?php endif; ?>
        </p>
    </section>

    <section class="cards-container">
        <article class="card" onmouseover="enlargeCard(this)" onmouseout="resetCard(this)">
            <h3>Узкий ИИ</h3>
            <p>
                Узкий искусственный интеллект предназначен для выполнения
                одной конкретной задачи.
            </p>
            <ul>
                <li><span>Пример:</span> голосовые помощники.</li>
                <li><span>Особенность:</span> высокая эффективность в одной области.</li>
            </ul>
        </article>

        <article class="card" onmouseover="enlargeCard(this)" onmouseout="resetCard(this)">
            <h3>Общий ИИ</h3>
            <p>
                Общий искусственный интеллект — это система, которая может
                выполнять разные интеллектуальные задачи на уровне человека.
            </p>
            <ul>
                <li><span>Пример:</span> пока находится на стадии исследований.</li>
                <li><span>Особенность:</span> универсальность мышления.</li>
            </ul>
        </article>

        <article class="card" onmouseover="enlargeCard(this)" onmouseout="resetCard(this)">
            <h3>Сверхинтеллект</h3>
            <p>
                Сверхинтеллект — это гипотетический уровень ИИ, который
                превосходит возможности человека.
            </p>
            <ul>
                <li><span>Пример:</span> теоретическая концепция.</li>
                <li><span>Особенность:</span> способность к сверхбыстрому анализу.</li>
            </ul>
        </article>
    </section>

    <section class="content-box">
        <h2>Сравнение видов ИИ</h2>
        <table>
            <thead>
                <tr>
                    <th>Вид ИИ</th>
                    <th>Описание</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Узкий ИИ</td>
                    <td>Решает одну конкретную задачу</td>
                    <td>Используется сегодня</td>
                </tr>
                <tr>
                    <td>Общий ИИ</td>
                    <td>Может выполнять разные интеллектуальные задачи</td>
                    <td>Разрабатывается</td>
                </tr>
                <tr>
                    <td>Сверхинтеллект</td>
                    <td>Превосходит возможности человека</td>
                    <td>Теоретическая модель</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<?php require 'footer.php'; ?>