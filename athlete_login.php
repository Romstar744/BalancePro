<?php
require_once 'functions.php'; // Include your database connection and helper functions
$conn = connect_db();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitizeInput($_POST["username"]);
    $password = sanitizeInput($_POST["password"]);

    //1. Check if the username exists:
    $sql = "SELECT id, username, password FROM athletes WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            //2. Username found - check the password
            $athlete = $result->fetch_assoc();
            if (password_verify($password, $athlete["password"])) {
                //3. Password matches - Start the session
                session_start();
                $_SESSION["athlete_id"] = $athlete["id"];
                $_SESSION["athlete_logged_in"] = true;
                header("Location: athlete_panel.php"); // Redirect to athlete panel
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


//Sanitize user input function (prevent SQL injection)
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
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }
        .error {
            color: red;
        }
        .container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Вход для спортсмена</h1>
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Войти">
        </form>
        <a href="index.php">На главную</a>
    </div>
</body>
</html>