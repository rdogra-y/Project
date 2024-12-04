<?php
session_start();

if (!isset($_SESSION['student'])) {
    header('Location:student_login.php');
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

// Fetch uploaded images
$image_query = $conn->prepare("SELECT instructor_username, image_path FROM uploaded_images");
$image_query->execute();
$images = $image_query->fetchAll(PDO::FETCH_ASSOC);

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

        header .logo a {
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

        /* Main Content */
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
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
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
                <li><a href="home_student.php">Home</a></li>
                <li><a href="about.php">About U</a></li>
                <li>
                    <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <p>Life is all about learning skills. With the help of our knowledgeable instructors, we are blessed to learn different things. Choose a video of your choice.</p>
        <br>
        <p>Try our quick <a href="Quiz/index.html">quiz</a></p>
        <div class="course-list">
            <?php foreach ($courses as $course): ?>
                <div class="course-item">
                    <a href="course_details.php?course_id=<?= htmlspecialchars($course['course_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($course['title'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

         <!-- Display Uploaded Images -->
         <div class="image-gallery">
    <?php foreach ($images as $image): ?>
        <div style="margin-bottom: 20px; text-align: center;">
            <p>Uploaded by: <?= htmlspecialchars($image['instructor_username'], ENT_QUOTES, 'UTF-8') ?></p>
            <img src="<?= htmlspecialchars($image['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="Uploaded Image" style="width:300px; height:auto; margin:10px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
        </div>
    <?php endforeach; ?>
</div>

    </main>

    <!-- Footer -->
    <footer>
        <p>Designed by Rakshita Dogra</p>
        <p>Content owner is RRC, and all rights belong to RRC.</p>
    </footer>
</body>
</html>
