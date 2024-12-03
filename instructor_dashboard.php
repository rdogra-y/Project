<?php
session_start();
if (!isset($_SESSION['instructor'])) {
    header("Location: instructor_login.php");
    exit();
}
echo "Welcome, Instructor " . $_SESSION['instructor'];
?>
<a href="logout.php">Logout</a>
