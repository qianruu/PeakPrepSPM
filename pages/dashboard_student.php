<?php
include '../includes/header.php';
include '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT badge FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>Your current badge: <strong><?php echo $user['badge']; ?></strong></p>
    <a href="quiz.php" class="btn btn-primary">Start Quiz</a>
    <a href="materials.php" class="btn btn-success">Study Materials</a>
    <a href="view_materials.php" class="btn btn-success">View Learning Materials</a> <!-- New -->
    <a href="results.php" class="btn btn-info">View Results</a>
</div>


<div class="container mt-5">
    <h3>Available Quizzes</h3>
    <?php
    $student_id = $_SESSION['user_id'];

    $quizQuery = "SELECT quizzes.id, quizzes.subject, quizzes.level, classes.class_name 
                  FROM quizzes 
                  JOIN classes ON quizzes.class_id = classes.id 
                  JOIN class_students ON class_students.class_id = classes.id 
                  WHERE class_students.student_id = ?";
    
    $stmt = $conn->prepare($quizQuery);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $quizzes = $stmt->get_result();

    if ($quizzes->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Subject</th>
                    <th>Level</th>
                    <th>Class</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $quizzes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['level']); ?></td>
                    <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                    <td><a href="quiz.php?id=<?php echo $row['id']; ?>" class="btn btn-success">Take Quiz</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No quizzes assigned yet.</p>
    <?php endif; ?>
</div>



<div class="container mt-5">
    <h3>Your Assigned Classes</h3>
    <?php
    $student_id = $_SESSION['user_id'];
    $sql = "SELECT classes.class_name FROM class_students 
            JOIN classes ON class_students.class_id = classes.id 
            WHERE class_students.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($row = $result->fetch_assoc()): ?>
                <li class="list-group-item"><?php echo htmlspecialchars($row['class_name']); ?></li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>You are not assigned to any classes yet.</p>
    <?php endif; ?>
</div>






<div class="container mt-5">
    <h3>Latest Announcements</h3>
    <?php
    $announcementsQuery = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5";
    $announcements = $conn->query($announcementsQuery);
    
    if ($announcements->num_rows > 0):
        while ($row = $announcements->fetch_assoc()):
    ?>
        <div class="alert alert-info">
            <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
            <?php echo htmlspecialchars($row['message']); ?><br>
            <small>Posted on: <?php echo $row['created_at']; ?></small>
        </div>
    <?php endwhile; else: ?>
        <p>No announcements at the moment.</p>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
