<?php
require_once 'functions.php';
$conn = connect_db();
// Pagination logic
$matchesPerPage = 6;
$currentPage = isset($_GET["page"]) && is_numeric($_GET["page"]) ? (int)$_GET["page"] : 1;
$currentPage = max(1, $currentPage); // Ensure currentPage is at least 1

// Get total number of matches
$totalMatchesSql = "SELECT COUNT(*) AS total FROM matches";
$totalMatchesResult = $conn->query($totalMatchesSql);
if (!$totalMatchesResult) {
    die("Ошибка при подсчете матчей: " . $conn->error);
}
$totalMatchesRow = $totalMatchesResult->fetch_assoc();
$totalMatches = $totalMatchesRow['total'];
$totalPages = ceil($totalMatches / $matchesPerPage);

// Handle invalid page numbers
if ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages; // Redirect to last page if current page exceeds total pages
}

// Paginated query
$offset = ($currentPage - 1) * $matchesPerPage;
$sqlPaginated = "SELECT * FROM matches LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sqlPaginated);
if (!$stmt){
    die("Ошибка при подготовке запроса: " . $conn->error);
}
$matchesPerPageInt = (int)$matchesPerPage;
$offsetInt = (int)$offset;
$stmt->bind_param("ii", $matchesPerPageInt, $offsetInt);
$stmt->execute();
$resultPaginated = $stmt->get_result();
if (!$resultPaginated) {
    die("Ошибка при получении данных о матчах: " . $conn->error);
}

// Fetch first and last match dates for the current page
$firstMatchDate = '';
$lastMatchDate = '';
if ($resultPaginated->num_rows > 0) {
    $resultPaginated->data_seek(0); // Rewind to the beginning
    $firstMatchRow = $resultPaginated->fetch_assoc();
    $firstMatchDate = $firstMatchRow['date'];

    $resultPaginated->data_seek($resultPaginated->num_rows - 1); // Go to the last row
    $lastMatchRow = $resultPaginated->fetch_assoc();
    $lastMatchDate = $lastMatchRow['date'];

    $resultPaginated->data_seek(0); // Rewind back to the beginning for the loop
}


// Session handling
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isRegistered = isset($_SESSION['user_id']);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Balance Pro - Политех</title>
    <link rel="stylesheet" href="style-index.css">
    
</head>
<body>
    <div class="background"></div>
        <div class="content">
            <div>
                <header>
                    <img src="uploads/logo.svg" alt="Логотип" class="logo">
                    <img src="uploads/site_title.svg" alt="Название сайта" class="site-title">
                </header>
            </div>
            <div class="container1">
                <div class="titlereg">Регистрация/Войти в аккаунт</div>
                    <ul class="list">
                        <?php
                            // Simulate checking for registration (replace with actual session check)
                            $isRegistered = false; // Replace with a database check using sessions

                            if ($isRegistered) {
                                echo '<a href="admin_login.php" class="login-button">Администратор</a>';
                                echo '<a href="athlete_login.php" class="login-button">Спортсмен</a>';
                                echo '<a href="coach_login.php" class="login-button">Тренер</a>';
                            } else {
                                echo '<a href="admin_register.php" class="login-button">Администратор</a>';
                                echo '<a href="athlete_register.php" class="login-button">Спортсмен</a>';
                                echo '<a href="coach_register.php" class="login-button">Тренер</a>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="container2">
                <div class="titlebalance">BalancePro</div>
                <div class="textalgoritm">Это инновационный алгоритм, который помогает спортивным командам находить оптимальные дни для проведения матчей. Мы учитываем график обучения студентов, чтобы обеспечить баланс между учебной нагрузкой и спортивными мероприятиями.</div>
            </div>

            <div class="container3">
                <div class="titlecluch">Наши ключевые преимущества:</div>
                <ul class="list">
                    <li>Интеллектуальный анализ данных: алгоритм учитывает расписания занятий, экзамены, и другие ключевые события в учебной жизни.</li>
                    <li>Повышение эффективности: мы подбираем дни, когда студенты наиболее свободны, чтобы максимально увеличить вовлечённость и успешность команды.</li>
                    <li>Гибкость настройки: система адаптируется под уникальные графики и потребности различных учебных учреждений и команд.</li>
                </ul>
            </div>

            <div class="tablica">
                <h1>Расписание матчей</h1>
                    <div class="pagination">
                        <button class="pagination-button prev" <?php if ($currentPage <= 1) echo 'disabled'; ?>>
                            <i class="fas fa-chevron-left"></i> Назад
                        </button>
                    <span class="pagination-date">
                    <?php if (!empty($firstMatchDate)): ?>
                        <?php echo date("d.m.Y", strtotime($firstMatchDate)); ?> - <?php echo date("d.m.Y", strtotime($lastMatchDate)); ?>
                    <?php endif; ?>
                </span>
                <button class="pagination-button next" <?php if ($currentPage >= $totalPages) echo 'disabled'; ?>>
                    Вперед <i class="fas fa-chevron-right"></i>
                </button>
            </div>
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
                    if ($resultPaginated->num_rows > 0) {
                        while ($row = $resultPaginated->fetch_assoc()) {
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
        </div>
        </div>
    </div>
    <script>
        const prevButton = document.querySelector('.prev');
        const nextButton = document.querySelector('.next');
        const currentPage = <?php echo $currentPage; ?>;
        const totalPages = <?php echo $totalPages; ?>;

        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                window.location.href = '?page=' + (currentPage - 1);
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                window.location.href = '?page=' + (currentPage + 1);
            }
        });
    </script>
    <?php $conn->close(); ?>
</body>
</html>