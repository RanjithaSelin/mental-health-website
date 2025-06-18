<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");
$user_email = $_SESSION['user_email'];
$user = $conn->query("SELECT * FROM users WHERE email='$user_email'")->fetch_assoc();

// Fetch emergency contacts
$contacts = $conn->query("SELECT * FROM emergency_contacts ORDER BY country, title");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Emergency Support</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffe3e3, #f7f7f7);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
                <li class="nav-item"><a class="nav-link active" href="emergency_support.php"><i class="bi bi-exclamation-triangle"></i> Emergency</a></li>
                <li class="nav-item"><a class="nav-link" href="community.php"><i class="bi bi-people-fill"></i> Community</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person-circle"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="bi bi-envelope"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- EMERGENCY SUPPORT SECTION -->
<div class="container mt-4">
    <div class="text-center mb-4">
        <h3><i class="bi bi-exclamation-triangle"></i> Emergency Support</h3>
        <p class="text-muted">If you or someone you know is in crisis, contact the resources below for immediate help.</p>
    </div>

    <div class="row g-4">
        <?php if ($contacts->num_rows == 0): ?>
            <div class="alert alert-info text-center">No emergency contacts found.</div>
        <?php else: ?>
            <?php while ($row = $contacts->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card p-3 h-100">
                        <h5><i class="bi bi-person-lines-fill text-danger"></i> <?= htmlspecialchars($row['title']) ?></h5>
                        <p class="mb-1"><strong>Country:</strong> <?= htmlspecialchars($row['country']) ?></p>
                        <p class="mb-1"><strong>Hotline:</strong> <a href="tel:<?= $row['phone_number'] ?>" class="text-dark"><?= htmlspecialchars($row['phone_number']) ?></a></p>
                        <p><strong>Website:</strong> <a href="<?= $row['website'] ?>" target="_blank"><?= htmlspecialchars($row['website']) ?></a></p>
                        <small class="text-muted">Added on <?= date("d M Y", strtotime($row['created_on'])) ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
