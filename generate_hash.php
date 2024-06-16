<?php
$password = 'your_new_password';
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
echo $hashed_password;
?>
