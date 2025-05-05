<?php
include '../includes/header.php';
include '../includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $sql = "INSERT INTO discussions (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Discussion posted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

$discussions = $conn->query("SELECT discussions.*, users.username FROM discussions JOIN users ON discussions.user_id = users.id ORDER BY created_at DESC");
?>

<div class="container mt-5">
    <h2>Discussion Forum</h2>
    <form method="POST">
        <input type="text" name="title" class="form-control mb-3" placeholder="Title" required>
        <textarea name="content" class="form-control mb-3" placeholder="Your question or topic..." required></textarea>
        <button type="submit" class="btn btn-primary">Post</button>
    </form>
    <hr>
    <h3>Recent Discussions</h3>
    <?php while ($row = $discussions->fetch_assoc()): ?>
        <div class="border p-3 mb-3">
            <h5><?php echo $row['title']; ?> <small>by <?php echo $row['username']; ?></small></h5>
            <p><?php echo $row['content']; ?></p>
            <small><?php echo $row['created_at']; ?></small>
        </div>
    <?php endwhile; ?>
</div>

<?php include '../includes/footer.php'; ?>
