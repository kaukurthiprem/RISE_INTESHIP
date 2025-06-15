$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

if (!empty($search)) {
    $query = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ? LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $term = "%$search%";
    $stmt->bind_param("ssii", $term, $term, $limit, $offset);
} else {
    $query = "SELECT * FROM posts LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

// Display results...

// Pagination links
$total_result = $conn->query("SELECT COUNT(*) as count FROM posts")->fetch_assoc()['count'];
$total_pages = ceil($total_result / $limit);

for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i&search=" . urlencode($search) . "'>$i</a> ";
}
