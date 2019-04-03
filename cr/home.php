<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    
    <title>CakeRankings - Staff</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
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

<div class="se-pre-con"></div>
<?php
session_start();
include("../connect.php");
include("includes/audit.inc.php");
include("includes/rank_id.inc.php");
include("includes/color.inc.php");
include("includes/permission_manager.php");
include("includes/audit_table.inc.php");
include("includes/getAudit.inc.php");

if(isset($_SESSION["username"])){
    $usernamea = $_SESSION["username"];
    $bericht = "Welkom $usernamea";
    #Getting permission ID
    $get_perm_id = $handle->prepare("SELECT perm_id FROM users WHERE username = :username");
    $get_perm_id->execute(["username" => $usernamea]);
    $perm_id = $get_perm_id->fetch(PDO::FETCH_ASSOC);
    $perm_id = $perm_id["perm_id"];
}

else{
    header("location:../index.php");
}

if(isset($_POST["logout"])){
  session_destroy();
  header("location:../index.php");
}

if(isset($_GET["naam"])){
    $page = 1;
    $naam = htmlspecialchars($_GET["naam"]);
    $usernamequery = $handle->prepare("SELECT username FROM user_ranks WHERE username = :naam");
    $us = $usernamequery->execute(["naam" => $naam]);
    $username = $usernamequery->fetch(PDO::FETCH_ASSOC);
    
    if(!empty($username)){
        $user = "EX";
        $username = $username["username"];
        $rank_id_query = $handle->prepare("SELECT rank_id, node FROM user_ranks WHERE username = :username");
        $rank_id_query->execute(["username" => $username]);
        $userdata = $rank_id_query->fetch(PDO::FETCH_ASSOC);
        $rank_id = $userdata["rank_id"];
        $rank = get_rank_name($rank_id);
        $node = $userdata["node"];
        #warns ophalen
        $get_scope_warns = $handle->prepare("SELECT * FROM warns WHERE gewaarschuwde = :username");
        $get_scope_warns->execute(["username" => $username]);

        #node system
        $node = "soon";
        
    }
    else{
        $username = $naam;
        $rank = "Niet werkzaam";
        $node = "De gebruiker bestaat niet.";
        $user = "DNEX";
        $rank_id = 0;
    }
}

else{
    $page = 0;
}

if(isset($_POST["warn"])){
    $_SESSION["warned"] = htmlspecialchars($_POST["warn"]);
    $_SESSION["warner"] = $usernamea;
    header("location:includes/warn.php");

}
if(isset($_POST["del"])){
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

if(isset($_POST["promote"])){
    $reason = htmlspecialchars($_POST["reason"]);
    $change_date = date('d/m/Y');
    $change_slachtoffer = $username;
    $change_type = "Promotie";
    $changer = $usernamea;
    $perm = get_perm($perm_id, $change_type, $rank_id);
    echo $perm;
    if($user == "DNEX"){
        if($perm == "allow"){
            $userinsert = $handle->prepare("INSERT INTO user_ranks (username, rank_id, node) VALUES(:username, :rank_id, :node)");
            $userinsert->execute(["username" => $username, "rank_id" => 2, "node" => "B"]);
            $new_rank = 2;
            rank_audit($changer, $change_type, $change_slachtoffer, $rank_id, $new_rank,$reason, $change_date);
            header("Refresh:0");
        }
        #PDNEX
        else{
            ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error"); </script> <?php
        }
        
    }
    #PEX
    elseif($perm == "allow"){
        $new_rank_id = $rank_id + 1;
        $new_rank = $new_rank_id;
        $old_rank = $rank_id;
        $userpromote = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
        $userpromote->execute(["rank_id" => $new_rank_id, "username" => $username]);
        rank_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank,$reason, $change_date);
        header("Refresh:0");
    }

    else{
        ?>
        <script>
            swal("No permission", "You don't have the appropriate permissions to complete this action.", "error");
            </script>
        <?php
    }

}

if(isset($_POST["demote"])){
    $reason = htmlspecialchars($_POST["reason"]);
    $change_date = date('d/m/Y');
    $change_slachtoffer = $username;
    $change_type = "Degradatie";
    $changer = $usernamea;
    $perm = get_perm($perm_id, $change_type, $rank_id);
    if($user == "DNEX"){
        ?>
            <script>
                swal("Error", "Deze gebruiker kan geen degradatie ontvangen!", "error");
            </script>
            <?php
    }
    #DEX
    else{
        if($rank_id > 1){
            if($perm == "allow"){
                $old_rank = $rank_id;
                $new_rank_id = $rank_id - 1;
                $userpromote = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
                $userpromote->execute(["rank_id" => $new_rank_id, "username" => $username]);
                rank_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank_id,$reason, $change_date);
                header("Refresh:0");
            }
            else{
                ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error"); </script> <?php
            }
            
        }
        else{
            ?>
            <script>
                swal("Error", "Deze gebruiker kan geen degradatie ontvangen!", "error");
                </script>
            <?php
        }
    }
}


if(isset($_POST["ontslag"])){
    $reason = htmlspecialchars($_POST["reason"]);
    $change_date = date('d/m/Y');
    $change_slachtoffer = $username;
    $change_type = "Ontslag";
    $changer = $usernamea;
    $perm = get_perm($perm_id, $change_type, $rank_id);
    if($user == "DNEX"){
        ?>
            <script>
                swal("Error", "Deze gebruiker kan geen ontslag ontvangen!", "error");
                </script>
            <?php
    }
    if($perm == "allow"){
        $old_rank = $rank_id;
        $userpromote = $handle->prepare("DELETE FROM user_ranks WHERE username = :username");
        $userpromote->execute(["username" => $username]);
        rank_audit($changer, $change_type, $change_slachtoffer, $old_rank, 0,$reason, $change_date);
        header("Refresh:0");
    }
    else{
        ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error"); </script> <?php
    }
}

