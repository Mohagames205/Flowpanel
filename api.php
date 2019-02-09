<?php
include("connect.php");
include("cr/includes/rank_id.inc.php");

$username = htmlspecialchars($_GET["username"]);
$get_username = $handle->prepare("SELECT * FROM user_ranks WHERE username = :username");
$get_username->execute(["username" => $username]);
$user = $get_username->fetch(PDO::FETCH_ASSOC);
$username = $user["username"];
$rank = get_rank_name($user["rank_id"]);
$array = array(["username" => $username, "rank" => $rank]);
$json = json_encode($array);

echo $json;