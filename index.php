<?php
session_start();
$isAdmin = false;

if (isset($_SESSION['user_id'])) {
    $conn = new mysqli('localhost', 'root', '', 'user_management');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    $stmt = $conn->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    $isAdmin = ($role == 'admin');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Project</title>
</head>
<body>
    <nav>
        <a href="profile.php">Profile</a>
        <?php if ($isAdmin): ?>
            <a href="admin.php">Admin Page</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>

    <h1>Welcome to My Project</h1>
    <!-- Rest of your page content -->
</body>
</html>
