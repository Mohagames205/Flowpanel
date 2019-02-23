<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once("../connect.php");

$name = $_GET["name"];
$balance = $_GET["balance"];

$search_user = $handle->prepare("SELECT * FROM gebruikers WHERE name = :name");
$search_user->execute(["name" => $name]);

$count = $search_user->rowCount();

if($count > 0){
    $edit = $handle->prepare("UPDATE gebruikers SET balance = :balance WHERE name = :name");
    $edit->execute(["balance" => $balance, "name" => $name]);
}
else{
    $verstuur = $handle->prepare("INSERT INTO gebruikers VALUES(id, :balance, :name)");
    $verstuur->execute(["balance" => $balance, "name" => $name]);
}


?>