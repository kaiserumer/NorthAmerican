<!DOCTYPE html>
<html>
<head>
    <title>Password Reset</title>
</head>
<body>
    <h2>Password Reset</h2>
    <form action="password_reset_request.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required><br>
        <button type="submit">Send Reset Link</button>
    </form>

    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];

        $conn = new mysqli('localhost', 'root', '', 'user_management');
        if ($conn->connect_error) {
            die('<p style="color:red;">Connection failed: ' . $conn->connect_error . '</p>');
        } else {
            echo '<p style="color:green;">Connection successful!</p>';
        }

        // Simple query to check the connection
        $result = $conn->query("SELECT DATABASE()");
        if ($result) {
            $row = $result->fetch_row();
            echo '<p style="color:green;">Connected to database: ' . $row[0] . '</p>';
        } else {
            echo '<p style="color:red;">Query failed: ' . $conn->error . '</p>';
        }

        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        if (!$stmt) {
            die('<p style="color:red;">Prepare failed: ' . $conn->error . '</p>');
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // Generate a unique token
            $token = bin2hex(random_bytes(50));
            $stmt->close();

            // Store the token in the database
            $stmt = $conn->prepare('UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?');
            if (!$stmt) {
                die('<p style="color:red;">Prepare failed: ' . $conn->error . '</p>');
            }
            $stmt->bind_param('ss', $token, $email);
            $stmt->execute();

            // Send the reset link via email
            $reset_link = "http://localhost/my_project/password_reset.php?token=$token";
            
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.example.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@example.com'; // SMTP username
                $mail->Password = 'your-email-password'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('your-email@example.com', 'Mailer');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Click <a href='$reset_link'>here</a> to reset your password.";

                $mail->send();
                echo '<p style="color:green;">Password reset link has been sent to your email.</p>';
            } catch (Exception $e) {
                echo '<p style="color:red;">Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '</p>';
            }
        } else {
            echo '<p style="color:red;">Email not found.</p>';
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
