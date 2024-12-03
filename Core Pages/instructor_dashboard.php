<?php
session_start();
if (!isset($_SESSION['instructor'])) {
    header("Location: User Pages/instructor_login.php");
    exit();
}
echo "Welcome, Instructor " . $_SESSION['instructor'];
?>
<a href="User Pages/logout.php">Logout</a>
