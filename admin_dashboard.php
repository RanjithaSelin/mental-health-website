<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");

// Fetch key counts
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
$total_moods = $conn->query("SELECT COUNT(*) FROM mood_logs")->fetch_row()[0];
$total_surveys = $conn->query("SELECT COUNT(*) FROM survey_summary")->fetch_row()[0];
$total_feedback = $conn->query("SELECT COUNT(*) FROM feedback")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Admin Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f1f4f9, #dff9fb);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .nav-link.active {
            font-weight: bold;
            color: #fff !important;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php"><i class="bi bi-shield-lock-fill"></i> Admin Panel</a>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
                <li class="nav-item"><a class="nav-link" href="view_surveys.php">View Surveys</a></li>
                <li class="nav-item"><a class="nav-link" href="view_feedback.php">View Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- DASHBOARD CONTENT -->
<div class="container mt-4">
    <h3 class="mb-4"><i class="bi bi-speedometer2"></i> Welcome, Admin</h3>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card text-center p-4 bg-primary text-white">
                <h5><i class="bi bi-people-fill fs-3"></i><br>Total Users</h5>
                <h2><?= $total_users ?></h2>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center p-4 bg-success text-white">
                <h5><i class="bi bi-emoji-smile fs-3"></i><br>Mood Logs</h5>
                <h2><?= $total_moods ?></h2>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center p-4 bg-warning text-white">
                <h5><i class="bi bi-list-check fs-3"></i><br>Surveys Taken</h5>
                <h2><?= $total_surveys ?></h2>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card text-center p-4 bg-danger text-white">
                <h5><i class="bi bi-envelope fs-3"></i><br>Feedbacks</h5>
                <h2><?= $total_feedback ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
