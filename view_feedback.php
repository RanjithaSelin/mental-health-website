<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");

// Fetch all feedback
$feedbacks = $conn->query("SELECT f.*, u.full_name, u.email FROM feedback f JOIN users u ON f.user_id = u.user_id ORDER BY submitted_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Feedback | Zentra Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f3f6fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.05);
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
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
                <li class="nav-item"><a class="nav-link" href="view_surveys.php">View Surveys</a></li>
                <li class="nav-item"><a class="nav-link active" href="view_feedback.php">View Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- FEEDBACK CONTENT -->
<div class="container mt-4">
    <div class="card p-4">
        <h3 class="mb-4"><i class="bi bi-envelope"></i> User Feedback</h3>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Submitted On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($feedbacks->num_rows == 0): ?>
                        <tr><td colspan="5" class="text-center">No feedback found.</td></tr>
                    <?php else: $i = 1; ?>
                        <?php while ($row = $feedbacks->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                                <td><?= date("d M Y h:i A", strtotime($row['submitted_on'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
