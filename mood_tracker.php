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

$message = "";
$today = date('Y-m-d');

// Check if mood already logged for today
$alreadyLogged = $conn->query("SELECT * FROM mood_logs WHERE user_id = $user_id AND log_date = '$today'")->num_rows > 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$alreadyLogged) {
    $mood = $_POST['mood'];
    $notes = $conn->real_escape_string($_POST['notes']);
    $conn->query("INSERT INTO mood_logs (user_id, mood, notes, log_date) VALUES ($user_id, '$mood', '$notes', '$today')");
    $message = "Your mood for today has been recorded!";
}

// Fetch past 7 days of mood logs
$mood_data = $conn->query("SELECT * FROM mood_logs WHERE user_id = $user_id ORDER BY log_date DESC LIMIT 7");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Mood Tracker</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fff1eb, #ace0f9);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .mood-badge {
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 20px;
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
                <li class="nav-item"><a class="nav-link active" href="mood_tracker.php"><i class="bi bi-emoji-smile"></i> Mood</a></li>
                <li class="nav-item"><a class="nav-link" href="recommendations.php"><i class="bi bi-stars"></i> Suggestions</a></li>
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

<!-- MOOD FORM -->
<div class="container mt-4">
    <div class="card p-4">
        <h3 class="mb-4"><i class="bi bi-emoji-smile"></i> Daily Mood Tracker</h3>

        <?php if ($message): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php elseif ($alreadyLogged): ?>
            <div class="alert alert-info">You have already logged your mood today (<?= $today ?>).</div>
        <?php endif; ?>

        <?php if (!$alreadyLogged): ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Your Mood</label>
                <select class="form-select" name="mood" required>
                    <option value="">-- Choose --</option>
                    <option value="Happy 😊">Happy 😊</option>
                    <option value="Sad 😢">Sad 😢</option>
                    <option value="Anxious 😟">Anxious 😟</option>
                    <option value="Excited 😄">Excited 😄</option>
                    <option value="Tired 😴">Tired 😴</option>
                    <option value="Angry 😠">Angry 😠</option>
                    <option value="Peaceful 🧘">Peaceful 🧘</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes (Optional)</label>
                <textarea class="form-control" name="notes" rows="3" placeholder="Write anything you want to reflect on..."></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send-check"></i> Submit Mood</button>
        </form>
        <?php endif; ?>
    </div>

    <!-- Past Mood Logs -->
    <div class="card p-4 mt-4">
        <h5 class="mb-3"><i class="bi bi-clock-history"></i> Your Recent Mood Entries</h5>
        <ul class="list-group list-group-flush">
            <?php while ($row = $mood_data->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= date("d M Y", strtotime($row['log_date'])) ?>
                    <span class="mood-badge bg-info text-dark"><?= $row['mood'] ?></span>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
