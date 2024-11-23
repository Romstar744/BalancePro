<?php
session_start();
if (!isset($_SESSION["coach_id"])) {
    header("Location: coach_login.php");
    exit();
}

require_once 'functions.php';
$conn = connect_db();

$coachId = $_SESSION["coach_id"];
$error = null;
$success = null;

$coachInfo = getCoachInfo($coachId, $conn);
if (!$coachInfo) {
    $error = "Ошибка получения информации о тренере.";
}

$resultAthleteAvailability = getAthleteAvailability($coachId, $conn);
if ($resultAthleteAvailability === false) {
    $error = "Ошибка получения информации о доступных спортсменах.";
}

$resultAssignedAthletes = getAssignedAthletes($coachId, $conn);
if ($resultAssignedAthletes === false) {
    $error = "Ошибка получения информации о назначенных спортсменах.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["athlete_id"]) && isset($_POST["date"])) {
    $athleteId = (int)$_POST["athlete_id"];
    $date = $_POST["date"];
    if (assignAthlete($conn, $coachId, $athleteId, $date)) {
        $success = "Спортсмен успешно назначен!";
    } else {
        $error = "Ошибка при назначении спортсмена: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Панель тренера</title>
    <link rel="stylesheet" href="style-coach.css">
</head>
<body>
        <div class="background"></div>
            <div class="content">
                <div class="container">
                    <form class="form2" method="post">
                            <h1>Панель тренера</h1>

                            <?php if ($error): ?>
                                <p class="error-message"><?php echo $error; ?></p>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <p class="success-message"><?php echo $success; ?></p>
                            <?php endif; ?>

                            <h2>Информация о тренере</h2>
                                <div class="text222">
                                    <p>Имя: <?php echo $coachInfo['first_name']; ?></p>
                                    <p>Фамилия: <?php echo $coachInfo['last_name']; ?></p>
                                    <p>Отчество: <?php echo $coachInfo['patronymic']; ?></p>
                                    <p>Телефон: <?php echo $coachInfo['phone']; ?></p>
                                </div>
                    </form>
                    <form class="form1" method="post">
                            <h2>Доступные спортсмены</h2>
                            <?php if ($resultAthleteAvailability && $resultAthleteAvailability->num_rows > 0): ?>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Спортсмен</th>
                                                <th>Дата</th>
                                                <th>Время</th>
                                                <th>Назначить</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $resultAthleteAvailability->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                    <td><?php echo $row['date']; ?></td>
                                                    <td><?php echo $row['time_interval']; ?></td>
                                                    <td>
                                                        <input type="radio" name="athlete_id" value="<?php echo $row['athlete_id']; ?>">
                                                        <input type="hidden" name="date" value="<?php echo $row['date']; ?>">
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                    <input type="submit" value="Назначить">
                    <?php else: ?>
                        <p>Нет доступных спортсменов.</p>
                    <?php endif; ?>

                    <h2>Назначенные спортсмены</h2>
                    <?php if ($resultAssignedAthletes && $resultAssignedAthletes->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Спортсмен</th>
                                    <th>Дата</th>
                                    <th>Время</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultAssignedAthletes->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['time_interval']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Нет назначенных спортсменов.</p>
                    <?php endif; ?>
                </form>

                    <a href="index.php" class="return-button">Назад на главную страницу</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>