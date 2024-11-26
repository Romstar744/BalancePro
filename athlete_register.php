<?php
require_once 'functions.php';
$conn = connect_db();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $patronymic = trim($_POST["patronymic"]);
    $birthdate = $_POST["birthdate"];


    if (empty($username) || empty($password) || empty($firstName) || empty($lastName) || empty($birthdate)) {
        $error = "Заполните все обязательные поля.";
    }
    elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate) || !strtotime($birthdate)) {
        $error = "Неверный формат даты рождения (должен быть YYYY-MM-DD).";
    }

    else {
        $sqlCheck = "SELECT id FROM athletes WHERE username = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $username);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($resultCheck->num_rows > 0) {
            $error = "Имя пользователя уже занято.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sqlInsert = "INSERT INTO athletes (username, password, first_name, last_name, patronymic, birthdate) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);

            if ($stmtInsert === false) {
                $error = "Ошибка при подготовке запроса: " . $conn->error;
            } else {
                $stmtInsert->bind_param("ssssss", $username, $hashedPassword, $firstName, $lastName, $patronymic, $birthdate);

                if ($stmtInsert->execute()) {
                    $success = "Регистрация успешна! <a href='athlete_login.php'>Войти</a>";
                } else {
                    $error = "Ошибка при регистрации: " . $stmtInsert->error;
                }
                $stmtInsert->close();
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
    <title>Регистрация спортсмена</title>
    <link rel="stylesheet" href="style-athlete_login_reg.css">
</head>
<d>
    <div class="background"></div>
        <div class="content">
            <div class="container">
                <h1>Регистрация спортсмена</h1>
                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <?php if ($success): ?>
                    <p class="success"><?php echo $success; ?></p>
                <?php endif; ?>
                <form method="post">
                    <label for="username">Имя пользователя:</label><br>
                    <input type="text" id="username" name="username" required><br>

                    <label for="password">Пароль:</label><br>
                    <input type="password" id="password" name="password" required><br>

                    <label for="first_name">Имя:</label><br>
                    <input type="text" id="first_name" name="first_name" required><br>

                    <label for="last_name">Фамилия:</label><br>
                    <input type="text" id="last_name" name="last_name" required><br>

                    <label for="patronymic">Отчество:</label><br>
                    <input type="text" id="patronymic" name="patronymic"><br>

                    <label for="birthdate">Дата рождения (YYYY-MM-DD):</label><br>
                    <input type="date" id="birthdate" name="birthdate" required><br>

                    <input type="submit" value="Зарегистрироваться">
                </form>
            </div>
            <a href="index.php">На главную</a>
            <a href="athlete_login.php" class="return-button">Уже зарегистрированы? Войти</a>
        </div>
    </div>
</body>
</html>