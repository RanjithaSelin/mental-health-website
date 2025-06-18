<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");

// Fetch all surveys
$surveys = $conn->query("SELECT s.*, u.full_name, u.email FROM survey_summary s JOIN users u ON s.user_id = u.user_id ORDER BY s.submitted_on DESC");

// Fetch data for charts
$chartData = $conn->query("SELECT result, COUNT(*) as count FROM survey_summary GROUP BY result");
$chartResults = [];
while ($row = $chartData->fetch_assoc()) {
    $chartResults[$row['result']] = $row['count'];
}

// Prepare labels and counts for Chart.js
$labels = json_encode(array_keys($chartResults));
$counts = json_encode(array_values($chartResults));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Survey Results | Zentra Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: #eef2f7;
            font-family: 'Segoe UI', sans-serif;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .chart-card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background: #fff;
            padding: 1rem;
        }
    </style>
</head>
<body>

<!-- ADMIN NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php"><i class="bi bi-shield-lock-fill"></i> Admin Panel</a>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
                <li class="nav-item"><a class="nav-link active" href="view_surveys.php">View Surveys</a></li>
                <li class="nav-item"><a class="nav-link" href="view_feedback.php">View Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- DASHBOARD -->
<div class="container mt-4">
    <h3 class="mb-4"><i class="bi bi-list-check"></i> Survey Submissions</h3>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="chart-card text-center">
                <h5 class="mb-3">Survey Results (Pie Chart)</h5>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-card text-center">
                <h5 class="mb-3">Survey Results (Bar Chart)</h5>
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Survey Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Survey Result</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($surveys->num_rows == 0): ?>
                    <tr><td colspan="5" class="text-center">No survey submissions found.</td></tr>
                <?php else: $i = 1; ?>
                    <?php while ($row = $surveys->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['result']) ?></span></td>
                            <td><?= date("d M Y h:i A", strtotime($row['submitted_on'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- CHART SCRIPT -->
<script>
const labels = <?= $labels ?>;
const data = <?= $counts ?>;

new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Survey Distribution',
            data: data,
            backgroundColor: ['#42a5f5', '#66bb6a', '#ffa726', '#ef5350']
        }]
    }
});

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Survey Counts',
            data: data,
            backgroundColor: '#42a5f5'
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
