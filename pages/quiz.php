<?php
// Quiz page for PeakPrepSPM
include '../includes/header.php';
include '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

$quizQuery = "SELECT * FROM quizzes ORDER BY RAND() LIMIT 10";
$quizResult = $conn->query($quizQuery);
?>

<div class="container mt-5">
    <h2>SPM Quiz</h2>
    <p id="timer" class="text-danger">Time Left: 10:00</p>
    <form method="POST" action="<?php echo BASE_URL; ?>pages/submit_quiz.php" id="quizForm">
        <?php while ($row = $quizResult->fetch_assoc()): ?>
            <div class="mb-4">
                <p><strong><?php echo $row['question']; ?></strong></p>
                <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="A"> <?php echo $row['option_a']; ?><br>
                <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="B"> <?php echo $row['option_b']; ?><br>
                <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="C"> <?php echo $row['option_c']; ?><br>
                <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="D"> <?php echo $row['option_d']; ?><br>
            </div>
        <?php endwhile; ?>
        <button type="submit" class="btn btn-primary">Submit Quiz</button>
    </form>
</div>

<script>
    let timeLeft = 600; // 10 minutes in seconds
    function updateTimer() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        document.getElementById('timer').innerHTML = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        if (timeLeft <= 0) {
            document.getElementById('quizForm').submit();
        }
        timeLeft--;
    }
    setInterval(updateTimer, 1000);
</script>

<?php include '../includes/footer.php'; ?>
