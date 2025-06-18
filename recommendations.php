<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");

// Debug mode (true to see raw errors)
$debug = false;

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email'];
$user_q = $conn->query("SELECT * FROM users WHERE email='$user_email'");
if (!$user_q || $user_q->num_rows == 0) {
    die("User not found.");
}
$user = $user_q->fetch_assoc();
$user_id = $user['user_id'];

// Fetch mood
$mood_row = null;
$mood_q = $conn->query("SELECT * FROM mood_logs WHERE user_id = $user_id ORDER BY log_date DESC LIMIT 1");
if ($mood_q && $mood_q->num_rows > 0) {
    $mood_row = $mood_q->fetch_assoc();
}
$latest_mood = $mood_row['mood'] ?? null;
$mood_date = $mood_row['log_date'] ?? null;

// Fetch survey
$survey_row = null;
$survey_q = $conn->query("SELECT * FROM survey_summary WHERE user_id = $user_id ORDER BY submitted_on DESC LIMIT 1");
if ($survey_q && $survey_q->num_rows > 0) {
    $survey_row = $survey_q->fetch_assoc();
}
$survey_status = $survey_row['result'] ?? null;
$survey_suggestion = $survey_row['suggestion'] ?? null;
$survey_date = $survey_row['submitted_on'] ?? null;

// Suggestion logic
$extra_tip = "Log your mood to receive custom suggestions.";
switch ($latest_mood) {
    case "Happy 😊": $extra_tip = "Keep spreading positivity and continue what you love doing."; break;
    case "Sad 😢": $extra_tip = "Take a walk, talk to a friend, or write down your thoughts."; break;
    case "Anxious 😟": $extra_tip = "Try a deep breathing exercise or listen to calming music."; break;
    case "Excited 😄": $extra_tip = "Channel your excitement into a creative hobby or activity."; break;
    case "Tired 😴": $extra_tip = "Consider taking a power nap or sleeping early tonight."; break;
    case "Angry 😠": $extra_tip = "Step away from the situation and practice slow breathing."; break;
    case "Peaceful 🧘": $extra_tip = "Great! Try meditation to stay grounded."; break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Recommendations</title>
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
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
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
                <li class="nav-item"><a class="nav-link active" href="recommendations.php"><i class="bi bi-stars"></i> Suggestions</a></li>
                <li class="nav-item"><a class="nav-link" href="resources.php"><i class="bi bi-journal-richtext"></i> Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="chatbot.php"><i class="bi bi-chat-dots"></i> Chatbot</a></li>
                <li class="nav-item"><a class="nav-link" href="emergency_support.php"><i class="bi bi-exclamation-triangle"></i> Emergency</a></li>
                <li class="nav-item"><a class="nav-link" href="community.php"><i class="bi bi-people-fill"></i> Community</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person-circle"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="feedback.php"><i class="bi bi-envelope"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link text-warning" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- RECOMMENDATION CARD -->
<div class="container mt-4">
    <div class="card p-4">
        <h3 class="mb-4"><i class="bi bi-stars"></i> Your Personalized Recommendations</h3>

        <?php if ($latest_mood || $survey_status): ?>
            <?php if ($latest_mood): ?>
                <div class="mb-3">
                    <h5>🧠 Based on your latest mood (<strong><?= htmlspecialchars($latest_mood) ?></strong> - <?= $mood_date ?>):</h5>
                    <p class="text-muted"><?= $extra_tip ?></p>
                </div>
            <?php endif; ?>

            <?php if ($survey_status): ?>
                <div class="mb-3">
                    <h5>📊 Based on your last survey result (<strong><?= htmlspecialchars($survey_status) ?></strong> - <?= date("d M Y", strtotime($survey_date)) ?>):</h5>
                    <p class="text-muted"><?= htmlspecialchars($survey_suggestion) ?></p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info">No mood or survey data available. Please complete a mood log or survey to get recommendations.</div>
        <?php endif; ?>

        <?php if ($debug): ?>
            <pre><?php print_r($mood_row); print_r($survey_row); ?></pre>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
