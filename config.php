<?php

// Block all iframe embedding for anti-hijacking, yellow flag on ZAP SCAN
header("X-Frame-Options: DENY"); 
 
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
        username TEXT NOT NULL UNIQUE COLLATE NOCASE, -- Case insensitive usernames
        password TEXT NOT NULL,
        role TEXT NOT NULL CHECK (role IN ('admin', 'customer')) -- Only accept 'admin' or 'customer'
    )
");

$db->exec("
    CREATE TABLE IF NOT EXISTS reviews (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        restaurant_name TEXT NOT NULL CHECK (length(restaurant_name) >= 2 AND length(restaurant_name) <= 25), -- Limiting characters
        review TEXT NOT NULL CHECK (length(review) >= 2 AND length(review) <= 240), -- Limiting characters
        rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5), -- Ensure rating is between 1 and 5
        customer TEXT NOT NULL,
        FOREIGN KEY (customer) REFERENCES users(username) ON DELETE CASCADE -- Cascade deletes reviews if the user is deleted
    )
");

?>
