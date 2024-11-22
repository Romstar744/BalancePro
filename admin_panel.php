<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Админ панель</title></head>
<body>
    <h1>Добро пожаловать, администратор!</h1>
    <p><a href="logout.php">Выйти</a></p>  <!-- Add logout.php -->
</body>
</html>