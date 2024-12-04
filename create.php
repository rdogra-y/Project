<?php
// Define database connection variables
$dsn = 'mysql:host=localhost;dbname=learning;charset=utf8';
$username = 'root';
$password = '';

// Initialize database connection
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Initialize an array to store errors
$errors = [];
$successMessage = '';

// Main logic for form submission
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
        $instructor_id = filter_var($_POST['instructor_id'], FILTER_VALIDATE_INT);
        $imagePath = null;

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $targetDir = 'uploads/images/';
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $targetDir . $imageName;

            // Check if the folder exists, if not, create it
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Validate and resize image
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    // Resize the image
                    list($width, $height) = getimagesize($targetFile);
                    $maxSize = 500;
                    $scale = min($maxSize / $width, $maxSize / $height);
                    $newWidth = floor($width * $scale);
                    $newHeight = floor($height * $scale);

                    $imageResource = imagecreatefromstring(file_get_contents($targetFile));
                    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                    imagecopyresampled($resizedImage, $imageResource, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                    // Save resized image
                    imagejpeg($resizedImage, $targetFile, 90);
                    $imagePath = $targetFile;
                } else {
                    $errors[] = "Failed to upload the image.";
                }
            } else {
                $errors[] = "Invalid image format. Allowed formats: JPEG, PNG, GIF.";
            }
        }

        // Validate other fields
        if (empty($title)) {
            $errors[] = 'Course title is required.';
        }
        if (empty($description)) {
            $errors[] = 'Course description is required.';
        }
        if (empty($instructor_id) || $instructor_id <= 0) {
            $errors[] = 'Valid Instructor ID is required.';
        }

        // Insert data into the database
        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO Courses (title, description, instructor_id, image) VALUES (:title, :description, :instructor_id, :image)");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':instructor_id', $instructor_id);
            $stmt->bindParam(':image', $imagePath);
            $stmt->execute();
            $successMessage = "Course added successfully!";
        }
    }
} catch (PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
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

        /* Main Form Styling */
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
        }

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
        form textarea,
        form input[type="file"] {
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
                <li><a href="create.php">create page</a></li>
                <li><a href="read.php">view page</a></li>
                <li><a href="update.php">update page</a></li>
                <li><a href="delete.php">delete page</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <h1>Create a New Course</h1>
    <?php if ($successMessage): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Course Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter course title" required>

        <label for="description">Course Description:</label>
        <textarea id="description" name="description" placeholder="Enter course description" required></textarea>

        <label for="instructor_id">Instructor ID:</label>
        <input type="number" id="instructor_id" name="instructor_id" placeholder="Enter instructor ID" required>

        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image">

        <button type="submit">Add Course</button>
    </form>
</body>
</html>

