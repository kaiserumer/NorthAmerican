<?php
$conn = new mysqli('localhost', 'root', '', 'user_management');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$username = 'admin';
$email = 'admin@example.com';
$password = password_hash('your_password', PASSWORD_BCRYPT); // Replace 'your_password' with the desired password
$role = 'admin';

$stmt = $conn->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $username, $email, $password, $role);

if ($stmt->execute()) {
    echo 'Admin user created successfully';
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>
