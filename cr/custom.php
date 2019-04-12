<?php
session_start();
include("../connect.php");

if(isset($_SESSION["custom"])){
    //ranklijst ophalen
    require("includes/header.inc.php");
    $gebruiker = new User($usernamea);
    $rank_list_query = $handle->prepare("SELECT * FROM ranks");
    $rank_list_query->execute();

    //min en max van rank_id bepalen
    $rank_minmax = $handle->prepare("SELECT
    (SELECT rank_id FROM ranks ORDER BY rank_id LIMIT 1) as 'first',
    (SELECT rank_id FROM ranks ORDER BY rank_id DESC LIMIT 1) as 'last'");
    $rank_minmax->execute();
    $rank_minmax = $rank_minmax->fetch(PDO::FETCH_ASSOC);
    $r_min = $rank_minmax["first"];
    $r_max = $rank_minmax["last"];

    //afdelingen ophalen
    $afdelingen = $handle->prepare("SELECT afdeling_afkorting FROM afdelingen");
    $afdelingen->execute();
    $previous = NULL;

    //dit zorgt ervoor dat alle afdelingnamen maar 1x voorkomen
    foreach($afdelingen as $afdeling){
        if($afdeling["afdeling_afkorting"] == $previous){
            
        }
        else{
            $afdelinglijst[] = $afdeling["afdeling_afkorting"];
        }
        $previous = $afdeling["afdeling_afkorting"];
        
    }

    //afdelingsrangen ophalen

    //!! Dit zo snel mogelijk aanpassen, 'func' moet veranderen adhv de perm_id!!
    if(isset($_GET["afdeling"])){
        $afdeling = htmlspecialchars($_GET["afdeling"]);
        if(in_array($afdeling, $afdelinglijst)){
            $afdeling_list_query = $handle->prepare("SELECT * FROM afdelingen WHERE afdeling_afkorting = :afdeling_afkorting AND func = :func");
            $afdeling_list_query->execute(["afdeling_afkorting" => $afdeling, "func" => "AD"]);
        }
        else{
            echo "ERROR";
            header("location:https://google.be");
            die();
        } 
    }
    

    //min en max van afdeling_id bepalen
    $afdeling_minmax = $handle->prepare("SELECT 
    (SELECT afdeling_id FROM afdelingen ORDER BY afdeling_id LIMIT 1) as 'first',
    (SELECT afdeling_id FROM afdelingen ORDER BY afdeling_id DESC LIMIT 1) as 'last'");
    $afdeling_minmax->execute();
    $afdeling_minmax = $afdeling_minmax->fetch(PDO::FETCH_ASSOC);
    $a_min = $afdeling_minmax["first"];
    $a_max = $afdeling_minmax["last"];

    


    //het slachtoffer ophalen
    $username = htmlspecialchars($_SESSION["custom"]);
    $naam = $username;
    //perm_id van de user ophalen
    $perm_id = $gebruiker->perm_id;
    //zoeken naar de user in de database
    $gebruiker->getUserdata($naam);
    $username = $gebruiker->gez_user;
    $gez_rank_id = $gebruiker->gez_rank_id;
    //checken als de gebruiker toestemming heeft

    //dit klopt niet!
    $perm = get_perm($perm_id, "custom", $gez_rank_id);
    if($perm != "allow"){
        die();
        header("location:../index.php");
    }

}

else{
    header("location:../index.php");
    die();
}


if(isset($_POST["nieuwe_rank"])){

    $nieuwe_rank = htmlspecialchars($_POST["nieuwe_rank"]);
    if(($r_min <= $nieuwe_rank) && ($nieuwe_rank <= $r_max)){
        $reason = htmlspecialchars($_SESSION["reason"]);
        $change_date = date('d/m/Y');
        $change_type = "Custom";
        if($gebruiker->userstate == "DNEX"){
            $old_rank = $gebruiker->gez_rank_id;
            $nieuwe_rank = htmlspecialchars($_POST["nieuwe_rank"]);
            $userinsert = $handle->prepare("INSERT INTO user_ranks (username, rank_id, node) VALUES(:username, :rank_id, :node)");
            $userinsert->execute(["username" => $username, "rank_id" => $nieuwe_rank, "node" => "B"]);
            rank_audit($usernamea, $change_type, $username, $old_rank, $nieuwe_rank,$reason, $change_date);
            unset($_SESSION['custom']);
            unset($_SESSION['reason']);
            header("LOCATION:home.php?naam=$username");
            die();
        }
        else{
            $old_rank = $gebruiker->gez_rank_id;
            $wijzigen = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
            $wijzigen->execute(["rank_id" => $_POST["nieuwe_rank"], "username" => $username]);
            $new_rank = $_POST["nieuwe_rank"];;
            $reason = $_SESSION["reason"];
            rank_audit($usernamea, $change_type, $username, $old_rank, $new_rank,$reason, $change_date);
            unset($_SESSION['custom']);
            unset($_SESSION['reason']);
            header("LOCATION:home.php?naam=$username");
            die();
        }
    }
    else{
        unset($_SESSION['custom']);
        unset($_SESSION['reason']);
        ?><script> alert("Deze rank bestaat niet!"); </script><?php
    }
    
    
}

//perm_manager nog laten werken met custom!

if(isset($_POST["afdeling_change"])){
    if($gebruiker->userstate == "EX"){
        $new_dep = $_POST["afdeling_change"];
        if(($a_min <= $new_dep) && ($new_dep <= $a_max) && in_array($_GET["afdeling"], $afdelinglijst)){

        //nog toevoegen dat die checked als de gekozen afdeling deel is van de gekozen afk
        $dep_check = $handle->prepare("SELECT afdeling_afkorting FROM afdelingen WHERE afdeling_id = :afdeling_id");
        $dep_check->execute(["afdeling_id" => $new_dep]); 
        $dep_check = $dep_check->fetch(PDO::FETCH_ASSOC);
        if($dep_check["afdeling_afkorting"] == $_GET["afdeling"]){
            $edit_dep_id = $handle->prepare("UPDATE user_ranks SET afdeling_id = :afdeling_id WHERE username = :username");
            $edit_dep_ak = $handle->prepare("UPDATE user_ranks SET afdeling_afkorting = :afdeling_afkorting WHERE username = :username");
            $edit_dep_id->execute(["afdeling_id" => $new_dep, "username" => $username]);
            $edit_dep_ak->execute(["afdeling_afkorting" => $_GET["afdeling"], "username" => $username]);
            unset($_SESSION['custom']);
            unset($_SESSION['reason']);
            header("LOCATION:home.php?naam=$username");
            die();
        }
        else{
            $_SESSION["error"] = "<script> swal( 'Oops' ,  'De afdeling komt niet overeen met het gekozen type.' ,  'error' ); </script>";
            header("LOCATION:home.php?naam=$username");
            die();
        }
        }
        else{
            $_SESSION["error"] = "<script> swal( 'Oops' ,  'Deze afdeling bestaat niet!' ,  'error' ); </script>";
            header("LOCATION:home.php?naam=$username");
            die();
        }
    }
    else{
        $_SESSION["error"] = "<script> swal( 'Oops' ,  'Je kan deze persoon niet in de afdeling zetten!' ,  'error' ); </script>";
        header("LOCATION:home.php?naam=$username");
        die();
        
        
    }
    
}
?>





<div class="tabel">
<table class="table table-striped table-dark table-bordered">
    <th colspan="3" class="nametable"> <?php echo $username ?> </th>
</table>

<?php if(isset($_GET["customiser"])){
    if(htmlspecialchars($_GET["customiser"]) == "rank"){
?>

<form method="POST">
    <select name="nieuwe_rank" class="custom_select">
    <?php
    foreach($rank_list_query as $rank_data){
        $rank_name = $rank_data["rank_name"];
        $rank_l_id = $rank_data["rank_id"];
        echo "<option value='$rank_l_id'>$rank_name</option>";
    }
    ?>

    </select>
    <br>
    <button type="submit">Wijzigen</button>
    </form>
    <?php } 
    if(htmlspecialchars($_GET["customiser"]) == "afdeling"){
        if(isset($_GET["afdeling"])){
?>
    
    <form method="POST">
    <select name="afdeling_change" class="custom_select">
    <?php
    foreach($afdeling_list_query as $afdeling_data){
        $afdeling_name = $afdeling_data["afdeling_name"];
        $afdeling_l_id = $afdeling_data["afdeling_id"];
        echo "<option value='$afdeling_l_id'>$afdeling_name</option>";
    }
    ?>
    </select>
    <br>
    <button type="submit">Wijzigen</button>
    </form>
<?php 
    }
    else{
        ?>
    <form method="GET">
    <input type="hidden" name="customiser" value="<?php echo htmlspecialchars($_GET['customiser']); ?>">
    <select name="afdeling" class="custom_select">
    <?php
    foreach($afdelinglijst as $afdelingafk){
        echo "<option value='$afdelingafk'>$afdelingafk</option>";
    }
    ?>
    </select>

    <button type="submit">Volgende</button>
    </form>
    <?php
    }
}
}
 else{ ?>

<form method="GET">
<button name="customiser" value="afdeling">Afdeling aanpassen</button>
<button name="customiser" value="rank">Rank aanpassen</button>
</form>

<?php } ?>
</div>
<br>
<hr>
<p style="color: white; text-align: center;">Made with ♥ by Mohamed | <a href="https://github.com/Mohagames205/flowpanel">FlowPanel</a> version 1.2 PreRelease </p>
</main>
</body>
</html>