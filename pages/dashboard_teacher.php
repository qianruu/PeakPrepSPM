<?php
include '../includes/header.php';
include '../includes/db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<div class="container mt-5">
    <h2>Teacher Dashboard</h2>
    <a href="create_quiz.php" class="btn btn-primary">Create a Quiz</a>
    <a href="view_student_results.php" class="btn btn-info">View Student Results</a>
    <a href="manage_classes.php" class="btn btn-warning">Manage Classes</a>
    <a href="assign_students.php" class="btn btn-success">Assign Students to Classes</a>
    <a href="manage_materials.php" class="btn btn-success">Manage Learning Materials</a> <!-- New -->
</div>


<div class="container mt-5">
    <h3>Assigned Students and Classes</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Class Name</th>
                <th>Student Name</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $teacher_id = $_SESSION['user_id'];
            $assignmentsQuery = "SELECT classes.class_name, users.username FROM class_students 
                                 JOIN classes ON class_students.class_id = classes.id 
                                 JOIN users ON class_students.student_id = users.id 
                                 WHERE classes.teacher_id = ?";
            $stmt = $conn->prepare($assignmentsQuery);
            $stmt->bind_param("i", $teacher_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="2">No students assigned yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<div class="container mt-5">
    <h3>Latest Announcements</h3>
    <?php
    $announcementsQuery = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5";
    $announcements = $conn->query($announcementsQuery);
    
    if ($announcements->num_rows > 0):
        while ($row = $announcements->fetch_assoc()):
    ?>
        <div class="alert alert-warning">
            <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
            <?php echo htmlspecialchars($row['message']); ?><br>
            <small>Posted on: <?php echo $row['created_at']; ?></small>
        </div>
    <?php endwhile; else: ?>
        <p>No announcements at the moment.</p>
    <?php endif; ?>
</div>


<?php include '../includes/footer.php'; ?>