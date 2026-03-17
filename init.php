<?php

$path = __DIR__ . "/data";

// Create data directory with error handling
if(!is_dir($path)){
    if(!mkdir($path, 0777, true)){
        die("Error: Cannot create data directory. Check permissions.");
    }
}

// Initialize SQLite database
try {
    $db = new SQLite3($path."/db.sqlite");
    
    // Create tables
    $db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE
    )");
    
    $db->exec("CREATE TABLE IF NOT EXISTS progress (
    user_id INTEGER,
    level INTEGER,
    solved INTEGER
    )");
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}