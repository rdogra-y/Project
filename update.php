<?php
$dsn = 'mysql:host=localhost;dbname=learning;charset=utf8';
$username = 'root';
$password = '';

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
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Navbar Styling */
        header.navbar {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header .logo {
            font-size: 1.5em;
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 15px;
        }

        .nav-links li {
            display: inline;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Page Header */
        h1 {
            text-align: center;
            margin: 20px 0;
            color: #4CAF50;
        }

        /* Form Styling */
        form {
            background: white;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        form input[type="text"],
        form input[type="number"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        form textarea {
            resize: vertical;
        }

        form button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        /* Success and Error Messages */
        p {
            text-align: center;
            font-weight: bold;
        }

        ul {
            max-width: 600px;
            margin: 0 auto;
            padding: 0;
            list-style: none;
            text-align: left;
        }

        ul li {
            color: red;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">U&Learning</div>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php">Logout</a></li>
                <li><a href="create.php">Create Page</a></li>
                <li><a href="read.php">View Page</a></li>
                <li><a href="update.php">Update Page</a></li>
                <li><a href="delete.php">Delete Page</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <h1>Update Course</h1>

    <!-- Display Success Message -->
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <!-- Display Error Messages -->
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST">
        <label for="course_id">Course ID:</label>
        <input type="number" id="course_id" name="course_id" placeholder="Enter Course ID" required>

        <label for="title">Course Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter Course Title">

        <label for="description">Course Description:</label>
        <textarea id="description" name="description" placeholder="Enter Course Description"></textarea>

        <label for="instructor_id">Instructor ID:</label>
        <input type="number" id="instructor_id" name="instructor_id" placeholder="Enter Instructor ID">

        <button type="submit">Update Course</button>
    </form>
</body>
</html>
