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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_name = htmlspecialchars(trim($_POST['restaurant_name']), ENT_QUOTES, 'UTF-8');  
    // added input validation to avoid Javascript or html injection
    //UTF-8 encoding refers a universal language of computers tranfering data independent of the program language

    $review = htmlspecialchars(trim($_POST['review']), ENT_QUOTES, 'UTF-8');  
    // same patter as before

    $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT, [
    //validating on server side rating 1-5
    //on the client side is 
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

<h1>Add a Restaurant Review</h1>
<form method="POST">
    Restaurant Name: <input type="text" name="restaurant_name" required><br>
    Review: <textarea name="review" required></textarea><br>
<!-- 1 to 5 is rendering on client side -->
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