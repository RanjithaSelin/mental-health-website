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

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO feedback (user_id, message) VALUES ($user_id, '$message')");
    $msg = "Thank you for your feedback!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Feedback</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fdfbfb, #ebedee);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                <li class="nav-item"><a class="nav-link" href="community.php"><i class="bi bi-people-fill"></i> Community</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person-circle"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link active" href="feedback.php"><i class="bi bi-envelope"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- FEEDBACK SECTION -->
<div class="container mt-4">
    <div class="card p-4 col-md-8 mx-auto">
        <h3 class="mb-3"><i class="bi bi-envelope"></i> Send Us Your Feedback</h3>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?= $msg ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Your Feedback</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Share your suggestions, issues, or thoughts..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-send-fill"></i> Submit Feedback</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
