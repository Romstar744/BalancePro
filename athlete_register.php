<?php
require_once 'functions.php';
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Пожалуйста, заполните все поля!";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO athletes (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $success = "Регистрация прошла успешно! <a href='athlete_login.php'>Войти</a>";
        } else {
            $error = "Ошибка регистрации: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация спортсмена</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация спортсмена</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password"><br>
            <input type="submit" value="Зарегистрироваться">
            <a href="coach_login.php">Уже зарегистрированы? Войти</a>
        </form>
    </div>
</body>
</html>