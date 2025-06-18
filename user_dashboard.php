<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: auth.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");
$user_email = $_SESSION['user_email'];
$user = $conn->query("SELECT full_name FROM users WHERE email='$user_email'")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra | Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #fce4ec);
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background: linear-gradient(to right, #0077b6, #00b4d8);
        }
        .card {
            border: none;
            border-radius: 20px;
            color: #fff;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .gradient-1 { background: linear-gradient(135deg, #74ebd5, #9face6); }
        .gradient-2 { background: linear-gradient(135deg, #ff9a9e, #fad0c4); }
        .gradient-3 { background: linear-gradient(135deg, #a18cd1, #fbc2eb); }
        .gradient-4 { background: linear-gradient(135deg, #f6d365, #fda085); }
        .gradient-5 { background: linear-gradient(135deg, #ffecd2, #fcb69f); color: #333; }
        .gradient-6 { background: linear-gradient(135deg, #89f7fe, #66a6ff); }

        .card h5, .card i {
            color: #fff;
            font-weight: 600;
        }
        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }
        .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Zentra Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person-circle"></i> <?= $user['full_name'] ?></a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- MAIN DASHBOARD -->
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Welcome to Zentra 🌿</h2>
        <p>Your mental wellness starts here.</p>
    </div>

    <div class="row g-4">
        <!-- Survey -->
        <div class="col-md-4 col-sm-6">
            <a href="survey.php" class="text-decoration-none">
                <div class="card p-4 gradient-1 text-center">
                    <i class="bi bi-list-check fs-2"></i>
                    <h5 class="mt-2">Take Mental Health Survey</h5>
                </div>
            </a>
        </div>

        <!-- Mood Tracker -->
        <div class="col-md-4 col-sm-6">
            <a href="mood_tracker.php" class="text-decoration-none">
                <div class="card p-4 gradient-2 text-center">
                    <i class="bi bi-emoji-smile fs-2"></i>
                    <h5 class="mt-2">Log Your Mood</h5>
                </div>
            </a>
        </div>

        <!-- Suggestions -->
        <div class="col-md-4 col-sm-6">
            <a href="recommendations.php" class="text-decoration-none">
                <div class="card p-4 gradient-3 text-center">
                    <i class="bi bi-stars fs-2"></i>
                    <h5 class="mt-2">Personalized Suggestions</h5>
                </div>
            </a>
        </div>

        <!-- Resources -->
        <div class="col-md-4 col-sm-6">
            <a href="resources.php" class="text-decoration-none">
                <div class="card p-4 gradient-4 text-center">
                    <i class="bi bi-journal-richtext fs-2"></i>
                    <h5 class="mt-2">Mental Health Resources</h5>
                </div>
            </a>
        </div>

        <!-- Chatbot -->
        <div class="col-md-4 col-sm-6">
            <a href="chatbot.php" class="text-decoration-none">
                <div class="card p-4 gradient-5 text-center">
                    <i class="bi bi-chat-dots fs-2"></i>
                    <h5 class="mt-2">Zentra Chatbot</h5>
                </div>
            </a>
        </div>

        <!-- Emergency Support -->
        <div class="col-md-4 col-sm-6">
            <a href="emergency_support.php" class="text-decoration-none">
                <div class="card p-4 gradient-6 text-center">
                    <i class="bi bi-exclamation-triangle fs-2"></i>
                    <h5 class="mt-2">Emergency Support</h5>
                </div>
            </a>
        </div>

        <!-- Community -->
        <div class="col-md-4 col-sm-6">
            <a href="community.php" class="text-decoration-none">
                <div class="card p-4 bg-secondary text-center">
                    <i class="bi bi-people-fill fs-2"></i>
                    <h5 class="mt-2">Community Forum</h5>
                </div>
            </a>
        </div>

        <!-- Feedback -->
        <div class="col-md-4 col-sm-6">
            <a href="feedback.php" class="text-decoration-none">
                <div class="card p-4 bg-dark text-center">
                    <i class="bi bi-envelope-paper fs-2"></i>
                    <h5 class="mt-2">Send Feedback</h5>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
