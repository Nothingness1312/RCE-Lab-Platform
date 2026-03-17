<?php

$path = __DIR__ . "/data";

if(!is_dir($path)){
    mkdir($path,0777,true);
}

$db = new SQLite3($path."/db.sqlite");

$db->exec("CREATE TABLE IF NOT EXISTS users (
id INTEGER PRIMARY KEY AUTOINCREMENT,
username TEXT UNIQUE
)");

$db->exec("CREATE TABLE IF NOT EXISTS progress (
user_id INTEGER,
level INTEGER,
solved INTEGER
)");