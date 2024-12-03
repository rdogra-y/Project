<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
echo "Welcome, Admin " . $_SESSION['admin'];
?>
<a href="logout.php">Logout</a>
