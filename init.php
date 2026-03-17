<?php

$path = __DIR__ . "/data";

// Set umask to allow full permissions
$old_umask = umask(0);

// Check if directory exists, if not create it
if(!is_dir($path)){
    if(!@mkdir($path, 0777, true)){
        umask($old_umask);
        error_log("ERROR: Cannot create data directory at: " . $path);
        die("Error: Cannot create data directory at " . $path . ". Check file permissions on host.");
    }
}

// Force chmod to ensure writable
@chmod($path, 0777);

// Restore umask
umask($old_umask);

// Ensure directory is writable
if(!is_writable($path)){
    error_log("ERROR: Data directory is not writable: " . $path . " (permissions: " . substr(sprintf('%o', fileperms($path)), -4) . ")");
    die("Error: Data directory at " . $path . " is not writable. Current permissions: " . substr(sprintf('%o', fileperms($path)), -4));
}

// Initialize SQLite database
try {
    $db_path = $path."/db.sqlite";
    $db = new SQLite3($db_path);
    
    if(!$db){
        throw new Exception("Failed to create/open SQLite database at " . $db_path);
    }
    
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
    error_log("DATABASE ERROR: " . $e->getMessage());
    die("Database Error: " . $e->getMessage());
}