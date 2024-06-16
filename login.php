<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        echo "Form submitted.<br>";
        echo "Submitted Username: $username<br>";
        echo "Submitted Password (raw): $password<br>";

        $conn = new mysqli('localhost', 'root', '', 'user_management');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $stmt = $conn->prepare('SELECT id, password FROM users WHERE username = ?');
        if (!$stmt) {
            die('Prepare failed: ' . $conn->error);
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        echo "Database ID: $id<br>";
        echo "Hashed Password from DB: $hashed_password<br>";
        echo "Password verification: " . (password_verify($password, $hashed_password) ? 'true' : 'false') . "<br>";

        if ($stmt->num_rows > 0) {
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                echo 'Login successful!<br>';
                echo 'Session User ID: ' . $_SESSION['user_id'] . '<br>';
                header('Location: profile.php');
                exit();
            } else {
                echo 'Password verification failed.';
            }
        } else {
            echo 'Username not found.';
        }

        $stmt->close();
        $conn->close();
    } else {
        session_unset();
    }
    ?>
</body>
</html>
