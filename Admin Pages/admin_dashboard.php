<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: Admin Pages/admin_login.php");
    exit();
}
echo "Welcome, Admin " . $_SESSION['admin'];
?>
<a href="logout.php">Logout</a>
<h1>Welcome to Learning Management System</h1>
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