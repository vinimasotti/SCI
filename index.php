<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Code changed - Tampering medium risk 
    //username does not accept special characters, password yes
    //$username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');   
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
<meta http-equiv="Content-Security-Policy" content="frame-ancestors 'self';">

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

