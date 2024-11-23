<?php
session_start();

if (!isset($_SESSION["admin_id"]) || !$_SESSION["admin_logged_in"]) {
    header("Location: admin_login.php");
    exit();
}

require_once 'functions.php';

$conn = connect_db();

$error = null;
$success = null;

function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

$sqlCoachAssignments = "SELECT caa.id, c.first_name AS coach_first_name, c.last_name AS coach_last_name, a.first_name AS athlete_first_name, a.last_name AS athlete_last_name, caa.date, caa.time_interval FROM coach_athlete_assignments caa JOIN coaches c ON caa.coach_id = c.id JOIN athletes a ON caa.athlete_id = a.id";
$stmtCoachAssignments = $conn->prepare($sqlCoachAssignments);

if ($stmtCoachAssignments) {
    $stmtCoachAssignments->execute();
    $resultCoachAssignments = $stmtCoachAssignments->get_result();
    $stmtCoachAssignments->close();
} else {
    $error = "Ошибка при подготовке запроса о назначениях тренеров: " . $conn->error;
}

$sqlAthleteAvailability = "SELECT aa.id, a.first_name AS athlete_first_name, a.last_name AS athlete_last_name, aa.date, aa.time_interval FROM athlete_availability aa JOIN athletes a ON aa.athlete_id = a.id";
$stmtAthleteAvailability = $conn->prepare($sqlAthleteAvailability);

if ($stmtAthleteAvailability) {
    $stmtAthleteAvailability->execute();
    $resultAthleteAvailability = $stmtAthleteAvailability->get_result();
    $stmtAthleteAvailability->close();
} else {
    $error = "Ошибка при подготовке запроса о доступности спортсменов: " . $conn->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_coach_assignment"])) {
    $assignmentId = sanitizeInput($_POST["assignment_id"]);
    if (deleteCoachAssignment($conn, $assignmentId)) {
        $success = "Назначение тренера успешно удалено!";
    } else {
        $error = "Ошибка при удалении назначения тренера: " . $conn->error;
    }
    header("Location: admin_panel.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_athlete_availability"])) {
    $availabilityId = sanitizeInput($_POST["availability_id"]);
    $date = sanitizeInput($_POST["date"]);
    $timeInterval = sanitizeInput($_POST["time_interval"]);

    if (updateAthleteAvailability($conn, $availabilityId, $date, $timeInterval)) {
        $success = "Запись о доступности успешно обновлена!";
    } else {
        $error = "Ошибка при обновлении записи о доступности: " . $conn->error;
    }
    header("Location: admin_panel.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_athlete_availability"])) {
    $availabilityId = sanitizeInput($_POST["availability_id"]);
    if (deleteAthleteAvailability($conn, $availabilityId)) {
        $success = "Запись о доступности успешно удалена!";
    } else {
        $error = "Ошибка при удалении записи о доступности: " . $conn->error;
    }
    header("Location: admin_panel.php");
    exit();
}


$conn->close(); 

?>

<!DOCTYPE html>
<html>
<head>
    <title>Админ панель</title>
    <link rel="stylesheet" href="style-admin.css">
</head>
<body>
    <main>
        <div class="container">
            <h1>Админ панель</h1>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>

            <h2>Назначения тренеров</h2>
            <?php if ($resultCoachAssignments && $resultCoachAssignments->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Тренер</th>
                            <th>Спортсмен</th>
                            <th>Дата</th>
                            <th>Время</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultCoachAssignments->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['coach_first_name'] . ' ' . $row['coach_last_name']; ?></td>
                                <td><?php echo $row['athlete_first_name'] . ' ' . $row['athlete_last_name']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['time_interval']; ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="assignment_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="delete_coach_assignment" value="true">
                                        <input type="submit" value="Удалить">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Нет назначений.</p>
            <?php endif; ?>

            <h2>Доступность спортсменов</h2>
            <?php if ($resultAthleteAvailability && $resultAthleteAvailability->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Спортсмен</th>
                            <th>Дата</th>
                            <th>Время</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultAthleteAvailability->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['athlete_first_name'] . ' ' . $row['athlete_last_name']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['time_interval']; ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="availability_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="delete_athlete_availability" value="true">
                                        <input type="submit" value="Удалить">
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="availability_id" value="<?php echo $row['id']; ?>">
                                        <input type="date" name="date" value="<?php echo $row['date']; ?>">
                                        <input type="text" name="time_interval" value="<?php echo $row['time_interval']; ?>">
                                        <input type="hidden" name="update_athlete_availability" value="true">
                                        <input type="submit" value="Изменить">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Нет данных о доступности.</p>
            <?php endif; ?>
            <a href="index.php">На главную</a>
        </div>
    </main>
</body>
</html>