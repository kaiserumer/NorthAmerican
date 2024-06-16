<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="password_reset.php" method="POST">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>" required><br>
        <input type="password" name="new_password" placeholder="New Password" required><br>
        <button type="submit">Reset Password</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $token = $_POST['token'];
        $new_password = $_POST['new_password'];

        $conn = new mysqli('localhost', 'root', '', 'user_management');
        if ($conn->connect_error) {
            die('<p style="color:red;">Connection failed: ' . $conn->connect_error . '</p>');
        }

        // Validate the token and check its expiry
        $stmt = $conn->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the user's password and clear the reset token
            $stmt = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?');
            $stmt->bind_param('si', $hashed_password, $user_id);
            $stmt->execute();

            echo '<p style="color:green;">Password has been reset successfully!</p>';
        } else {
            echo '<p style="color:red;">Invalid or expired token.</p>';
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
