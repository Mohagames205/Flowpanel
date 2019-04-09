<html>
<head>
<meta charset="utf-8" />
    
    <title>CakeRankings - Staff</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <!-- alertify -->
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/themes/default.min.css"/>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/themes/semantic.min.css"/>
    <link rel="stylesheet" type="text/css" href="style/main.css"/>
    <style>
.no-js #loader { display: none;  }
.js #loader { display: block; position: absolute; left: 100px; top: 0; }
.se-pre-con {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url("../foto/loader.gif") center no-repeat #fff;
}
</style>
<script>
$(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");;
	});
</script>
</head>
<body>
<?php
include("../connect.php");
include("includes/audit.inc.php");
include("includes/rank_id.inc.php");
include("includes/color.inc.php");
include("includes/permission_manager.php");
include("includes/audit_table.inc.php");
include("includes/getAudit.inc.php");
include("includes/lijsten.class.php");
session_start();
if(isset($_SESSION["username"])){
    $lists = new Lists;
    $usernamea = $_SESSION["username"];
    $bericht = "Hey $usernamea!";
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

?>
<div class="name">
        <a href="home.php">⌂ Home </a>
        <?php echo "<p id='a'> $bericht </p>" ?>
    </div>
<?php 
    require_once("includes/navbar.inc.php");
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