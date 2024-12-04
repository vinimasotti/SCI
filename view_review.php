<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    echo "You must be logged in to add a review!";
    exit;
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
