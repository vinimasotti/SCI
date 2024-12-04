<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    echo "You must be logged in to add a review!";
    exit;
}

if (isset($_GET['logout'])) { // Logout button functionality
    session_destroy();
    
    header("Location: index.php"); // Redirect to index page
    echo "Logged out of Review";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_name = $_POST['restaurant_name'];  // No input validation

    $review = $_POST['review'];  // Vulnerable to XSS as no sanitization is done

    $rating = $_POST['rating'];  // No validation, possible SQL injection

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
    Rating: <input type="number" name="rating" min="1" max="5" required><br>
    <input type="submit" value="Submit Review">
</form>


<h2> Want to see the reviews?  </h2>
<h3> Please click on the button below </h3>
<form action="view_review.php" method="GET">
    <button type="submit" value="reviews"> -- VIEW REVIEWS --</button>
</form>

<h4> To logout click below <h4>
<form method="GET" action="">
    <button type="submit" name="logout" value="1">-- LOG OUT HERE --</button>
</form>