<?php
session_start();
if(isset($_SESSION["username"])){
    require("../connect.php");
    require("includes/header.inc.php");
    $lists = new Lists;
    #Getting permission ID
    $get_perm_id = $handle->prepare("SELECT perm_id FROM users WHERE username = :username");
    $get_perm_id->execute(["username" => $usernamea]);
    $perm_id = $get_perm_id->fetch(PDO::FETCH_ASSOC);
    $perm_id = $perm_id["perm_id"];
}

else{
    header("location:../index.php");
}


if(isset($_GET["functie"])){
    $functie = htmlspecialchars($_GET["functie"]);
    if($functie == "promolijst" || $functie == "ranglijst" || $functie == "werklijst"){
        $function = $functie;
    }
    else{
        header("location:home.php");
    }
}

else{
    header("location:home.php");
}


if(isset($_POST["del"])){
    
    $perm = get_perm($perm_id, "delete", 0);
    if($perm == "allow"){
        $audit_id = $_POST["del"];
        $audit_data = getAuditdata($audit_id);
        $change_slachtoffer = $audit_data["change_slachtoffer"];
        $rank_id = $audit_data["old_rank_id"];
        $rewriteRank = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
        $rewriteRank->execute(["rank_id" => $rank_id, "username" => $change_slachtoffer]);
        $delAudit = $handle->prepare("DELETE FROM audit_log WHERE audit_id = :audit_id");
        $delAudit->execute(["audit_id" => $audit_id]);
        ?> <script> alertify.success('Promotie is ongedaan gemaakt!'); </script><?php
        header("Refresh:2");
    }
    else{
        ?><script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error"); </script><?php
    }
    
}
    switch($function){
        case "promolijst":
        ?>
            <div class="tablebox">
            <table class="table table-striped table-dark table-bordered table-hover">
            <thead>
            <tr>
            <th colspan="5" class="nametable">Promoties</th>
            </tr>
            <?php
            echo $lists->getAudit("global");
            break;
        case "ranglijst":
            echo $lists->getRanks();
            break;
        case "werklijst":
            echo $lists->getWerkers();
            break;

    }
?>


</body>
</html>