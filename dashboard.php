<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: student_login.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="logout.php">Logout</a>
</body>
</html>
