<?php
require_once 'functions.php';
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $patronymic = trim($_POST["patronymic"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $phone = trim($_POST["phone"]);

    if (empty($firstName) || empty($lastName) || empty($username) || empty($password) || empty($phone)) {
        $error = "Заполните все поля!";
    } else {
        $checkUsername = "SELECT * FROM coaches WHERE username = ?";
        $stmtCheck = $conn->prepare($checkUsername);
        $stmtCheck->bind_param("s", $username);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            $error = "Пользователь с таким именем уже существует!";
        } else {
            $checkPhone = "SELECT * FROM coaches WHERE phone = ?";
            $stmtPhone = $conn->prepare($checkPhone);
            $stmtPhone->bind_param("s", $phone);
            $stmtPhone->execute();
            $resultPhone = $stmtPhone->get_result();

            if ($resultPhone->num_rows > 0) {
                $error = "Пользователь с таким номером телефона уже существует!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO coaches (first_name, last_name, patronymic, username, password, phone) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    $error = "Ошибка при подготовке запроса: " . $conn->error;
                } else {
                    $stmt->bind_param("ssssss", $firstName, $lastName, $patronymic, $username, $hashedPassword, $phone);
                    if ($stmt->execute()) {
                        $success = "Регистрация прошла успешно! <a href='coach_login.php'>Войти</a>";
                    } else {
                        $error = "Ошибка регистрации: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
            $stmtPhone->close();
        }
        $stmtCheck->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Регистрация тренера</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация тренера</h1>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="first_name">Имя:</label>
            <input type="text" id="first_name" name="first_name" required><br>
            <label for="last_name">Фамилия:</label>
            <input type="text" id="last_name" name="last_name" required><br>
            <label for="patronymic">Отчество:</label>
            <input type="text" id="patronymic" name="patronymic" required><br>
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="phone">Телефон:</label>
            <input type="tel" id="phone" name="phone" required><br>
            <input type="submit" value="Зарегистрироваться">
        </form>
        <a href="index.php" class="return-button">Назад на главную страницу</a>
        <a href="coach_login.php" class="return-button">Уже зарегистрированы? Войти</a>
    </div>
</body>
</html>