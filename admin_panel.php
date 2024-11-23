<?php
session_start();

// Check if the user is logged in as an admin.  You'll need to implement admin authentication separately (e.g., a separate admin login page).
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: admin_login.php"); // Redirect to admin login page
    exit();
}

require_once 'functions.php';
$conn = connect_db();

$error = null;
$success = null;

//Fetch data for coach_athlete_assignments
$sqlCoachAssignments = "SELECT caa.id, c.first_name AS coach_first_name, c.last_name AS coach_last_name, a.first_name AS athlete_first_name, a.last_name AS athlete_last_name, caa.date, caa.time_interval FROM coach_athlete_assignments caa JOIN coaches c ON caa.coach_id = c.id JOIN athletes a ON caa.athlete_id = a.id";
$resultCoachAssignments = $conn->query($sqlCoachAssignments);
if (!$resultCoachAssignments) {
    $error = "Ошибка при получении данных о назначениях тренеров: " . $conn->error;
}

//Fetch data for athlete_availability
$sqlAthleteAvailability = "SELECT aa.id, a.first_name AS athlete_first_name, a.last_name AS athlete_last_name, aa.date, aa.time_interval FROM athlete_availability aa JOIN athletes a ON aa.athlete_id = a.id";
$resultAthleteAvailability = $conn->query($sqlAthleteAvailability);
if (!$resultAthleteAvailability) {
    $error = "Ошибка при получении данных о доступности спортсменов: " . $conn->error;
}

//Handle edits and deletions (add functions to functions.php)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['action']) && $_POST['action'] == 'delete_coach_assignment'){
        $id = (int)$_POST['assignment_id']; //Sanitize input!
        if(deleteCoachAssignment($conn, $id)){
            $success = "Назначение тренера успешно удалено!";
        } else {
            $error = "Ошибка при удалении назначения тренера: " . $conn->error;
        }
    }
    // Add similar handling for athlete_availability edits and deletions
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Админ панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <div class="container">
            <h1>Админ панель</h1>

            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success-message"><?php echo $success; ?></p>
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
                                        <input type="hidden" name="action" value="delete_coach_assignment">
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
                                   <!-- Add forms for edit and delete here -->
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Нет данных о доступности.</p>
            <?php endif; ?>

            <a href="index.php" class="return-button">Назад на главную</a>
        </div>
    </main>
</body>
</html>