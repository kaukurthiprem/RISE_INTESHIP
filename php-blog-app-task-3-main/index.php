<?php
require 'db.php';
session_start();

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search setup
$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchParam = "%$search%";

// Count total records for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE :search OR content LIKE :search");
$countStmt->execute(['search' => $searchParam]);
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Fetch posts
$sql = "SELECT * FROM posts WHERE title LIKE :search OR content LIKE :search ORDER BY created_at DESC LIMIT :start, :limit";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', $searchParam);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Blog Posts</h2>

    <!-- Search Form -->
    <form class="d-flex mb-3" method="GET">
        <input type="text" class="form-control me-2" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>

    <!-- Add New Post Button -->
    <div class="mb-3">
        <a href="create.php" class="btn btn-success">Add New Post</a>
    </div>

    <!-- Posts Table -->
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= $post['id'] ?></td>
                    <td><?= htmlspecialchars($post['title']) ?></td>
                    <td><?= htmlspecialchars(substr($post['content'], 0, 100)) ?>...</td>
                    <td><?= $post['created_at'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete.php?id=<?= $post['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No posts found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

</body>
</html>
