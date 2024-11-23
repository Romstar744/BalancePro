<?php
require_once 'functions.php';
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Заполните все поля!";
    } else {
        $checkUsername = "SELECT * FROM admins WHERE username = ?";
        $stmtCheck = $conn->prepare($checkUsername);
        $stmtCheck->bind_param("s", $username);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            $error = "Пользователь с таким именем уже существует!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                $error = "Ошибка при подготовке запроса: " . $conn->error;
            } else {
                $stmt->bind_param("ss", $username, $hashedPassword);
                if ($stmt->execute()) {
                    $success = "Регистрация прошла успешно! <a href='admin_login.php'>Войти</a>";
                } else {
                    $error = "Ошибка регистрации: " . $stmt->error;
                }
                $stmt->close();
            }
        }
        $stmtCheck->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация администратора</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация администратора</h1>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Зарегистрироваться">
        </form>
        <a href="index.php" class="return-button">Назад на главную страницу</a>
        <a href="admin_login.php" class="return-button">Уже зарегистрированы? Войти</a>
    </div>
</body>
</html>