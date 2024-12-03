<?php
session_start();

if (!isset($_SESSION['student'])) {
    header('Location: User Pages/student_login.php');
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

 // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
        $current_folder = dirname(__FILE__);
        
        // Build an array of paths segment names to be joins using OS specific slashes.
        $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
        
        // The DIRECTORY_SEPARATOR constant is OS specific.
        return join(DIRECTORY_SEPARATOR, $path_segments);
     }
 
     // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
     function file_is_an_image($temporary_path, $new_path) {
         $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
         $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
         
         $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
         $actual_mime_type        = getimagesize($temporary_path)['mime'];
         
         $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
         $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
         
         return $file_extension_is_valid && $mime_type_is_valid;
     }
     
     $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
     $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);
 
     if ($image_upload_detected) { 
         $image_filename        = $_FILES['image']['name'];
         $temporary_image_path  = $_FILES['image']['tmp_name'];
         $new_image_path        = file_upload_path($image_filename);
         if (file_is_an_image($temporary_image_path, $new_image_path)) {
             move_uploaded_file($temporary_image_path, $new_image_path);
         }
     }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U&Learning</title>
    <link rel="stylesheet" href="Styling/styles.css">
    
    </style>
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo"><a href="Core Pages/index_student.php">U&Learning<a></div>
        <nav>
            <ul class="nav-links">
                <li><a href="Core Pages/home_student.php">Home</a></li>
                <li><a href="Core Pages/about.php">About U</a></li>
                <li>
                <a href="User Pages/logout.php" class="btn btn-danger mt-3">Logout</a>
                </li>
            </ul>
        </nav>

    </header>
    <main>
        <p>Life is all about learning skills. With the help of our knowledgeable instructors, we are blessed to learn different things. Choose a video of your choice.</p>
        <br>
        <p>Try our quick <a href="Quiz/index.html"> quiz </a></p>
        <div class="course-list">
            <?php foreach ($courses as $course): ?>
                <div class="course-item">
                    <!-- Link with sanitized output -->
                    <a href="Course Pages/course_details.php?course_id=<?= htmlspecialchars($course['course_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

    <!-- image upload -->
    <form method='post' enctype='multipart/form-data'>
         <label for='image'>Image Filename:</label>
         <input type='file' name='image' id='image'>
         <input type='submit' name='submit' value='Upload Image'>
     </form>
     
    <?php if ($upload_error_detected): ?>

        <p>Error Number: <?= $_FILES['image']['error'] ?></p>

    <?php elseif ($image_upload_detected): ?>

        <p>Client-Side Filename: <?= $_FILES['image']['name'] ?></p>
        <p>Apparent Mime Type:   <?= $_FILES['image']['type'] ?></p>
        <p>Size in Bytes:        <?= $_FILES['image']['size'] ?></p>
        <p>Temporary Path:       <?= $_FILES['image']['tmp_name'] ?></p>

    <?php endif ?>
    </main>
    <!-- Footer -->
    <footer>
        <p>Designed by Rakshita Dogra</p>
        <p>Content owner is rrc and all rights are belonging to rrc</p>
    </footer>
</body>
</html>
