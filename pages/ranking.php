<?php
// Leaderboard page for PeakPrepSPM
include '../includes/header.php';
include '../includes/db.php';

$monthlyQuery = "SELECT users.username, SUM(results.score) AS total_score FROM results
                 JOIN users ON results.user_id = users.id
                 WHERE MONTH(results.created_at) = MONTH(CURRENT_DATE())
                 GROUP BY users.id ORDER BY total_score DESC LIMIT 10";

$allTimeQuery = "SELECT users.username, SUM(results.score) AS total_score FROM results
                 JOIN users ON results.user_id = users.id
                 GROUP BY users.id ORDER BY total_score DESC LIMIT 10";

$monthlyResult = $conn->query($monthlyQuery);
$allTimeResult = $conn->query($allTimeQuery);
?>

<div class="container mt-5">
    <h2>Leaderboard</h2>
    <h3>Monthly Top 10</h3>
    <table class="table">
        <thead>
            <tr><th>Rank</th><th>Student</th><th>Total Score</th></tr>
        </thead>
        <tbody>
            <?php $rank = 1; while ($row = $monthlyResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $rank++; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['total_score']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3>All-Time Top 10</h3>
    <table class="table">
        <thead>
            <tr><th>Rank</th><th>Student</th><th>Total Score</th></tr>
        </thead>
        <tbody>
            <?php $rank = 1; while ($row = $allTimeResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $rank++; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['total_score']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
