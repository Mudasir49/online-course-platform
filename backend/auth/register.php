<?php
// backend/auth/register.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/session.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $fullName = trim($_POST['full_name']);

    if ($email && $password && $fullName) {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already registered.";
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (email, password, full_name) VALUES (?, ?, ?)");
            try {
                $stmt->execute([$email, $hashed, $fullName]);
                // Auto login or redirect to login
                header('Location: login.php?registered=1');
                exit;
            } catch (PDOException $e) {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Mini Coursera</title>
    <link rel="stylesheet" href="../../frontend/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Register</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
