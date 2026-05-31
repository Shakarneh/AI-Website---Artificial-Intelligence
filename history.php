<?php
session_start();
require 'db.php';

$pageTitle = "История искусственного интеллекта";

// загрузка контента из таблицы pages (slug='history')
$pageRow = null;
$res = mysqli_query($conn, "SELECT * FROM pages WHERE slug = 'history' LIMIT 1");
if ($res) {
    $pageRow = mysqli_fetch_assoc($res);
}

require 'header.php';
require 'menu.php';
?>

<main class="main-content">
    <section class="content-box">
        <h2><?php echo $pageRow ? htmlspecialchars($pageRow['title']) : 'История развития ИИ'; ?></h2>
        <p>
            <?php if ($pageRow): ?>
                <?php echo nl2br(htmlspecialchars($pageRow['content'])); ?>
            <?php else: ?>
                История искусственного интеллекта началась в середине XX века.
                Учёные стремились создать программы, способные имитировать
                человеческое мышление и принимать решения.
            <?php endif; ?>
        </p>
    </section>

    <section class="content-box">
        <h2 onmouseover="changeTextColor(this)" onmouseout="resetTextColor(this)">Основные этапы развития</h2>
        <ul>
            <li><span>1950 год:</span> Алан Тьюринг предложил тест Тьюринга.</li>
            <li><span>1956 год:</span> Дартмутская конференция стала началом ИИ как науки.</li>
            <li><span>1980-е годы:</span> развитие экспертных систем.</li>
            <li><span>2010-е годы:</span> быстрый рост машинного обучения и нейронных сетей.</li>
        </ul>
    </section>

    <section class="content-box">
        <h2>Ключевые события</h2>
        <table>
            <thead>
                <tr>
                    <th>Год</th>
                    <th>Событие</th>
                    <th>Значение</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1950</td>
                    <td>Тест Тьюринга</td>
                    <td>Предложен способ оценки разумности машины</td>
                </tr>
                <tr>
                    <td>1956</td>
                    <td>Дартмутская конференция</td>
                    <td>Официальное появление термина «искусственный интеллект»</td>
                </tr>
                <tr>
                    <td>1980</td>
                    <td>Экспертные системы</td>
                    <td>Широкое использование ИИ в практических задачах</td>
                </tr>
                <tr>
                    <td>2010</td>
                    <td>Глубокое обучение</td>
                    <td>Новый этап развития нейронных сетей</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>

<?php require 'footer.php'; ?>