<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    echo "You must be logged in to see this page";
    exit;
}
 // Logoutout button add good practice functionality for the user session
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php"); // Redirect to index page
    echo "Logged out";//success message
    exit;
}

// Implemented a session timeout
$timeout_session = 20; // 5 minutes = 300 seconds
if (isset($_SESSION['last_activity'])) {
    // Calculate the time since the last activity
    $elapsed_time = time() - $_SESSION['last_activity'];
    if ($elapsed_time > $timeout_session) {
        // If the timeout duration has passed, destroy the session
        session_unset();
        session_destroy();
        header("Location: index.php"); // Redirect to index page
        exit();
    }
}

// Update last activity time stamp
$_SESSION['last_activity'] = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_name = htmlspecialchars(trim($_POST['restaurant_name']), ENT_QUOTES, 'UTF-8');  
    // added input validation to avoid Javascript or html injection
    //UTF-8 encoding refers a universal language of computers tranfering data independent of the program language

    $review = htmlspecialchars(trim($_POST['review']), ENT_QUOTES, 'UTF-8');  
    // same pattern as before

    //blocking from server side empty inputs and limiting maximum characters
    if (!preg_match('/^[^\s]{2,25}$/', $restaurant_name)) {
        echo "Restaurant name must be between 2 and 25 characters and cannot contain spaces.";
        exit;
    }
    if (strlen($review) < 2 || strlen($review) > 240 || empty($review)) {
        echo "Review must be between 2 and 240 characters.";
        exit;
    }
        //validating on server side rating 1-5, wrong inputs wont be on the db
    $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT, [
        'options' => [
            'min_range' => 1,
            'max_range' => 5,
        ]
    ]);  

    // Vulnerable: SQL Injection risk due to lack of prepared statements
    $sql = "INSERT INTO reviews (restaurant_name, review, rating, customer) VALUES ('$restaurant_name', '$review', '$rating', '".$_SESSION['username']."')";
    $db->exec($sql);  // Direct SQL execution with unsanitized user input
    echo "Review added!";
}
?>
<!-- head added for good practices in HTML language
 CONTENT SECURITY POLICY to not allow any user input on html language  -->
<head>
<meta http-equiv="Content-Security-Policy" content="default-src 'self';">
</head>
<!-- head added CONTENT SECURITY POLICY  -->

<h1>Add a Restaurant Review</h1>
<form method="POST">
    <!-- limiting the max user input to not flood -->
    Restaurant Name: <input type="text" name="restaurant_name" minlength="2" maxlength="25" required><br>
     <!-- blocking white space submits input to not enter space on databases -->
   <pattern=".*\S.*" title="Cannot be blank or only spaces" required><br>

    Review: <textarea name="review" minlength="5" maxlength="240" required></textarea><br>
    <pattern=".*\S.*" title="Cannot be blank or only spaces" required></textarea><br>

<!-- boundary 1 to 5 is rendering on client side - applied on server side as well-->
    Rating: <input type="number" name="rating" min="1" max="5" required><br>
    <input type="submit" value="Submit Review">
</form>
<h2> Want to see the reviews?  </h2>
<h3> Please click on the button below </h3>
<form action="view_review.php" method="GET">
    <button type="submit" value="reviews"> -- VIEW REVIEWS --</button>
</form>

<!-- user botton to logout the application -->
<h4> To logout click below <h4>
<form method="GET" action="">
    <button type="submit" name="logout" value="1">-- LOG OUT HERE --</button>
</form>