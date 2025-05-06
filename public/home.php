<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
//$host = "localhost";
//$dbname = "BlogDB";
//$username = "root";
//$password = "";

require_once "db.php";

// Add LIKE column if it doesn't exist (run once)
//$conn->query("ALTER TABLE posts ADD COLUMN likes INT DEFAULT 0");

// Handle new post submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_post'])) {
    $title = trim($_POST['post_title']);
    $content = trim($_POST['post_content']);
    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_SESSION['user_id'], $title, $content);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: home.php");
    exit();
}

// Handle like button
if (isset($_GET['like'])) {
    $post_id = (int)$_GET['like'];
    $conn->query("UPDATE posts SET likes = likes + 1 WHERE id = $post_id");
    header("Location: home.php");
    exit();
}

// Fetch all posts
$posts = $conn->query("
    SELECT posts.id, posts.title, posts.content, posts.likes, posts.created_at, users.username
    FROM posts
    JOIN users ON posts.user_id = users.id
    ORDER BY posts.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Blog - Home</title>
</head>
<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>

    <form method="POST" action="home.php">
        <input type="text" name="post_title" placeholder="Post title" required><br><br>
        <textarea name="post_content" rows="4" cols="50" placeholder="What's on your mind?" required></textarea><br><br>
        <button type="submit" name="new_post">Post</button>
    </form>

    <h3>Recent Posts</h3>
    <?php while ($row = $posts->fetch_assoc()): ?>
        <div style="border:1px solid #ccc; padding:10px; margin:10px 0;">
            <strong><?= htmlspecialchars($row['username']) ?></strong>
            <em style="color:gray;"> - <?= $row['created_at'] ?></em><br><br>
            <h4><?= htmlspecialchars($row['title']) ?></h4>
            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
            <form method="GET" action="home.php" style="display:inline;">
                <input type="hidden" name="like" value="<?= $row['id'] ?>">
                <button type="submit">❤️ Like (<?= $row['likes'] ?>)</button>
            </form>
        </div>
    <?php endwhile; ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>