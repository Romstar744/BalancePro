<?php
require_once 'functions.php';
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); //Hash password

    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "Регистрация прошла успешно!";
    } else {
        echo "Ошибка регистрации: " . $stmt->error; //Show error for debugging
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Регистрация администратора</title></head>
<body>
    <h1>Регистрация администратора</h1>
    <form method="post">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Зарегистрироваться">
    </form>
</body>
</html>