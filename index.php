<?php
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

session_start();
require 'config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Code changed - Tampering medium risk 

    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));  

    $password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING));

    //insecure code - $password = $_POST['password'];   No sanitization or password policy
    // Vulnerable to SQL Injection due to lack of prepared statements
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $db->query($sql);  // Direct SQL execution without validation

    if ($row = $result->fetchArray()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            header("Location: add_review.php");
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid username!";
    }
}
?>
<!-- head added for good practices in HTML language
 CONTENT SECURITY POLICY to not allow any user input on html language  -->
 <head>
<meta http-equiv="Content-Security-Policy" content="default-src 'self';">     
</head>


<h1>Welcome to the Review Website</h1>
<h2> Insert your username and password </h2>
<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
<br>
<h3> Dont have account? </h3>
<h4> Please Register with us now </h4>
<form action="register.php" method="GET">
    <button type="submit" value="Register"> - -  REGISTER HERE - -  </button>
</form>

