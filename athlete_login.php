<?php
require_once 'functions.php';
$conn = connect_db();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);

    $sql = "SELECT id, username, password FROM athletes WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $athlete = $result->fetch_assoc();
            if (password_verify($password, $athlete["password"])) {
                session_start();
                $_SESSION["athlete_id"] = $athlete["id"];
                $_SESSION["athlete_logged_in"] = true;
                header("Location: athlete_panel.php"); 
                exit();
            } else {
                $error = "Неверный пароль.";
            }
        } else {
            $error = "Пользователь не найден.";
        }
        $stmt->close();
    } else {
        $error = "Ошибка при подготовке запроса: " . $conn->error;
    }
}
$conn->close();

function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход для спортсмена</title>
    <link rel="stylesheet" href="style-athlete_login_reg.css">
</head>
<body>
    <div class="background"></div>
        <div class="content">
            <div class="container">
                <h1>Вход для спортсмена</h1>
                <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
                <form class="post1" method="post">
                    <label for="username">Имя пользователя:</label><br>
                    <input type="text" id="username" name="username" required><br>
                    <label for="password">Пароль:</label><br>
                    <input type="password" id="password" name="password" required><br>
                    <input type="submit" value="Войти"><br>
                </form>
            </div>
            <a href="index.php">На главную</a>
            <a href="athlete_register.php">Не зарегистрированы? Регистрация</a>
        </div>
    </div>
</body>
</html>