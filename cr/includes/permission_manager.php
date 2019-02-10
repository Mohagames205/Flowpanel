<?php

function get_perm($perm_id, $change_type, $rank_id){
    #permissions voor promotie
    
    $demprowa = array(2, 3, 4);
    if($change_type == "Promotie"){
        if($perm_id == 1){
            $perm = "deny";
            return $perm;
        }
        if(in_array($perm_id, $demprowa)){
            if($rank_id <= 5){
                $perm = "allow";
                return $perm;
            }
            else{
                $perm = "deny";
                return $perm;
            }
        }
        if($perm_id == 5 AND $rank_id <= 6){
            $perm = "allow";
            return $perm;
        }
        else{
            $perm = "deny";
            return $perm;
        }
    }
    #Permissions voor degra
    
    if($change_type == "Degradatie"){
        if($perm_id >= 3 AND $rank_id >= 3 AND $rank_id <= 6){
            $perm = "allow";
            return $perm;
        }
        else{
            $perm = "deny";
            return $perm;
        }
    }
    if($change_type == "Ontslag"){
        if($perm_id >= 4 AND $rank_id >= 2 AND $rank_id <= 6){
            $perm = "allow";
            return $perm;
        }
        else{
            $perm = "deny";
            return $perm;
        }
    }

    

}