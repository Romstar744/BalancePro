<?php
function connect_db(){
  $servername = "localhost"; // ваш сервер MySQL
  $username = "starostin_Bears"; // ваш пользователь MySQL
  $password = "Admin123*"; // ваш пароль MySQL
  $dbname = "starostin_Bears"; // имя вашей базы данных

  // Создаем подключение
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Проверка подключения
  if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
  }
  return $conn;
}
?>