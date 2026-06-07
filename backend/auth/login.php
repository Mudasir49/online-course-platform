<?php
// backend/auth/login.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/session.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT id, password, full_name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: ../dashboard.php');
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Mini Coursera</title>
    <link rel="stylesheet" href="../../frontend/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login</h2>
        <?php if (isset($_GET['registered'])): ?>
            <p class="success">Registration successful! Please login.</p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" value="student@example.com" required>
            <input type="password" name="password" placeholder="Password" value="Password123!" required>
            <button type="submit">Login</button>
        </form>
        <p>No account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>
