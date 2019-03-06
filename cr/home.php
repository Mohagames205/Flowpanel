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
        #changes en warns ophalen
        #audit
        $get_scope_audit = $handle->prepare("SELECT * FROM audit_log WHERE change_slachtoffer = :username");
        $get_scope_audit->execute(["username" => $username]);
        #warns
        $get_scope_warns = $handle->prepare("SELECT * FROM warns WHERE gewaarschuwde = :username");
        $get_scope_warns->execute(["username" => $username]);

        #node system
        if(strtolower($node) == "c"){
            $node = "CakeCraft Quest";
        }
        if(strtolower($node) == "h"){
            $node = "CakeCraft Hub";
        }
        if(strtolower($node) == "b"){
            $node = "Global";
        }
        
    }
    else{
        $username = $naam;
        $rank = "Guest";
        $node = "De gebruiker staat niet in onze database";
        $user = "DNEX";
        $rank_id = 1;
    }


}

else{
    $page = 0;
    $get_personal_audit = $handle->prepare("SELECT * FROM audit_log WHERE changer = :usernamea");
    $get_personal_audit->execute(["usernamea" => $usernamea]);
    $get_global_audit = $handle->query("SELECT * FROM audit_log");

    
}
if(isset($_POST["warn"])){
    $_SESSION["warned"] = htmlspecialchars($_POST["warn"]);
    $_SESSION["warner"] = $usernamea;
    header("location:includes/warn.php");

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
            ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action. <br> Error code: PDNEX", "error"); </script> <?php
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
            swal("No permission", "You don't have the appropriate permissions to complete this action. <br> Error code: PEX", "error");
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
                ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action. <br> Error code: DEX", "error"); </script> <?php
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
                swal("Error", "Deze gebruiker kan geen ontslag ontvangen! Error code: ONTDNEX", "error");
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
        ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action. <br> Error code: ONTEX", "error"); </script> <?php
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
        ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action. \nError code: CUSTEX", "error"); </script> <?php
    }
    
}
?>

    <div class="name">
    <a href="home.php"> Home </a>
    <?php echo "<p id='a'> $bericht </p>" ?>
    <form method="POST">
        <button name="logout" class="btn btn-outline-light">Uitloggen</button>
        <a role="button" class="btn btn-outline-light" href="../paneel">Sollicitaties</a>
    </form>
</div>

<?php if($page == 0){
    $count = $get_personal_audit->rowCount();
    ?>
    <div class='search'>
    <form method='GET'>
    
    <p><b>Username</b></p>
    <input type='text' name='naam' id='name' placeholder='Username'>

</form>
</div>

<?php if($count > 0){

?>
<table class="table table-striped table-dark table-bordered table-hover">
<tr>
<th colspan="4" class="nametable"> 
<form method="GET" id="option">
    <select id="select" placeholder="optie" name="queryoption" onchange='if(this.value != 0) { this.form.submit(); }'>
        <option>Keuze</option>
        <option value="global">Global</option>
        <option value="personal">Personal</option>
    </select>
</form>


 </th></tr>


<th>User</th>
<th>Wijziging</th>
<th>Reden</th>
<th>Soort</th>

<?php

if(isset($_GET["queryoption"])){
    $qo = htmlspecialchars($_GET["queryoption"]);
}
if(!isset($_GET["queryoption"])){
    $qo = "personal";
}
if($qo == "personal"){
    foreach($get_personal_audit as $personal_audit){
        $changer = $personal_audit["changer"];
        $oude_rank = $personal_audit["old_rank_id"];
        $nieuwe_rank = $personal_audit["new_rank_id"];
        $change_type = $personal_audit["change_type"];
        $slachtoffer = $personal_audit["change_slachtoffer"]; 
        $reason = $personal_audit["reason"];
        $oude_rank = get_rank_name($oude_rank);
        $nieuwe_rank = get_rank_name($nieuwe_rank);
        $tstat = get_color($change_type);
        echo "<tr><td>$changer <b>&rarr;</b> $slachtoffer</td>
        <td>$oude_rank <b>&rarr;</b> $nieuwe_rank</td>
        <td>$reason</td>
        <td bgcolor='$tstat'>$change_type</td></tr>";
         
    }
    
}

if($qo == "global"){
    foreach($get_global_audit as $global_audit){
        $changer = $global_audit["changer"];
        $oude_rank = $global_audit["old_rank_id"];
        $nieuwe_rank = $global_audit["new_rank_id"];
        $change_type = $global_audit["change_type"];
        $reason = $global_audit["reason"];
        $slachtoffer = $global_audit["change_slachtoffer"]; 
        $oude_rank = get_rank_name($oude_rank);
        $nieuwe_rank = get_rank_name($nieuwe_rank);
        $tstat = get_color($change_type);
        echo "<tr><td>$changer <b>&rarr;</b> $slachtoffer</td>
        <td>$oude_rank <b>&rarr;</b> $nieuwe_rank</td>
        <td>$reason</td>
        <td bgcolor='$tstat'>$change_type</td></tr>";
         
    }
}

}
}

if($page == 1){
    ?>
<div class="onderkant">  
<div class="profile">


<table class="table table-striped table-dark table-bordered">
    <th colspan="3" class="nametable"> <?php echo $username ?></th>
    <tr>
    <th scope="row">Rank</th>
    <th scope="row">Server</th>
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
<table class="table table-striped table-dark table-bordered table-hover">
<tr>
<th colspan="4" class="nametable"> 
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
        foreach($get_scope_audit as $scope_audit){
            $changer = $scope_audit["changer"];
            $oude_rank = $scope_audit["old_rank_id"];
            $nieuwe_rank = $scope_audit["new_rank_id"];
            $change_type = $scope_audit["change_type"];
            $reason = $scope_audit["reason"];
            $slachtoffer = $scope_audit["change_slachtoffer"]; 
            $oude_rank = get_rank_name($oude_rank);
            $nieuwe_rank = get_rank_name($nieuwe_rank);
            $tstat = get_color($change_type);
            echo "<tr><td>$changer <b>&rarr;</b> $slachtoffer</td>
            <td>$oude_rank <b>&rarr;</b> $nieuwe_rank</td>
            <td>$reason</td>
            <td bgcolor='$tstat'>$change_type</td></tr>";
             
        }
       
    }
}


#end of page 1
}
?>
</div>
<p style="color: white; text-align: center;">Made with ♥ by Mohamed | <a href="https://github.com/Mohagames205/flowpanel">FlowPanel</a> version 2.0.0 Alpha</p>
</main>


</body>

</html>
