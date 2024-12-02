<?php
class MyDB extends SQLite3 {
    function __construct() {
        $this->open('restaurant_reviews.db');  // Database file name
    }
}

$db = new MyDB();

if(!$db) {
    echo $db->lastErrorMsg();
} else {
    echo "Database opened successfully\n";
}

// Create users table
$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT NOT NULL
    )
");

// Create reviews table
$db->exec("
    CREATE TABLE IF NOT EXISTS reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        restaurant_name TEXT NOT NULL,
        review TEXT NOT NULL,
        rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
        customer TEXT NOT NULL,
        FOREIGN KEY (customer) REFERENCES users(username) ON DELETE CASCADE
    )
");

?>
