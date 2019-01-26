<?php



function write_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank, $change_date){
    $path = $_SERVER['DOCUMENT_ROOT'] . "/kaas.php";
    include("$path");
    $auditquery = $handle->prepare("INSERT INTO audit_log VALUES(:changer, :change_type, :change_slachtoffer, :old_rank_id, :new_rank_id, audit_id, :change_date)");
    $auditquery->execute(["changer" => $changer, "change_type" => $change_type, "change_slachtoffer" => $change_slachtoffer, "old_rank_id" => $old_rank, "new_rank_id" => $new_rank, "change_date" => $change_date]);
}

?>