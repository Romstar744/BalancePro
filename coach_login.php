<?php
session_start();
require_once 'functions.php';
$conn = connect_db();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Заполните все поля!";
    } else {
        $sql = "SELECT id, password FROM coaches WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                $_SESSION["coach_id"] = $row["id"];
                header("Location: coach_panel.php");
                exit();
            } else {
                $error = "Неверный пароль";
            }
        } else {
            $error = "Пользователь не найден";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход для тренера</title>
    <link rel="stylesheet" href="style-coach.css">
</head>
<body>
    <main>
        <div class="container">
            <h1>Вход для тренера</h1>
            <?php if (isset($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="post">
                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" name="username" required><br>
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required><br>
                <input type="submit" value="Войти">
            </form>
            <a href="index.php" class="return-button">Назад на главную страницу</a>
        </div>
    </main>
</body>
</html>