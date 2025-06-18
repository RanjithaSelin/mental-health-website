<?php
session_start();
if (!isset($_SESSION['admin_username'])) {
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "zentra_db");
$admin = $conn->query("SELECT * FROM admins WHERE username='{$_SESSION['admin_username']}'")->fetch_assoc();
$admin_id = $admin['admin_id'];
$msg = "";

// Handle Add Resource
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['title'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $desc = $conn->real_escape_string($_POST['description']);
    $type = $conn->real_escape_string($_POST['type']);
    $url = $conn->real_escape_string($_POST['url']);

    if ($title && $desc && $type && $url) {
        $conn->query("INSERT INTO resources (title, description, type, url, uploaded_by) 
                      VALUES ('$title', '$desc', '$type', '$url', $admin_id)");
        $msg = "Resource added successfully!";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $rid = intval($_GET['delete']);
    $conn->query("DELETE FROM resources WHERE resource_id = $rid");
    header("Location: manage_resources.php");
    exit;
}

// Get resources
$resources = $conn->query("SELECT r.*, a.username FROM resources r JOIN admins a ON r.uploaded_by = a.admin_id ORDER BY r.uploaded_on DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Resources | Zentra Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background: #eef2f7;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 12px rgba(0,0,0,0.05);
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
                <li class="nav-item"><a class="nav-link" href="view_surveys.php">View Surveys</a></li>
                <li class="nav-item"><a class="nav-link" href="view_feedback.php">View Feedback</a></li>
                <li class="nav-item"><a class="nav-link active" href="manage_resources.php">Resources</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">
    <h3 class="mb-4"><i class="bi bi-journal-richtext"></i> Manage Mental Health Resources</h3>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <!-- Resource Form -->
    <div class="card p-4 mb-4">
        <h5><i class="bi bi-plus-circle-fill"></i> Add New Resource</h5>
        <form method="POST">
            <div class="mb-2">
                <input type="text" name="title" required class="form-control" placeholder="Resource Title" />
            </div>
            <div class="mb-2">
                <textarea name="description" rows="3" required class="form-control" placeholder="Short Description"></textarea>
            </div>
            <div class="mb-2">
                <select name="type" required class="form-select">
                    <option value="">-- Select Type --</option>
                    <option value="Article">Article</option>
                    <option value="Video">Video</option>
                    <option value="Tool">Tool</option>
                </select>
            </div>
            <div class="mb-2">
                <input type="url" name="url" required class="form-control" placeholder="Resource URL (https://...)" />
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Upload Resource</button>
        </form>
    </div>

    <!-- Resource List -->
    <div class="card p-4">
        <h5 class="mb-3"><i class="bi bi-collection"></i> Uploaded Resources</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>URL</th>
                        <th>Uploaded By</th>
                        <th>Uploaded On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resources->num_rows == 0): ?>
                        <tr><td colspan="8" class="text-center">No resources found.</td></tr>
                    <?php else: $i = 1; ?>
                        <?php while ($row = $resources->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= $row['type'] ?></span></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><a href="<?= $row['url'] ?>" target="_blank">Open</a></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= date("d M Y h:i A", strtotime($row['uploaded_on'])) ?></td>
                                <td>
                                    <a href="?delete=<?= $row['resource_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this resource?')">
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
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
