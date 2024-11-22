<?php
session_start();
if (!isset($_SESSION["athlete_id"])) {
    header("Location: athlete_login.php");
    exit();
}

require_once 'functions.php';
$conn = connect_db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $athlete_id = $_SESSION["athlete_id"];
    $date = $_POST["date"];
    $time_interval = $_POST["time_interval"];

    if (empty($date) || empty($time_interval)) {
        $error = "Заполните все поля!";
    } else {
        $sql = "INSERT INTO athlete_availability (athlete_id, date, time_interval) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $athlete_id, $date, $time_interval);

        if ($stmt->execute()) {
            $success = "Информация сохранена!";
        } else {
            $error = "Ошибка сохранения: " . $stmt->error;
        }
        $stmt->close();
    }
}
$conn->close();

?>

<!DOCTYPE html>
<html>
<head>
<title>Панель спортсмена</title>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
</script>
<link rel="stylesheet" href="style.css">
<style>
  .container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  label {
    margin-top: 10px;
  }
</style>
</head>
<body>
    <div class="container">
        <h1>Панель спортсмена</h1>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="datepicker">Дата:</label>
            <input type="text" id="datepicker" name="date" required>
            <label for="time_interval">Время (в формате ЧЧ:ММ-ЧЧ:ММ):</label>
            <input type="text" id="time_interval" name="time_interval" required>
            <input type="submit" value="Сохранить">
            <button type="button" onclick="clearForm()">Очистить</button>
        </form>

        <script>
          function clearForm() {
            document.getElementById("datepicker").value = "";
            document.getElementById("time_interval").value = "";
          }
        </script>
    </div>
</body>
</html>