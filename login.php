<?php session_start();
require 'db.php';

$pageTitle = "Вход";
$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {
        $error = "Заполните все поля";
    } else {

        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Неверный email или пароль";
        }
    }
}
?>

<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

<main class="main-content">
    <section class="content-box form-page">
        <div class="form-card">
            <h2>Вход</h2>
            <p class="form-text">Введите данные для входа на сайт.</p>

            <?php if ($error != ""): ?>
                <p class="form-error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" class="site-form">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Введите email"
                >

                <label for="password">Пароль</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Введите пароль"
                >

                <button type="submit" name="login" class="form-button">Войти</button>
            </form>
        </div>
    </section>
</main>

<?php require 'footer.php'; ?>
