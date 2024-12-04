<?php
session_start();
require 'config.php';

//add a password validation
function CheckifisValidPassword($password) {
    return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
}

// Allow customers to register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];  // No sanitization, vulnerable to XSS
    $password = $_POST['password'];  // No sanitization, password stored insecurely
    

    if (empty($username) || empty($password)) {
        echo "Username and password are required.";
        exit;
    }

    if (!CheckifisValidPassword($password)) { // user:customer1 password:12345678Aa!
        echo "Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.";
        exit;
    }

    $role = 'customer';  // Default role for all registrants there are is jsut one admin on the application id = 1

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
<br>
<br>
<button onclick="window.location.href='index.php';"> Back  </button>
