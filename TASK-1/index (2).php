<?php
session_start();
include 'db.php';

// Fetch all posts
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Blog Posts</title>
</head>
<body>
    <h2>All Blog Posts</h2>
    <p><a href="dashboard.php">← Back to Dashboard</a></p>

    <?php while ($row = $result->fetch_assoc()): ?>
        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
        <small>Posted on: <?php echo $row['created_at']; ?></small><br>
        <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
        <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        <hr>
    <?php endwhile; ?>
</body>
</html>
