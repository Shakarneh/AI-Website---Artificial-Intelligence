<?php
session_start();
require 'db.php';

$pageTitle = "Применение искусственного интеллекта";

$name = '';
$email = '';
$age = '';
$comment = '';

$errors = array();
$successMessage = '';

// удаление сообщения (только админ)
if (isset($_GET['delete']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $deleteId = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM messages WHERE id = $deleteId");
    $_SESSION['message'] = 'Сообщение было удалено.';
    header('Location: applications.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $age     = trim($_POST['age'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Введите имя.';
    } elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z\s\-]{2,50}$/u', $name)) {
        $errors['name'] = 'Имя заполнено неверно.';
    }

    if ($email === '') {
        $errors['email'] = 'Введите email.';
    } elseif (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
        $errors['email'] = 'Некорректный email.';
    }

    if ($age === '') {
        $errors['age'] = 'Введите возраст.';
    } elseif (!preg_match('/^[0-9]{1,3}$/', $age)) {
        $errors['age'] = 'Возраст должен содержать только цифры.';
    }

    if ($comment === '') {
        $errors['comment'] = 'Введите комментарий.';
    } elseif (mb_strlen($comment) < 5) {
        $errors['comment'] = 'Комментарий слишком короткий.';
    }

    if (empty($errors)) {
        // сохраняем в MySQL
        $n = mysqli_real_escape_string($conn, $name);
        $e = mysqli_real_escape_string($conn, $email);
        $a = mysqli_real_escape_string($conn, $age);
        $c = mysqli_real_escape_string($conn, $comment);
        mysqli_query($conn, "INSERT INTO messages (name, email, age, comment) VALUES ('$n', '$e', '$a', '$c')");
        $_SESSION['message'] = 'Данные успешно отправлены и сохранены.';
        header('Location: applications.php');
        exit;
    }
}

if (isset($_SESSION['message'])) {
    $successMessage = $_SESSION['message'];
    unset($_SESSION['message']);
}

// читаем сообщения из MySQL
$result = mysqli_query($conn, "SELECT * FROM messages ORDER BY id DESC");
$messages = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
}

require 'header.php';
require 'menu.php';
?>

<main class="main-content">
    <section class="content-box">
        <h2>Применение искусственного интеллекта</h2>
        <p>Искусственный интеллект активно используется в разных сферах жизни и помогает автоматизировать сложные процессы.</p>
    </section>

    <section class="content-box">
        <h2>Основные области применения</h2>
        <ul>
            <li><span>Медицина:</span> диагностика заболеваний и анализ снимков.</li>
            <li><span>Образование:</span> интеллектуальные обучающие системы.</li>
            <li><span>Транспорт:</span> беспилотные автомобили и навигация.</li>
            <li><span>Бизнес:</span> анализ данных и автоматизация обслуживания клиентов.</li>
        </ul>
    </section>

    <section class="cards-container">
        <article class="card" onmouseover="enlargeCard(this)" onmouseout="resetCard(this)">
            <h3>Медицина</h3>
            <p>ИИ помогает врачам быстрее анализировать результаты обследований и выявлять заболевания.</p>
        </article>
        <article class="card" onmouseover="enlargeCard(this)" onmouseout="resetCard(this)">
            <h3>Транспорт</h3>
            <p>ИИ используется в системах навигации, распознавании объектов и управлении беспилотным транспортом.</p>
        </article>
        <article class="card" onmouseover="enlargeCard(this)" onmouseout="resetCard(this)">
            <h3>Образование</h3>
            <p>ИИ позволяет создавать персонализированные курсы и интеллектуальные системы проверки знаний.</p>
        </article>
    </section>

    <section class="content-box">
        <h2>Форма обратной связи</h2>

        <?php if ($successMessage !== ''): ?>
            <p class="success-message"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>

        <form class="form-box" method="post" action="applications.php">
            <input type="text" name="name" id="name" placeholder="Введите имя"
                value="<?php echo htmlspecialchars($name); ?>"
                onkeyup="countNameLength()"
                class="<?php echo isset($errors['name']) ? 'input-error' : ''; ?>">
            <?php if (isset($errors['name'])): ?>
                <p class="error-text"><?php echo htmlspecialchars($errors['name']); ?></p>
            <?php endif; ?>
            <p id="nameInfo"></p>

            <input type="text" name="email" id="email" placeholder="Введите email"
                value="<?php echo htmlspecialchars($email); ?>"
                onfocus="showHint()" onblur="hideHint()"
                class="<?php echo isset($errors['email']) ? 'input-error' : ''; ?>">
            <?php if (isset($errors['email'])): ?>
                <p class="error-text"><?php echo htmlspecialchars($errors['email']); ?></p>
            <?php endif; ?>
            <p id="emailHint"></p>

            <input type="text" name="age" id="age" placeholder="Введите возраст"
                value="<?php echo htmlspecialchars($age); ?>"
                class="<?php echo isset($errors['age']) ? 'input-error' : ''; ?>">
            <?php if (isset($errors['age'])): ?>
                <p class="error-text"><?php echo htmlspecialchars($errors['age']); ?></p>
            <?php endif; ?>

            <textarea name="comment" id="comment" rows="5" placeholder="Введите комментарий"
                class="<?php echo isset($errors['comment']) ? 'input-error' : ''; ?>"
            ><?php echo htmlspecialchars($comment); ?></textarea>
            <?php if (isset($errors['comment'])): ?>
                <p class="error-text"><?php echo htmlspecialchars($errors['comment']); ?></p>
            <?php endif; ?>

            <button type="submit">Отправить</button>
        </form>
    </section>

    <section class="content-box">
        <h2>Сохранённые сообщения</h2>

        <?php if (empty($messages)): ?>
            <p>Пока нет сохранённых сообщений.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Возраст</th>
                        <th>Комментарий</th>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <th>Удалить</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($msg['date']); ?></td>
                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo htmlspecialchars($msg['age']); ?></td>
                            <td><?php echo htmlspecialchars($msg['comment']); ?></td>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <td>
                                    <a href="applications.php?delete=<?php echo intval($msg['id']); ?>"
                                       class="delete-link"
                                       onclick="return confirm('Удалить сообщение?')">
                                        Удалить
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>

<?php require 'footer.php'; ?>
