<?php
session_start();
if (!isset($_SESSION["athlete_id"])) {
    header("Location: athlete_login.php");
    exit();
}

require_once 'functions.php';
$conn = connect_db();

$athleteId = $_SESSION["athlete_id"];

$sqlAthleteInfo = "SELECT first_name, last_name, patronymic, birthdate, DATE_FORMAT(birthdate, '%d.%m.%Y') AS formatted_birthdate FROM athletes WHERE id = ?";
$stmtAthleteInfo = $conn->prepare($sqlAthleteInfo);
if ($stmtAthleteInfo === false) {
    $error = "Ошибка при подготовке запроса: " . $conn->error;
} else {
    $stmtAthleteInfo->bind_param("i", $athleteId);
    $stmtAthleteInfo->execute();
    $resultAthlete = $stmtAthleteInfo->get_result();
    if ($resultAthlete === false) {
        $error = "Ошибка при выполнении запроса: " . $stmtAthleteInfo->error;
    } else {
        $athleteInfo = $resultAthlete->fetch_assoc();
    }
}

$sqlAvailability = "SELECT date, time_interval FROM athlete_availability WHERE athlete_id = ?";
$stmtAvailability = $conn->prepare($sqlAvailability);
if ($stmtAvailability === false) {
    $error = "Ошибка при подготовке запроса: " . $conn->error;
} else {
    $stmtAvailability->bind_param("i", $athleteId);
    $stmtAvailability->execute();
    $resultAvailability = $stmtAvailability->get_result();
    if ($resultAvailability === false) {
        $error = "Ошибка при выполнении запроса: " . $stmtAvailability->error;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $time_interval = $_POST["time_interval"];

    if (empty($date) || empty($time_interval)) {
        $error = "Заполните все поля!";
    } else {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $error = "Неверный формат даты. Используйте YYYY-MM-DD.";
        } elseif (!preg_match('/^\d{2}:\d{2}-\d{2}:\d{2}$/', $time_interval)) {
            $error = "Неверный формат времени. Используйте ЧЧ:MM-ЧЧ:MM.";
        } else {
            $sqlInsert = "INSERT INTO athlete_availability (athlete_id, date, time_interval) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            if ($stmtInsert === false){
                $error = "Ошибка при подготовке запроса: " . $conn->error;
            } else {
                $stmtInsert->bind_param("iss", $athleteId, $date, $time_interval);
                if ($stmtInsert->execute()) {
                    $success = "Запись добавлена!";
                    $stmtAvailability->execute();
                    $resultAvailability = $stmtAvailability->get_result();
                } else {
                    $error = "Ошибка добавления: " . $stmtInsert->error;
                }
                $stmtInsert->close();
            }
        }
    }
}


$stmtAthleteInfo->close();
$stmtAvailability->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Панель спортсмена</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
        });

        function clearForm() {
            $("#datepicker").val("");
            $("#time_interval").val("");
        }
    </script>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .info-section {
            width: 80%;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .info-item span {
            font-weight: bold;
            display: block;
        }
        .availability-list {
            list-style: none;
            padding: 0;
            width: 80%;
        }
        .availability-item {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .availability-item span {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .error-message{
            color:red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Панель спортсмена</h1>

        <div class="info-section">
            <h2>Информация о пользователе</h2>
            <?php if (isset($athleteInfo)): ?>
                <div class="info-item"><span>Имя:</span> <?php echo $athleteInfo['first_name']; ?></div>
                <div class="info-item"><span>Фамилия:</span> <?php echo $athleteInfo['last_name']; ?></div>
                <div class="info-item"><span>Отчество:</span> <?php echo $athleteInfo['patronymic']; ?></div>
                <div class="info-item"><span>Дата рождения:</span> <?php echo $athleteInfo['formatted_birthdate']; ?></div>
            <?php else: ?>
                <p class="error-message">Ошибка при загрузке информации о пользователе.</p>
            <?php endif; ?>
        </div>

        <?php if (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="datepicker">Дата:</label>
            <input type="text" id="datepicker" name="date" required>
            <label for="time_interval">Время (в формате ЧЧ:ММ-ЧЧ:ММ):</label>
            <input type="text" id="time_interval" name="time_interval" required>
            <input type="submit" value="Сохранить">
            <button type="button" onclick="clearForm()">Очистить</button>
        </form>

        <ul class="availability-list">
            <?php
            if (isset($resultAvailability) && $resultAvailability->num_rows > 0) {
                while ($row = $resultAvailability->fetch_assoc()) {
                    echo '<li class="availability-item">';
                    echo '<span>Дата: ' . $row["date"] . '</span>';
                    echo '<span>Время: ' . $row["time_interval"] . '</span>';
                    echo '</li>';
                }
            } else {
                if (!isset($error)) {
                    echo '<li class="availability-item"><p>Занятостей нет.</p></li>';
                }
            }
            ?>
        </ul>

    </div>
</body>
</html>