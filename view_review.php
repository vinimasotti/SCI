<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    echo "You must be logged in to see this page";
    exit;

    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: index.php"); // Redirect to index page
        echo "Logged out";//success message
        exit;
    }
}

// Display all restaurant reviews
$result = $db->query("SELECT * FROM reviews");

while ($row = $result->fetchArray()) {
    // Vulnerable: Directly echoing user input without escaping leads to XSS
    echo "<h2>" . $row['restaurant_name'] . "</h2>";
    echo "<p><strong>Review:</strong> " . $row['review'] . "</p>";
    echo "<p><strong>Rating:</strong> " . $row['rating'] . "/5</p>";
    echo "<p><strong>By:</strong> " . $row['customer'] . "</p><hr>";
}
?>

<!-- onclick is not a good write practice because can be easy bypassed by a guessing -->
<button onclick="window.location.href='add_review.php';"> Back  </button>



