<?php
$dsn = 'mysql:host=localhost;dbname=learning;charset=utf8';
$username = 'serveruser';
$password = 'gorgonzola7!';

$errors = [];
$successMessage = '';

try {
    // Establish database connection
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize inputs
        $course_id = filter_var($_POST['course_id'], FILTER_VALIDATE_INT);
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
        $instructor_id = filter_var($_POST['instructor_id'], FILTER_VALIDATE_INT);

        // Validation
        if (empty($course_id) || $course_id <= 0) {
            $errors[] = 'Valid Course ID is required.';
        } else {
            // Check if the Course ID exists
            $checkStmt = $pdo->prepare("SELECT * FROM Courses WHERE course_id = :course_id");
            $checkStmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $checkStmt->execute();
            if ($checkStmt->rowCount() === 0) {
                $errors[] = 'Course ID does not exist.';
            }
        }

        if (empty($title)) {
            $errors[] = 'Course title is required.';
        } elseif (strlen($title) > 255) {
            $errors[] = 'Course title must not exceed 255 characters.';
        }

        if (empty($description)) {
            $errors[] = 'Course description is required.';
        }

        if (empty($instructor_id) || $instructor_id <= 0) {
            $errors[] = 'Valid Instructor ID is required.';
        }

        // If no errors, proceed with the update
        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE Courses SET title = :title, description = :description, instructor_id = :instructor_id WHERE course_id = :course_id");
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
            $stmt->execute();

            // Confirm update
            if ($stmt->rowCount() > 0) {
                $successMessage = 'Course updated successfully!';
            } else {
                $errors[] = 'No changes were made to the course.';
            }
        }
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage(), 3, 'errors.log');
    $errors[] = "There was an issue updating the course. Please try again later.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
</head>
<body>
    <h1>Update Course</h1>

    <!-- Display Success Message -->
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <!-- Display Error Messages -->
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label for="course_id">Course ID:</label><br>
        <input type="number" id="course_id" name="course_id" placeholder="Enter Course ID" required><br><br>

        <label for="title">Course Title:</label><br>
        <input type="text" id="title" name="title" placeholder="Enter Course Title"><br><br>

        <label for="description">Course Description:</label><br>
        <textarea id="description" name="description" placeholder="Enter Course Description"></textarea><br><br>

        <label for="instructor_id">Instructor ID:</label><br>
        <input type="number" id="instructor_id" name="instructor_id" placeholder="Enter Instructor ID"><br><br>

        <button type="submit">Update Course</button>
    </form>
</body>
</html>
