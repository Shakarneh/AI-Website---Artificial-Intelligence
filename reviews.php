<?php
session_start();
require 'db.php';

$pageTitle = "Отзывы — Искусственный интеллект";
require 'header.php';
require 'menu.php';

// читаем все отзывы из базы
$result  = mysqli_query($conn, "SELECT * FROM reviews ORDER BY id DESC");
$reviews = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
}

// считаем среднее и количество
$total     = count($reviews);
$avgRating = 0;

if ($total > 0) {
    $sum = 0;
    foreach ($reviews as $r) {
        $sum += intval($r['rating']);
    }
    $avgRating = round($sum / $total, 1);
}

// вспомогательная функция — строка звёзд
function buildStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return $stars;
}
?>

<style>
    /* ===== СЕКЦИЯ ОТЗЫВОВ ===== */

    /* верхний блок: заголовок + статистика слева, кнопка справа */
    .reviews-header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    /* заголовок */
    .reviews-header-left h2 {
        font-size: 22px;
        font-weight: bold;
        text-transform: uppercase;
        color: #1a1a2e;
        margin: 0 0 12px 0;
    }

    /* строка: Средняя оценка: ★★★★★ 5.0 */
    .avg-line {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
        font-size: 15px;
        color: #333;
    }

    .avg-line .avg-stars {
        color: #f5a623;
        font-size: 20px;
    }

    .avg-line .avg-number {
        font-weight: bold;
        font-size: 17px;
        color: #1a1a2e;
    }

    /* строка: Количество отзывов: N */
    .reviews-count {
        font-size: 14px;
        color: #555;
    }

    /* кнопка "Написать отзыв" */
    .btn-add-review {
        padding: 12px 26px;
        background-color: #1a1a2e;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-add-review:hover {
        background-color: #2d2d4e;
    }

    /* сообщения об успехе и ошибке */
    .alert-success {
        background-color: #d4edda;
        border: 1px solid #a3d9a5;
        color: #155724;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .alert-error {
        background-color: #ffe3e3;
        border: 1px solid #cc0000;
        color: #7a0000;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    /* форма добавления отзыва */
    .review-form-box {
        display: none;
        background-color: #f8f9ff;
        border: 1px solid #d0d8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .review-form-box h3 {
        margin: 0 0 16px 0;
        color: #1a1a2e;
        font-size: 18px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        font-size: 14px;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
    }

    .form-group textarea {
        height: 90px;
        resize: vertical;
    }

    /* звёзды в форме (radio) */
    .star-radio-group {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 6px;
    }

    .star-radio-group input[type="radio"] {
        display: none;
    }

    .star-radio-group label {
        font-size: 32px;
        color: #ccc;
        cursor: pointer;
        margin-bottom: 0;
    }

    /* подсветка звёзд при выборе */
    .star-radio-group input[type="radio"]:checked ~ label,
    .star-radio-group label:hover,
    .star-radio-group label:hover ~ label {
        color: #f5a623;
    }

    .btn-submit {
        padding: 10px 24px;
        background-color: #1a1a2e;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        cursor: pointer;
        margin-right: 10px;
    }

    .btn-submit:hover {
        background-color: #2d2d4e;
    }

    .btn-cancel {
        padding: 10px 18px;
        background-color: #aaa;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-cancel:hover {
        background-color: #888;
    }

    /* сетка карточек — мобильный: 1 колонка */
    .reviews-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    /* планшет: 2 колонки */
    @media (min-width: 768px) {
        .reviews-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* десктоп: 4 колонки */
    @media (min-width: 1024px) {
        .reviews-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* карточка отзыва */
    .review-card {
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
    }

    /* заголовок карточки: аватар + имя */
    .review-card .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .card-avatar-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #1a1a2e;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        flex-shrink: 0;
    }

    .card-avatar-img {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #1a1a2e;
        flex-shrink: 0;
    }

    .card-name {
        font-weight: bold;
        font-size: 15px;
        color: #1a1a2e;
    }

    /* золотые звёзды в карточке */
    .card-stars {
        color: #f5a623;
        font-size: 18px;
        margin-bottom: 8px;
    }

    /* текст отзыва */
    .card-text {
        font-size: 14px;
        color: #444;
        line-height: 1.6;
    }

    /* дата */
    .card-date {
        font-size: 12px;
        color: #999;
        margin-top: 8px;
    }

    /* фото внизу карточки */
    .card-photo {
        margin-top: 12px;
        width: 100%;
        max-height: 180px;
        object-fit: cover;
        border-radius: 8px;
    }

    .no-reviews-msg {
        color: #999;
        font-style: italic;
    }

    /* мобильный: шапка в столбик */
    @media (max-width: 600px) {
        .reviews-header-section {
            flex-direction: column;
            align-items: flex-start;
        }

        .star-radio-group label {
            font-size: 26px;
        }
    }
</style>

<main class="main-content">

    <!-- верхний блок: заголовок + статистика слева, кнопка справа -->
    <section class="reviews-header-section content-box">
        <div class="reviews-header-left">
            <h2>ОТЗЫВЫ НАШИХ ПОКУПАТЕЛЕЙ</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert-success"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert-error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="avg-line">
                <span>Средняя оценка:</span>
                <span class="avg-stars">
                    <?php echo $total > 0 ? buildStars(round($avgRating)) : '☆☆☆☆☆'; ?>
                </span>
                <span class="avg-number">
                    <?php echo $total > 0 ? $avgRating : '—'; ?>
                </span>
            </div>
            <div class="reviews-count">
                <?php echo $total > 0 ? 'Количество отзывов: ' . $total : 'Нет отзывов'; ?>
            </div>
        </div>
        <div class="reviews-header-right">
            <button class="btn-add-review" id="btn-show-form">&#9998; Написать отзыв</button>
        </div>
    </section>

    <!-- форма добавления отзыва (скрыта по умолчанию) -->
    <section class="review-form-box" id="review-form-box">
        <h3>Новый отзыв</h3>
        <form action="save_review.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="input-name">Ваше имя *</label>
                <input type="text" name="name" id="input-name" placeholder="Введите имя">
            </div>

            <div class="form-group">
                <label>Оценка *</label>
                <!-- звёзды как radio, порядок обратный для CSS-трюка -->
                <div class="star-radio-group">
                    <input type="radio" name="rating" id="star5" value="5">
                    <label for="star5">&#9733;</label>
                    <input type="radio" name="rating" id="star4" value="4">
                    <label for="star4">&#9733;</label>
                    <input type="radio" name="rating" id="star3" value="3">
                    <label for="star3">&#9733;</label>
                    <input type="radio" name="rating" id="star2" value="2">
                    <label for="star2">&#9733;</label>
                    <input type="radio" name="rating" id="star1" value="1">
                    <label for="star1">&#9733;</label>
                </div>
            </div>

            <div class="form-group">
                <label for="input-text">Текст отзыва *</label>
                <textarea name="text" id="input-text" placeholder="Напишите ваш отзыв..."></textarea>
            </div>

            <div class="form-group">
                <label for="input-image">Фото (необязательно)</label>
                <input type="file" name="image" id="input-image" accept="image/*">
            </div>

            <div>
                <button type="submit" class="btn-submit">Отправить</button>
                <button type="button" class="btn-cancel" id="btn-cancel">Отмена</button>
            </div>

        </form>
    </section>

    <!-- список карточек отзывов -->
    <section class="content-box">
        <div class="reviews-grid">
            <?php if ($total === 0): ?>
                <p class="no-reviews-msg">Пока нет отзывов. Будьте первым!</p>
            <?php else: ?>
                <!-- вывод отзывов из базы данных -->
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="card-header">
                            <?php
                            $hasPhoto = !empty($review['image'])
                                && file_exists(__DIR__ . '/images/' . $review['image']);
                            ?>
                            <?php if ($hasPhoto): ?>
                                <img class="card-avatar-img"
                                     src="images/<?php echo htmlspecialchars($review['image']); ?>"
                                     alt="фото">
                            <?php else: ?>
                                <div class="card-avatar-placeholder">
                                    <?php echo mb_strtoupper(mb_substr($review['username'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="card-name">
                                <?php echo htmlspecialchars($review['username']); ?>
                            </div>
                        </div>

                        <div class="card-stars">
                            <?php echo buildStars(intval($review['rating'])); ?>
                        </div>

                        <div class="card-text">
                            <?php echo htmlspecialchars($review['text']); ?>
                        </div>

                        <?php if ($hasPhoto): ?>
                            <img class="card-photo"
                                 src="images/<?php echo htmlspecialchars($review['image']); ?>"
                                 alt="фото отзыва">
                        <?php endif; ?>

                        <div class="card-date">
                            <?php echo htmlspecialchars($review['created_at']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</main>

<script>
    // показать форму
    document.getElementById('btn-show-form').addEventListener('click', function() {
        document.getElementById('review-form-box').style.display = 'block';
        this.style.display = 'none';
    });

    // скрыть форму
    document.getElementById('btn-cancel').addEventListener('click', function() {
        document.getElementById('review-form-box').style.display = 'none';
        document.getElementById('btn-show-form').style.display = 'inline-block';
    });
</script>

<?php require 'footer.php'; ?>
