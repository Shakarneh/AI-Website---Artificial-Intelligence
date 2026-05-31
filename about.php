<?php
session_start();
$pageTitle = "Размер файлов и папок";

function getFolderSize($dir)
{
    $size = 0;

    if (!is_dir($dir)) {
        return $size;
    }

    $items = scandir($dir);

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($fullPath)) {
            $size += getFolderSize($fullPath);
        } elseif (is_file($fullPath)) {
            $size += filesize($fullPath);
        }
    }

    return $size;
}

function formatSize($size)
{
    if ($size < 1024) {
        return $size . ' байт';
    }

    if ($size < 1024 * 1024) {
        return round($size / 1024, 2) . ' КБ';
    }

    return round($size / (1024 * 1024), 2) . ' МБ';
}

$inputPath = $_GET['path'] ?? '';
$resultMessage = '';
$errorMessage = '';

if ($inputPath !== '') {
    $root = realpath(__DIR__);
    $target = realpath(__DIR__ . DIRECTORY_SEPARATOR . $inputPath);

    if ($target === false) {
        $errorMessage = 'Указанный файл или папка не существует.';
    } elseif (strpos($target, $root) !== 0) {
        $errorMessage = 'Доступ разрешён только внутри папки сайта.';
    } else {
        if (is_file($target)) {
            $size = filesize($target);
            $resultMessage = 'Размер файла: ' . formatSize($size);
        } elseif (is_dir($target)) {
            $size = getFolderSize($target);
            $resultMessage = 'Размер папки: ' . formatSize($size);
        } else {
            $errorMessage = 'Не удалось определить тип объекта.';
        }
    }
}

require 'header.php';
require 'menu.php';
?>

<main class="main-content">
    <section class="content-box">
        <h2>Подсчёт размера файла или папки</h2>
        <p>
            Введите относительный путь внутри сайта.
            Например: <strong>images</strong> или <strong>style.css</strong>
        </p>

        <form class="form-box" method="get" action="about.php">
            <input type="text" name="path" placeholder="Введите путь" value="<?php echo htmlspecialchars($inputPath); ?>">
            <button type="submit">Показать размер</button>
        </form>

        <?php if ($resultMessage !== ''): ?>
            <p class="success-message"><?php echo htmlspecialchars($resultMessage); ?></p>
        <?php endif; ?>

        <?php if ($errorMessage !== ''): ?>
            <p class="error-text"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>
    </section>
</main>

<?php require 'footer.php'; ?>