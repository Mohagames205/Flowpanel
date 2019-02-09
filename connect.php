<?php

$database_json = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/config/database.json");
$database = json_decode($database_json, true);
$dbhost = $database["dbhost"];
$username = $database["username"];
$password = $database["password"];
$dbname = $database["dbname"];

try {
    $handle = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>
