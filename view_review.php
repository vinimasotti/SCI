<?php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    echo "You must be logged in to see this page";
    exit;

}

$username = $_SESSION['username'];
$user_role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review_id'])) {
    $review_id = intval($_POST['delete_review_id']);
    
    // Check if the user is allowed to delete this review
    $stmt = $db->prepare("SELECT customer FROM reviews WHERE id = :id");
    $stmt->bindValue(':id', $reviw_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $review = $result->fetchArray(SQLITE3_ASSOC);

    //adding delete button admin can delete any review
    //otherwise just the owner of the review can delete 
    if ($review) {
        if ($user_role === 'admin' || $review['customer'] === $username) {
            $delete_stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
            $delete_stmt->bindValue(':id', $review_id, SQLITE3_INTEGER);
            $delete_stmt->execute();
            echo "Review deleted successfully.";
        } else {
            echo "You do not have permission to delete this review.";
        }
    } else {
        echo "Review not found.";
    }
}

// Display all restaurant reviews
$result = $db->query("SELECT * FROM reviews");

while ($row = $result->fetchArray()) {
    // UTF-8 implemented and htmlspecialchar to not fetch any malicious inputc
    echo "<h2>" . htmlspecialchars($row['restaurant_name'], ENT_QUOTES, 'UTF-8') . "</h2>";
    echo "<p><strong>Review:</strong> " . $row['review'] . "</p>";
    echo "<p><strong>Rating:</strong> " . $row['rating'] . "/5</p>";
    echo "<p><strong>By:</strong> " . htmlspecialchars($row['customer'], ENT_QUOTES, 'UTF-8') . "</p><hr>";
    // add button
    if ($user_role === 'admin' || $row['customer'] === $username) {
        echo "<form method='POST' style='display:inline;'>
                <input type='hidden' name='delete_review_id' value='" . intval($row['id']) . "'>
                <button type='submit'>Delete</button>
              </form>";
    }
    echo "<hr>";
}
?>
<!-- head added for good practices in HTML language
 CONTENT SECURITY POLICY to not allow any user input on html language  -->
<!-- onclick is not a good write practice because can be easy bypassed by a guessing -->
<button onclick="window.location.href='add_review.php';"> Back  </button>



