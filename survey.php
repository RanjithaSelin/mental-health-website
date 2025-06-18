<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");
$user_email = $_SESSION['user_email'];
$user_q = $conn->query("SELECT * FROM users WHERE email='$user_email'");
$user = $user_q->fetch_assoc();
$user_id = $user['user_id'];

$survey_taken = false;
$resultMsg = "";

// Check last survey submission
$check = $conn->query("SELECT submitted_on FROM survey_summary WHERE user_id = $user_id ORDER BY submitted_on DESC LIMIT 1");
if ($check && $check->num_rows > 0) {
    $last_record = $check->fetch_assoc();
    $last_date = strtotime($last_record['submitted_on']);
    $today = strtotime("today");
    $diff = ($today - $last_date) / (60 * 60 * 24);
    if ($diff < 7) {
        $survey_taken = true;
        $resultMsg = "✅ You completed a survey on <strong>" . date("d M Y", $last_date) . "</strong>. You can retake it after <strong>" . (7 - floor($diff)) . " day(s)</strong>.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$survey_taken) {
    $total = 0;
    for ($i = 1; $i <= 10; $i++) {
        $total += intval($_POST["q$i"]);
    }

    if ($total <= 15) {
        $status = "Healthy";
        $suggestion = "You're doing great! Keep exercising, eating well, and staying social.";
        $link = "https://www.healthline.com/health/mental-health/improve-your-mental-health";
    } elseif ($total <= 25) {
        $status = "Mild Stress";
        $suggestion = "Take short breaks, practice meditation, and avoid overworking.";
        $link = "https://www.headspace.com/meditation/stress";
    } elseif ($total <= 35) {
        $status = "Moderate Stress";
        $suggestion = "Try journaling, mindfulness apps, and speaking with a peer or mentor.";
        $link = "https://www.mind.org.uk/information-support/tips-for-everyday-living/mindfulness/";
    } else {
        $status = "High Stress / Anxiety";
        $suggestion = "We recommend consulting a professional. Don't wait to seek help.";
        $link = "https://www.betterhelp.com/get-started/";
    }

    $conn->query("INSERT INTO survey_summary (user_id, total_score, result, suggestion) 
                  VALUES ($user_id, $total, '$status', '$suggestion')");

    $resultMsg = "<strong>🧠 Survey Result: $status</strong><br>$suggestion<br>
                  <a href='$link' target='_blank'>Click here for helpful resources</a>";
    $survey_taken = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Survey</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #fce4ec, #e0f7fa);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 15px;
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
                <li class="nav-item"><a class="nav-link active" href="survey.php"><i class="bi bi-list-check"></i> Survey</a></li>
                <li class="nav-item"><a class="nav-link" href="mood_tracker.php"><i class="bi bi-emoji-smile"></i> Mood</a></li>
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

<!-- SURVEY FORM -->
<div class="container mt-4">
    <div class="card p-4 bg-white">
        <h3 class="mb-4"><i class="bi bi-list-check"></i> Mental Health Survey</h3>

        <?php if ($resultMsg): ?>
            <div class="alert alert-info"><?= $resultMsg ?></div>
        <?php endif; ?>

        <?php if (!$survey_taken): ?>
        <form method="POST">
            <?php
            $questions = [
                "How often do you feel anxious or worried?",
                "How well do you sleep at night?",
                "Do you feel hopeful about the future?",
                "How often do you feel overwhelmed by tasks?",
                "Do you enjoy your daily activities?",
                "Do you feel valued and appreciated in your life?",
                "Do you feel tired or lacking energy most days?",
                "How often do you have trouble concentrating?",
                "Do you feel isolated or lonely?",
                "How often do you experience mood swings or irritability?"
            ];
            $options = [
                "1" => "Never / Very Positive",
                "2" => "Rarely",
                "3" => "Sometimes",
                "4" => "Often",
                "5" => "Always / Very Negative"
            ];

            foreach ($questions as $index => $q) {
                $qno = $index + 1;
                echo "<div class='mb-3'>
                        <label class='form-label'>$qno. $q</label>
                        <select class='form-select' name='q$qno' required>";
                foreach ($options as $val => $label) {
                    echo "<option value='$val'>$label</option>";
                }
                echo "</select></div>";
            }
            ?>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle"></i> Submit Survey</button>
        </form>
        <?php else: ?>
            <p class="text-muted text-center">Please return after 7 days to retake the survey.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
