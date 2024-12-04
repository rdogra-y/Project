<?php
session_start();
$host = "localhost";
$db = "learning";
$user = "root"; 
$pass = ""; 

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND role = 'Admin'");
    $query->execute([$username, $password]);
    $user = $query->fetch();

    if ($user) {
        $_SESSION['admin'] = $user['username'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Invalid credentials or not an admin.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding-top: 60px; /* Push content below the navbar */
        }

        /* Navbar Styling */
        header.navbar {
            width: 100%;
            background-color: #4CAF50; /* Green background */
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            height: 60px; /* Fixed height */
            line-height: 60px; /* Vertically centers text */
        }

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 1rem;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        /* Form Styling */
        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Footer Styling */
        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="logo">U&Learning</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About U</a></li>
                <li><a href="student_login.php">Student Login</a></li>
                <li><a href="instructor_login.php">Instructor Login</a></li>
                <li><a href="admin_login.php">Admin Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Login Form -->
    <form method="POST">
        <h2>Admin Login</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <!-- Footer -->
    <footer>
        <p>Designed by Rakshita Dogra</p>
    </footer>
</body>
</html>
