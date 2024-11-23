<?php
require_once 'functions.php';
$conn = connect_db();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);

    $sql = "SELECT id, username, password FROM admins WHERE username = ?"; // Select only necessary columns
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                session_start();
                $_SESSION["admin_id"] = $row["id"]; // Correct session variable name
                $_SESSION["admin_logged_in"] = true; //Set admin logged in flag
                header("Location: admin_panel.php");
                exit();
            } else {
                $error = "Неверный пароль.";
            }
        } else {
            $error = "Пользователь не найден.";
        }
        $stmt->close(); // Close the prepared statement
    } else {
        $error = "Ошибка при подготовке запроса: " . $conn->error;
    }
}
$conn->close();

// Sanitize user input function (prevent SQL injection)
function sanitizeInput($data) {
    //This function should be in your functions.php file.
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
    <title>Вход для админа</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="container">
        <h1>Вход для админа</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
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
</body>
</html>