<?php
session_start();
if (!isset($_SESSION['instructor'])) {
    header("Location:instructor_login.php");
    exit();
}

$host = "localhost";
$db = "learning";
$user = "root"; 
$pass = ""; 
$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

// Fetch all courses from the database
$query = $conn->prepare("SELECT * FROM courses");
$query->execute();
$courses = $query->fetchAll(PDO::FETCH_ASSOC);

// File upload helper functions
function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
    $current_folder = dirname(__FILE__);
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

function file_is_an_image($temporary_path, $new_path) {
    $allowed_mime_types = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
    $actual_file_extension = pathinfo($new_path, PATHINFO_EXTENSION);
    $actual_mime_type = getimagesize($temporary_path)['mime'];

    return in_array($actual_file_extension, $allowed_file_extensions) &&
           in_array($actual_mime_type, $allowed_mime_types);
}

function resize_image($temporary_path, $new_path, $width, $height) {
    list($original_width, $original_height, $type) = getimagesize($temporary_path);
    $src = null;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($temporary_path);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($temporary_path);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($temporary_path);
            break;
        default:
            return;
    }

    $dst = imagecreatetruecolor($width, $height);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $original_width, $original_height);

    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($dst, $new_path);
            break;
        case IMAGETYPE_PNG:
            imagepng($dst, $new_path);
            break;
        case IMAGETYPE_GIF:
            imagegif($dst, $new_path);
            break;
    }

    imagedestroy($src);
    imagedestroy($dst);
}

$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);

if ($image_upload_detected) {
    $image_filename = $_FILES['image']['name'];
    $temporary_image_path = $_FILES['image']['tmp_name'];

    // Define the upload folder and create the full path
    $upload_subfolder_name = 'uploads';
    $new_image_path = $upload_subfolder_name . '/' . basename($image_filename);

    // Ensure the upload directory exists
    if (!is_dir($upload_subfolder_name)) {
        mkdir($upload_subfolder_name, 0777, true); // Create the directory if it doesn't exist
    }

    if (file_is_an_image($temporary_image_path, $new_image_path)) {
        resize_image($temporary_image_path, $new_image_path, 300, 300); // Resize to 300x300

        // Save the image path and instructor's username into the database
        $query = $conn->prepare("INSERT INTO uploaded_images (instructor_username, image_path, uploaded_at) VALUES (?, ?, NOW())");
        $query->execute([$_SESSION['instructor'], $new_image_path]);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U&Learning</title>
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

        .navbar .logo a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .nav-links li {
            display: inline;
        }

        /* Main Content Styling */
        main {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        main p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .course-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .course-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1 1 calc(33.333% - 20px);
            box-sizing: border-box;
        }

        .course-item a {
            color: #4CAF50;
            font-weight: bold;
            text-decoration: none;
        }

        .course-item a:hover {
            text-decoration: underline;
        }

        /* Form Styling */
        form {
            background: white;
            padding: 20px;
            margin-top: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        form input[type="file"] {
            display: block;
            margin: 10px auto;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Footer Styling */
        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
        }

        footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo"><a href="index_student.php">U&Learning</a></div>
        <nav>
            <ul class="nav-links">
                <li><a href="instructor_dashboard.php">Home</a></li>
                <li><a href="about.php">About U</a></li>
                <li>
                    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                </li>
                <li><?= "Welcome, Instructor " . $_SESSION['instructor'];?></li>
            </ul>
        </nav>
    </header>
    <main>
        <p>Life is all about learning skills. With the help of our knowledgeable instructors, we are blessed to learn different things. Choose a video of your choice.</p>
        <br>
        <p>For addition of any new topic write in About U.</p>
        <div class="course-list">
            <?php foreach ($courses as $course): ?>
                <div class="course-item">
                    <!-- Link with sanitized output -->
                    <a href="course_details.php?course_id=<?= htmlspecialchars($course['course_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Image Upload Form -->
        <form method="post" enctype="multipart/form-data">
            <label for="image">Upload an Image:</label>
            <input type="file" name="image" id="image">
            <input type="submit" value="Upload">
        </form>
    </main>
    <!-- Footer -->
    <footer>
        <p>Designed by Rakshita Dogra</p>
        <p>Content owner is RRC, and all rights belong to RRC</p>
    </footer>
</body>
</html>
