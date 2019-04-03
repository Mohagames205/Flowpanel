<?php
$path = realpath("../index.php");
if($path == false){
    $path = realpath("index.php");
}
$path2 = dirname($path);
 
$database_json = file_get_contents($path2 . "\config\database.json");
$database = json_decode($database_json, true);
$dbhost = $database["dbhost"];
$dbusername = $database["username"];
$password = $database["password"];
$dbname = $database["dbname"];

try {
    $handle = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbusername, $password);
    // set the PDO error mode to exception
    $handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?>
