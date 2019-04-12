<?php

function get_rank_name($id){
    include("../connect.php");
    $rank_name_query = $handle->prepare("SELECT rank_name FROM ranks WHERE rank_id = :id");
    $rank_name_query->execute(["id" => $id]);
    $rank_name_query = $rank_name_query->fetch(PDO::FETCH_ASSOC);
    $rank_name = $rank_name_query["rank_name"];
    return $rank_name;
}

function get_dep_name($id){
    include("../connect.php");
    $afdeling_name_query = $handle->prepare("SELECT afdeling_name FROM afdelingen WHERE afdeling_id = :id");
    $afdeling_name_query->execute(["id" => $id]);
    $afdeling_name_query = $afdeling_name_query->fetch(PDO::FETCH_ASSOC);
    $afdeling_name = $afdeling_name_query["afdeling_name"];
    return $afdeling_name;
}