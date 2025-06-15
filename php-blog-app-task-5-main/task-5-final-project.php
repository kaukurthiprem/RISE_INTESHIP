<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$title, $content]);
    }
}
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = $_GET['search'] ?? '';
$search_query = $search ? "WHERE title LIKE :search OR content LIKE :search" : "";
$total = $pdo->prepare("SELECT COUNT(*) FROM posts $search_query");
if ($search) $total->bindValue(':search', "%$search%");
$total->execute();
$totalPosts = $total->fetchColumn();
$pages = ceil($totalPosts / $limit);
$query = "SELECT * FROM posts $search_query ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($query);
if ($search) $stmt->bindValue(':search', "%$search%");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Final Blog Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Blog Dashboard</h2>
    <form method="POST" class="mb-3">
        <input type="text" name="title" placeholder="Title" class="form-control mb-2" required>
        <textarea name="content" placeholder="Content" class="form-control mb-2" required></textarea>
        <button type="submit" name="submit_post" class="btn btn-primary">Post</button>
    </form>
    <form method="GET" class="mb-3">
        <input type="text" name="search" placeholder="Search posts..." class="form-control" value="<?= htmlspecialchars($search) ?>">
    </form>
    <?php foreach ($posts as $post): ?>
        <div class="border p-3 mb-2">
            <h4><?= htmlspecialchars($post['title']) ?></h4>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <small><?= $post['created_at'] ?></small>
        </div>
    <?php endforeach; ?>
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</body>
</html>