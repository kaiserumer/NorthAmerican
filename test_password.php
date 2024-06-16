<?php
$hashed_password = '$2y$10$CrIa8wlcWLvBdg8ZmHybQuj2EC1pEXTi2.YWjjG5klB2qBggF.Uo6';
$password = 'newpassword';

if (password_verify($password, $hashed_password)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
?>
