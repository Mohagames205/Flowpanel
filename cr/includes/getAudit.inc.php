<?php

function getAuditdata($audit_id){
    include("../connect.php");
    $getAudit = $handle->prepare("SELECT * FROM audit_log WHERE audit_id = :audit_id");
    $getAudit->execute(["audit_id" => $audit_id]);
    $audit_data = $getAudit->fetch(PDO::FETCH_ASSOC);
    return $audit_data;
}