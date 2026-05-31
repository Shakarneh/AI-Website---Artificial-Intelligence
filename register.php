<?php session_start();
require 'db.php';

$pageTitle = "Регистрация";
$error = "";

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($username == "" || $email == "" || $password == "") {
        $error = "Заполните все поля";
    } else {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) 
                  VALUES ('$username', '$email', '$password_hash')";

        mysqli_query($conn, $query);

        header("Location: login.php");
        exit();
    }
}
?>

<?php require 'header.php'; ?>
<?php require 'menu.php'; ?>

<main class="main-content">
    <section class="content-box form-page">
        <div class="form-card">
            <h2>Регистрация</h2>
            <p class="form-text">Создайте учетную запись для входа на сайт.</p>

            <?php if ($error != ""): ?>
                <p class="form-error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="post" class="site-form">
                <label for="username">Имя</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Введите имя"
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                >

                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Введите email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                >

                <label for="password">Пароль</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Введите пароль"
                >

                <button type="submit" name="register" class="form-button">Зарегистрироваться</button>
            </form>
        </div>
    </section>
</main>

<?php require 'footer.php'; ?>
