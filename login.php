<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];  //  Vulnerable to SQL Injection
    $password = $_POST['password'];  // No sanitization or password policy
      //  $password = htmlspecialchars ($_POST['password'], ENT_QUOTES, 'UTF-8') 
      //  $username = htmlspecialchars ($_POST['password'], ENT_QUOTES, 'UTF-8')
        
        //
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

<h1>Login </h1>
<form method="POST">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>
