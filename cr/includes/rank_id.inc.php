<?php

function get_rank_name($id){
    include("../connect.php");
    $rank_name_query = $handle->prepare("SELECT rank_name FROM ranks WHERE rank_id = :id");
    $rank_name_query->execute(["id" => $id]);
    $rank_name_query = $rank_name_query->fetch(PDO::FETCH_ASSOC);
    $rank_name = $rank_name_query["rank_name"];
    return $rank_name;
}