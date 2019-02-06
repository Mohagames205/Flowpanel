<?php



function rank_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank, $reason, $change_date){
    include("../connect.php");
    $auditquery = $handle->prepare("INSERT INTO audit_log VALUES(:changer, :change_type, :change_slachtoffer, :old_rank_id, :new_rank_id, :reason, audit_id, :change_date)");
    $auditquery->execute(["changer" => $changer, "change_type" => $change_type, "change_slachtoffer" => $change_slachtoffer, "old_rank_id" => $old_rank, "new_rank_id" => $new_rank,"reason" => $reason, "change_date" => $change_date]);
}


?>