<?php
//CSP settled to default
	header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self';");
    header("X-content-Type-Options: nosniff");
//cookie managament
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict'); // mitigating against cross-site forgery

session_set_cookie_params([
    'lifetime' => time() +3000, //expire in 3000 seconds or when browser close
    'path' => '/',   //default path
    'domain' => '/',
    'secure' => false, //not using HTTPS
    'httponly' => true,
    'samesite' => 'strict', //samesite policy

]);

setcookie(
    'test_cookie',
    'test_value',
    [
        'expires' => time () + 3000,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
    ]
    );
    
require 'config.php';

//add a password validation
function CheckifisValidPassword($password) {
    return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
}

// Allow customers to register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // No sanitization, vulnerable to XSSsuper
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING)); 
    if (strlen($username) < 2 || strlen($username) > 25) {
        die("Username must be between 2 and 25 characters.");
    } 
    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));
  // Minimum 8 letters, special character and upper case

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
<!-- head added for good practices in HTML language
 CONTENT SECURITY POLICY to not allow any user input on html language  -->
 <head>
<meta http-equiv="Content-Security-Policy" content="default-src 'self';">
<meta http-equiv="Content-Security-Policy" content="frame-ancestors 'self';">

</head>

<h1>Register as a Customer</h1>
<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Register">
</form>
<br>
<br>
<!-- not good code <button onclick="window.location.href='index.php';"> Back  </button> -->
 
<a href="index.php">
    <button type="button">Back</button>
</a>

