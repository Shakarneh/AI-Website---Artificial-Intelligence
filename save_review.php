<?php
session_start();
require 'db.php';

// принимаем только POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reviews.php');
    exit;
}

// читаем поля
$name   = trim($_POST['name'] ?? '');
$rating = intval($_POST['rating'] ?? 0);
$text   = trim($_POST['text'] ?? '');

// валидация
$errors = [];

if ($name === '') {
    $errors[] = 'Введите имя';
}
if ($rating < 1 || $rating > 5) {
    $errors[] = 'Выберите оценку от 1 до 5';
}
if ($text === '') {
    $errors[] = 'Напишите текст отзыва';
}

if (!empty($errors)) {
    $_SESSION['error'] = implode('; ', $errors);
    header('Location: reviews.php');
    exit;
}

// обработка загрузки фото
$imageName = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpPath  = $_FILES['image']['tmp_name'];
    $origName = basename($_FILES['image']['name']);

    // проверка типа файла
    $allowed  = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $mimeType = mime_content_type($tmpPath);

    if (in_array($mimeType, $allowed)) {
        $ext       = pathinfo($origName, PATHINFO_EXTENSION);
        $imageName = 'review_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $destPath  = __DIR__ . '/images/' . $imageName;

        if (!move_uploaded_file($tmpPath, $destPath)) {
            $imageName = '';
        }
    }
}

// вставка в базу данных
$name   = mysqli_real_escape_string($conn, $name);
$text   = mysqli_real_escape_string($conn, $text);
$image  = mysqli_real_escape_string($conn, $imageName);
$query = "INSERT INTO reviews (username, rating, text, image)
          VALUES ('$name', $rating, '$text', '$image')";

$ok = mysqli_query($conn, $query);

// перенаправление
if ($ok) {
    $_SESSION['message'] = 'Ваш отзыв успешно добавлен!';
} else {
    $_SESSION['error'] = 'Ошибка базы данных: ' . mysqli_error($conn);
}
header('Location: reviews.php');
exit;
