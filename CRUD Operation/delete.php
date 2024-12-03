<?php
// Database connection settings
$dsn = 'mysql:host=localhost;dbname=learning;charset=utf8';
$username = 'root';
$password = '';

// Initialize error and success message arrays
$errors = [];
$successMessage = '';

try {
    // Establish database connection
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate and sanitize course_id input
        $course_id = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);

        if (!empty($course_id)) {
            // Fetch the image path from the database
            $stmt = $pdo->prepare("SELECT image FROM Courses WHERE course_id = :course_id");
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->execute();
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            // Delete the image file if it exists
            if ($course && !empty($course['image']) && file_exists($course['image'])) {
                unlink($course['image']); // Remove the image from the file system
            }

            // Delete the course from the database
            $stmt = $pdo->prepare("DELETE FROM Courses WHERE course_id = :course_id");
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->execute();

            $successMessage = "Course and associated image deleted successfully!";
        } else {
            $errors[] = "Invalid course ID.";
        }
    }
} catch (PDOException $e) {
    // Log the error and display a generic error message
    error_log("Database Error: " . $e->getMessage(), 3, 'logs/errors.log');
    $errors[] = "There was an issue deleting the course. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Course</title>
</head>
<body>
    <h1>Delete Course</h1>

    <!-- Success Message -->
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Form to delete course -->
    <form method="POST">
        <label for="course_id">Course ID:</label><br>
        <input type="number" id="course_id" name="course_id" placeholder="Enter Course ID" required><br><br>
        <button type="submit">Delete Course</button>
    </form>
</body>
</html>
