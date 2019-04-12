<?php
session_start();
if(isset($_SESSION["username"])){
    require("../connect.php");
    require("includes/header.inc.php");
    $lists = new Lists;
    $zoekgebruiker = new User($usernamea);
    #Getting permission ID
    $perm_id = $zoekgebruiker->perm_id;
    
    
}

else{
    header("location:../index.php");
}




if(isset($_GET["naam"])){
    $page = 1;
    $naam = htmlspecialchars($_GET["naam"]);
    $zoekgebruiker->getUserdata($naam);
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
    $function = NULL;
}

if(isset($_POST["warn"])){
    $_SESSION["warned"] = htmlspecialchars($_POST["warn"]);
    $_SESSION["warner"] = $usernamea;
    header("location:warn.php");

}
if(isset($_POST["del"])){
    $perm = get_perm($perm_id, "delete", $rank_id);
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

if(isset($_SESSION["error"])){
    echo $_SESSION["error"];
    unset($_SESSION["error"]);
}

if(isset($_POST["promote"])){
    $zoekgebruiker->promote($username);
}

if(isset($_POST["demote"])){
    $zoekgebruiker->demote($username);
}


if(isset($_POST["ontslag"])){
    $zoekgebruiker->ontslagen($username);
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
            echo $lists->getAudit("personal");
            break;
        case "global":
            echo $lists->getAudit("global");
            break;
        default:
            echo $lists->getAudit("personal");
            break;
    }
}
else{
    echo $lists->getAudit("personal");
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
    <?php if($user == "EX" AND $rank_id > 0){ ?>
    <th scope="row">Afdeling</th>
    <th scope="row">Warn</th>
    <?php } ?>
</tr>
<tr>
<td><?php echo $rank ?></td>
<?php 
if($user == "EX" AND $rank_id > 0 ){
    ?>
<td><?php echo get_dep_name($zoekgebruiker->gez_afdeling); ?> </td>

<td> <form method="POST"><button type="submit" name="warn" value="<?php echo $username ?>">Warn</button></form></td>
<?php }
?>
</tr>
    
</tr>
</table>

<form method="POST" name="change">
    <?php
    if($user == "DNEX" OR $rank_id <= 0){
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
        echo $lists->getAudit("scope");
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
