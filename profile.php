<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Debugging session
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'user_management');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $new_email = $_POST['email'];

        // Update email
        $stmt = $conn->prepare('UPDATE users SET email = ? WHERE id = ?');
        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param('si', $new_email, $user_id);
        if ($stmt->execute()) {
            $message = 'Email updated successfully!';
        } else {
            $message = 'Failed to update email.';
        }
        $stmt->close();
    }

    if (isset($_POST['password'])) {
        $new_password = $_POST['password'];
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password
        $stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param('si', $hashed_password, $user_id);
        if ($stmt->execute()) {
            $message = 'Password updated successfully!';
        } else {
            $message = 'Failed to update password.';
        }
        $stmt->close();
    }
}

// Fetch current user details
$stmt = $conn->prepare('SELECT username, email FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($username, $email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
</head>
<body>
    <h2>Profile</h2>
    <p>Username: <?php echo htmlspecialchars($username); ?></p>
    <form action="profile.php" method="POST">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>
        
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Update Profile</button>
    </form>
    <p><?php echo $message; ?></p>
</body>
</html>