if(isset($_POST["custom"])){
    $perm = get_perm($perm_id, "custom", $rank_id);
    if($perm == "allow"){
        $_SESSION["custom"] = $username;
        $_SESSION["reason"] = htmlspecialchars($_POST["reason"]);
        header("location:custom.php");
    }
    else{
        ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error"); </script> <?php
    }
    
}
?>

    <div class="name">
    <a href="home.php">⌂ Home </a>
    <?php echo "<p id='a'> $bericht </p>" ?>
    <form method="POST">
        <button name="logout" class="btn btn-outline-light">Uitloggen</button>
        <a role="button" class="btn btn-outline-light" href="profile.php">Profiel</a>
    </form>
</div>


<?php 
#start van Page 0
if($page == 0){
?>
<div class='search'>
    <form method='GET'>
    <p><b>Username</b></p>
    <input type='text' name='naam' id='name' placeholder='Username'>
    </form>
</div>
<div class="claimsysteem">
<p>soon</p>

</div> <!-- einde claimsys div -->
<div class="tablebox">
<table class="table table-striped table-dark table-bordered table-hover">
<thead>
<tr>
<th colspan="5" class="nametable"> 
<form method="GET" id="option">
    <select id="select" placeholder="optie" name="queryoption" onchange='if(this.value != 0) { this.form.submit(); }'>
        <option>Keuze</option>
        <option value="global">Global</option>
        <option value="personal">Personal</option>
    </select>
</form>
</th>
</tr>

<?php
if(isset($_GET["queryoption"])){
    $qo = htmlspecialchars($_GET["queryoption"]);
    switch($qo){
        case "personal":
            echo getAudit("personal");
            break;
        case "global":
            echo getAudit("global");
            break;
        default:
            echo getAudit("personal");
            break;
    }
}
else{
    echo getAudit("personal");
}
} 
?>
</table>

<?php
if($page == 1){
?>
<div class="onderkant">  
<div class="profile">


<table class="table table-striped table-dark table-bordered">
    <th colspan="3" class="nametable"> <?php echo $username ?></th>
    <tr>
    <th scope="row">Rank</th>
    <th scope="row">Afdeling</th>
    <?php if($user == "EX"){ ?>
    <th scope="row">Warn</th>
    <?php } ?>
</tr>
<tr>
<td><?php echo $rank ?></td>
<td><?php echo $node ?> </td>
<?php 
if($user == "EX"){
    ?>
<td> <form method="POST"><button type="submit" name="warn" value="<?php echo $username ?>">Warn</button></form></td>
<?php }
?>
</tr>
    
</tr>
</table>

<form method="POST" name="change">
    <?php
    if($user == "DNEX"){
        ?>
        <div class="changers">
        <button name="promote" id="promote">Promoveren</button>
        <button name="custom" id="custom">Custom</button>
        </div>
        <br>
        <input type="text" name="reason" placeholder="Reden" required>
        <?php
    }

    else{
        ?>
    <div class="changers">
    <button name="promote" id="promote">Promoveren</button>
    <button name="demote" id="demote">Degraderen</button>
    <button name="ontslag" id="ontslag">Ontslagen</button>
    <button name="custom" id="custom">Custom</button>
    </div>
    <br>
    <input type="text" name="reason" placeholder="Reden" required>
    <?php
}
?>
</form>
    </div>
    <br>
<div class='search'>
    <form method='GET'>
    <p><b>Username</b></p>
    <input type='text' name='naam' id='name' placeholder='Username'>
</div>
</form>

<?php
#warnings
if($user == "EX"){
if(isset($_POST["showoption"])){
    $s_o = htmlspecialchars($_POST["showoption"]);
}
if(!isset($_POST["showoption"])){
    $s_o = "changes";
}

?>
<div class="tablebox">
<table class="table table-striped table-dark table-bordered table-hover">
<tr>
<th colspan="5" class="nametable"> 
<form method="POST" id="option">
    <select id="select" placeholder="optie" name="showoption" onchange='if(this.value != 0) { this.form.submit(); }'>
        <option value="changes">Keuze</option>
        <option value="warns">Warns</option>
        <option value="changes">Changes</option>
    </select>
</form>
</th></tr>

<tr>

<?php

    if($s_o == "warns"){
        ?>
        <th scope="row">User</th>
        <th scope="row">Reason</th>
        <th scope="row">Warning type</th>
        </tr>
    <?php
        foreach($get_scope_warns as $scope_warns){
            $gewaarschuwde = $scope_warns["gewaarschuwde"];
            $waarschuwer = $scope_warns["waarschuwer"];
            $reden = $scope_warns["reden"];
            $type = $scope_warns["warn_type"];
            echo "<tr><td>$waarschuwer <b>&rarr;</b> $gewaarschuwde</td>
            <td>$reden</td>
            <td>$type</td></tr>";
    
        }
    }
    else{
        echo getAudit("scope");
    }
}
#end of page 1
}
?>
</table>
<!-- einde div van tablebox -->
</div>
</div>
</div>
<p style="color: white; text-align: center;">Made with ♥ by Mohamed | <a href="https://github.com/Mohagames205/flowpanel">FlowPanel</a> version 2.0.0 Alpha</p>
</body>
</html>
