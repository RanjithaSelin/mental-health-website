<?php
session_start();
$conn = new mysqli("localhost", "root", "", "zentra_db");

if (isset($_POST['register'])) {
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "INSERT INTO users (full_name, email, password) VALUES ('$name', '$email', '$password')";
    if ($conn->query($sql)) {
        $success = "Registration successful! Please login.";
    } else {
        $error = "Email already exists!";
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['login_email'];
    $password = md5($_POST['login_password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $_SESSION['user_email'] = $email;
        header("Location: user_dashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Zentra Login & Register</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap + Icons + Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #9face6);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            animation: slideUp 0.5s ease-in-out;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        @keyframes slideUp {
            0% {transform: translateY(100px); opacity: 0;}
            100% {transform: translateY(0); opacity: 1;}
        }
        .form-toggle {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-end p-3">
        <a href="index.php" class="btn btn-outline-dark"><i class="bi bi-house-door-fill"></i> Home</a>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 col-md-6 col-lg-5 bg-white">
            <h3 class="text-center mb-3"><i class="bi bi-person-circle"></i> Zentra Portal</h3>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Register Form -->
            <form method="POST" id="registerForm" class="d-none">
                <div class="mb-3">
                    <label>Full Name</label>
                    <input type="text" name="full_name" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control">
                </div>
                <button class="btn btn-primary w-100" name="register"><i class="bi bi-person-plus-fill"></i> Register</button>
                <p class="text-center mt-2">Already have an account? <span class="form-toggle" onclick="toggleForms()">Login</span></p>
            </form>

            <!-- Login Form -->
            <form method="POST" id="loginForm">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="login_email" required class="form-control">
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="login_password" required class="form-control">
                </div>
                <button class="btn btn-success w-100" name="login"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                <p class="text-center mt-2">Don't have an account? <span class="form-toggle" onclick="toggleForms()">Register</span></p>
            </form>
        </div>
    </div>

    <script>
        function toggleForms() {
            document.getElementById("loginForm").classList.toggle("d-none");
            document.getElementById("registerForm").classList.toggle("d-none");
        }
    </script>
</body>
</html>
