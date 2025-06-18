<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");

// Handle delete
if (isset($_GET['delete'])) {
    $uid = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE user_id = $uid");
    header("Location: manage_users.php");
    exit;
}

// Handle activate/deactivate
if (isset($_GET['toggle'])) {
    $uid = intval($_GET['toggle']);
    $get = $conn->query("SELECT is_active FROM users WHERE user_id = $uid")->fetch_assoc();
    $new_status = $get['is_active'] ? 0 : 1;
    $conn->query("UPDATE users SET is_active = $new_status WHERE user_id = $uid");
    header("Location: manage_users.php");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Users | Zentra Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .table th, .table td {
            vertical-align: middle;
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
                <li class="nav-item"><a class="nav-link active" href="manage_users.php">Manage Users</a></li>
                <li class="nav-item"><a class="nav-link" href="view_surveys.php">View Surveys</a></li>
                <li class="nav-item"><a class="nav-link" href="view_feedback.php">View Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- USER MANAGEMENT -->
<div class="container mt-4">
    <h3 class="mb-4"><i class="bi bi-people-fill"></i> Manage Users</h3>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users->num_rows == 0): ?>
                    <tr><td colspan="8" class="text-center">No users found.</td></tr>
                <?php else: $i = 1; ?>
                    <?php while ($row = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= $row['gender'] ?: '-' ?></td>
                            <td><?= $row['dob'] ?: '-' ?></td>
                            <td>
                                <?php if ($row['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                            <td>
                                <a href="?toggle=<?= $row['user_id'] ?>" class="btn btn-sm <?= $row['is_active'] ? 'btn-warning' : 'btn-success' ?>">
                                    <i class="bi bi-toggle-<?= $row['is_active'] ? 'off' : 'on' ?>"></i> <?= $row['is_active'] ? 'Deactivate' : 'Activate' ?>
                                </a>
                                <a href="?delete=<?= $row['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
