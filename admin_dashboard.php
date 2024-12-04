<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location:admin_login.php");
    exit();
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U&Learning</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
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

        /* Card Styling */
        .row {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 10px;
            flex: 1;
            max-width: 300px;
            padding: 20px;
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 20px;
        }

        .btn {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 3px;
            font-size: 0.9em;
            font-weight: bold;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Footer Styling */
        footer {
            text-align: center;
            padding: 15px 0;
            background-color: #333;
            color: white;
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
                <li><?= "Welcome, Admin " . $_SESSION['admin']; ?></li>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="create.php">Create Page</a></li>
                <li><a href="read.php">View Page</a></li>
                <li><a href="update.php">Update Page</a></li>
                <li><a href="delete.php">Delete Page</a></li>
            </ul>
        </nav>
    </header>
    <p>Manage your courses with ease. Use the navigation menu above to create, view, update, delete, or search for courses.</p>

    <!-- Quick Links to Major Features -->
    <div class="mt-4 row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create a Course</h5>
                    <p class="card-text">Add new courses to the system with titles, descriptions, and instructors.</p>
                    <a href="create.php" class="btn btn-success">Create</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View Courses</h5>
                    <p class="card-text">Browse all courses currently available in the system.</p>
                    <a href="read.php" class="btn btn-primary">View Courses</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Search Courses</h5>
                    <p class="card-text">Search for specific courses by keywords or categories.</p>
                    <a href="search.php" class="btn btn-info">Search</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Features Section -->
    <div class="mt-4 row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Update Courses</h5>
                    <p class="card-text">Modify course details such as title, description, or instructor.</p>
                    <a href="update.php" class="btn btn-warning">Update</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Delete Courses</h5>
                    <p class="card-text">Remove unwanted courses from the system.</p>
                    <a href="delete.php" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Designed by Rakshita Dogra</p>
    </footer>
</body>
</html>
