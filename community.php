<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");
$user_email = $_SESSION['user_email'];
$user = $conn->query("SELECT * FROM users WHERE email='$user_email'")->fetch_assoc();
$user_id = $user['user_id'];

// Handle post submission
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['content'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);

    if ($title != "" && $content != "") {
        $conn->query("INSERT INTO community_posts (user_id, title, content) VALUES ($user_id, '$title', '$content')");
        $msg = "Your post has been shared with the community!";
    }
}

// Fetch all posts
$posts = $conn->query("SELECT p.*, u.full_name FROM community_posts p JOIN users u ON p.user_id = u.user_id ORDER BY posted_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Community</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f7d9e3, #e2f0fb);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="user_dashboard.php"><i class="bi bi-heart-pulse-fill"></i> Zentra</a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="survey.php"><i class="bi bi-list-check"></i> Survey</a></li>
                <li class="nav-item"><a class="nav-link" href="mood_tracker.php"><i class="bi bi-emoji-smile"></i> Mood</a></li>
                <li class="nav-item"><a class="nav-link" href="recommendations.php"><i class="bi bi-stars"></i> Suggestions</a></li>
                <li class="nav-item"><a class="nav-link" href="resources.php"><i class="bi bi-journal-richtext"></i> Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="chatbot.php"><i class="bi bi-chat-dots"></i> Chatbot</a></li>
                <li class="nav-item"><a class="nav-link" href="emergency_support.php"><i class="bi bi-exclamation-triangle"></i> Emergency</a></li>
                <li class="nav-item"><a class="nav-link active" href="community.php"><i class="bi bi-people-fill"></i> Community</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person-circle"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="bi bi-envelope"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- COMMUNITY SECTION -->
<div class="container mt-4">
    <h3 class="mb-4 text-center"><i class="bi bi-people-fill"></i> Community Forum</h3>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <!-- Post Form -->
    <div class="card p-4 mb-4">
        <h5><i class="bi bi-pencil-square"></i> Share Something with the Community</h5>
        <form method="POST">
            <div class="mb-2">
                <input type="text" name="title" required class="form-control" placeholder="Post Title" />
            </div>
            <div class="mb-2">
                <textarea name="content" required rows="4" class="form-control" placeholder="Write your thoughts..."></textarea>
            </div>
            <button class="btn btn-primary" type="submit"><i class="bi bi-send-fill"></i> Post</button>
        </form>
    </div>

    <!-- Posts List -->
    <div class="row g-4">
        <?php if ($posts->num_rows == 0): ?>
            <div class="alert alert-info">No community posts yet. Be the first to share!</div>
        <?php else: ?>
            <?php while ($row = $posts->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card p-3 h-100">
                        <h5><i class="bi bi-chat-dots-fill text-primary"></i> <?= htmlspecialchars($row['title']) ?></h5>
                        <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                        <small class="text-muted">Posted by <?= htmlspecialchars($row['full_name']) ?> on <?= date("d M Y h:i A", strtotime($row['posted_on'])) ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
