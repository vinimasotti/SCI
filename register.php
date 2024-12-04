<?php
session_start();
require 'config.php';

// Allow customers to register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];  // No sanitization, vulnerable to XSS
    $password = $_POST['password'];  // No sanitization, password stored insecurely
    $role = 'user';  // Default role for all registrants

    // Hash the password (better practice but still missing rate limits and password complexity checks)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Vulnerable to SQL Injection, as no prepared statements are used
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
    $db->exec($sql);  // Direct SQL execution with user input

    echo "Customer registered successfully!";
}
?>

<h1>Register as a Customer</h1>
<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Register">
</form>
