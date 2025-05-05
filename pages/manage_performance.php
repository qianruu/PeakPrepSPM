<?php
include '../config.php';
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ensure only admin can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

// Fetch student quiz performance data
$sql = "SELECT users.username, results.quiz_id, results.score, results.attempt_date, quizzes.subject 
        FROM results 
        JOIN users ON results.user_id = users.id 
        JOIN quizzes ON results.quiz_id = quizzes.id 
        ORDER BY results.attempt_date DESC";
$performance = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center">User Performance Analytics</h2>

    <table class="table table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th>Username</th>
                <th>Subject</th>
                <th>Score</th>
                <th>Date Attempted</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $performance->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo $row['score']; ?></td>
                <td><?php echo $row['attempt_date']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<h2 class="text-center mt-5">Leaderboard</h2>

<table class="table table-bordered mt-3">
    <thead class="table-dark">
        <tr>
            <th>Rank</th>
            <th>Username</th>
            <th>Total Score</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $leaderboardQuery = "SELECT users.username, SUM(results.score) AS total_score 
                             FROM results 
                             JOIN users ON results.user_id = users.id 
                             GROUP BY users.id 
                             ORDER BY total_score DESC 
                             LIMIT 10";
        $leaderboard = $conn->query($leaderboardQuery);
        $rank = 1;

        while ($row = $leaderboard->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $rank++; ?></td>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo $row['total_score']; ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Username', 'Total Score'],
            <?php
            $chartQuery = "SELECT users.username, SUM(results.score) AS total_score 
                           FROM results 
                           JOIN users ON results.user_id = users.id 
                           GROUP BY users.id 
                           ORDER BY total_score DESC";
            $chartResult = $conn->query($chartQuery);
            while ($row = $chartResult->fetch_assoc()) {
                echo "['" . $row['username'] . "', " . $row['total_score'] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Top Student Performance',
            hAxis: {title: 'Users'},
            vAxis: {title: 'Total Score'},
            bars: 'vertical'
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('performance_chart'));
        chart.draw(data, options);
    }
</script>

<div class="container mt-5">
    <h2 class="text-center">Performance Analysis</h2>
    <div id="performance_chart" style="width: 100%; height: 400px;"></div>
</div>

<?php include '../includes/footer.php'; ?>
