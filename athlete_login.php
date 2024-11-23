<?php
require_once 'functions.php';
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM athletes WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION["athlete_id"] = $row["id"];
            header("Location: athlete_panel.php"); 
            exit();
        } else {
            $error = "Неверный пароль.";
        }
    } else {
        $error = "Пользователь не найден.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход для спортсмена</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Вход для спортсмена</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username"><br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password"><br>
            <input type="submit" value="Войти">
        </form>
        <a href="index.php" class="return-button">Назад на главную страницу</a>
    </div>
</body>
</html>