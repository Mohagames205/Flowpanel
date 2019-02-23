<?php
require_once("../connect.php");
$name = $_GET["name"];

$get_api = $handle->prepare("SELECT * FROM gebruikers WHERE name = :name");
$get_api->execute(["name" => $name]);
$data = $get_api->fetch(PDO::FETCH_ASSOC);
$name = $data["name"];
$balance = $data["balance"];


$array = array("name" => $name, "balance" => $balance);
$array_json = json_encode($array);

echo $array_json;
?>