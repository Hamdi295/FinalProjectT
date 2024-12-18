<?php
session_start();

// Only allow access to register.php if the user is not logged in
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit(); // Redirect to home page if the user is already logged in
}

require_once '../app/Controllers/AuthController.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();
    $error = $authController->register($_POST['username'], $_POST['password'], $_POST['role']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="role">Role:</label>
        <select name="role" id="role">
            <option value="Admin">Admin</option>
            <option value="Editor">Editor</option>
            <option value="Viewer">Viewer</option>
        </select>
        <br>
        <button type="submit">Register</button>
    </form>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <a href="login.php">
        <button>Go to Login</button>
    </a>
</body>
</html>
