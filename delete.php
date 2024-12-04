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
            max-width: 500px;
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

        form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
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
            max-width: 500px;
            margin: 10px auto;
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

    <!-- Page Header -->
    <h1>Delete Course</h1>

    <!-- Success Message -->
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Form to delete course -->
    <form method="POST">
        <label for="course_id">Course ID:</label>
        <input type="number" id="course_id" name="course_id" placeholder="Enter Course ID" required>
        <button type="submit">Delete Course</button>
    </form>
</body>
</html>
