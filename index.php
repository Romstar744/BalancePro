<?php
require_once 'functions.php';
$conn = connect_db();
$sql = "SELECT * FROM matches";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Черные Медведи - Политех</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="uploads/logo.png" alt="Логотип" class="logo">
        <img src="uploads/site_title.png" alt="Название сайта" class="site-title">
    </header>

    <nav>  <!-- Navigation menu -->
        <a href="admin_login.php" class="login-button">Вход для администратора</a>
        <a href="athlete_login.php" class="login-button">Вход для спортсмена</a>
        <a href="coach_login.php" class="login-button">Вход для тренера</a>
    </nav>

    <main>
        <h1>Расписание матчей</h1>
        <table>
            <thead>
                <tr>
                    <th>Турнир</th>
                    <th>Дата</th>
                    <th>Время</th>
                    <th>Команда</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["tournament"] . "</td>";
                        echo "<td>" . $row["date"] . "</td>";
                        echo "<td>" . $row["time"] . "</td>";
                        echo "<td>" . $row["team"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Данных нет</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <?php $conn->close(); ?>
</body>
</html>