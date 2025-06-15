<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>You are successfully logged in.</p>

    <ul>
        <li><a href="create.php">Create New Post</a></li>
        <li><a href="index.php">View All Posts</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
