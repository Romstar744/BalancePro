<?php
require_once 'functions.php';
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // *** IMPORTANT: Verify password using password_verify() ***
        // if (password_verify($password, $row["password"])) {
        //     // Successful login - redirect to admin panel
        //     header("Location: admin_panel.php");
        //     exit();
        // } else {
        //     echo "Неверный пароль.";
        // }
    } else {
        echo "Пользователь не найден.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход для администратора</title>
</head>
<body>
    <h1>Вход для администратора</h1>
    <form method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Войти">
    </form>
</body>
</html>