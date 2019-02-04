<?php
function get_color($change_type){
    if($change_type == "Ontslag"){
        $tstat = "red";
      }
      elseif($change_type == "Promotie"){
        $tstat = "green";
      }
      elseif($change_type == "Degradatie"){
        $tstat = "orange";
      }
      elseif($change_type == "Custom"){
        $tstat = "purple";
    }
      else{
          $tstat = NULL;
      }
    return $tstat;
}