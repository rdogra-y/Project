<?php
session_start();

if (!isset($_SESSION['student'])) {
    header('Location: student_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U&Learning</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo"><a href="index_student.php">U&Learning<a></div>
        <nav>
            <ul class="nav-links">
                <li><a href="home_student.php">Home</a></li>
                <li><a href="#">Search</a></li>
                <li>
                <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                </li>
            </ul>
        </nav>
        
    

</li>

    </header>

  
    Life is all about learning skills And with the help of our knowledgable instructors we got the blessing to learn different thing. Choose video of you choice.

   

    <!-- Footer -->
    <footer>
        <p>Designed by Rakshita Dogra</p>
        <p>Content owner is rrc and all rights are belonging to rrc</p>
    </footer>
</body>
</html>
