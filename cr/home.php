<?php
session_start();
include("../kaas.php");
include("includes/audit.inc.php");

if(isset($_SESSION["username"])){
    $usernamea = $_SESSION["username"];
    $bericht = "Welkom $usernamea";
    

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
    $naam = $_GET["naam"];
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
        $rank_query = $handle->prepare("SELECT rank_name, perm_id FROM ranks WHERE rank_id = :rank_id");
        $rank_query->execute(["rank_id" => $rank_id]);
        $rank = $rank_query->fetch(PDO::FETCH_ASSOC);
        $rank = $rank["rank_name"];
        $node = $userdata["node"];

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
    }


}

else{
    $page = 0;
    $get_personal_audit = $handle->prepare("SELECT * FROM audit_log WHERE changer = :usernamea");
    $get_personal_audit->execute(["usernamea" => $usernamea]);
    $get_global_audit = $handle->query("SELECT * FROM audit_log");

    
}

if(isset($_POST["promote"])){
    $reason = $_POST["reason"];
    $change_date = date('d/m/Y');
    $change_slachtoffer = $username;
    $change_type = "Promotie";
    $changer = $usernamea;

    if($user == "DNEX"){
        $userinsert = $handle->prepare("INSERT INTO user_ranks (username, rank_id, node) VALUES(:username, :rank_id, :node)");
        $userinsert->execute(["username" => $username, "rank_id" => 2, "node" => "B"]);
        $old_rank = 1;
        $new_rank = 2;
        write_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank,$reason, $change_date);
        header("Refresh:0");
    }

    else{
        if($rank_id < 6){
            $new_rank_id = $rank_id + 1;
            $new_rank = $new_rank_id;
            $old_rank = $rank_id;
            $userpromote = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
            $userpromote->execute(["rank_id" => $new_rank_id, "username" => $username]);
            write_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank,$reason, $change_date);
            header("Refresh:0");
        }
        else{
            ?>
            <script>
                alert("U kan geen promotie meer geven!");
                </script>
            <?php
        }
    }
}

if(isset($_POST["demote"])){
    $reason = $_POST["reason"];
    $change_date = date('d/m/Y');
    $change_slachtoffer = $username;
    $change_type = "Degradatie";
    $changer = $usernamea;
    if($user == "DNEX"){
        ?>
            <script>
                alert("Deze gebruiker kan geen degradatie ontvangen!");
            </script>
            <?php
    }

    else{
        if($rank_id > 1){
            $old_rank = $rank_id;
            $new_rank_id = $rank_id - 1;
            $userpromote = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
            $userpromote->execute(["rank_id" => $new_rank_id, "username" => $username]);
            write_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank_id,$reason, $change_date);
            header("Refresh:0");
        }
        else{
            ?>
            <script>
                alert("U kan geen degradatie meer geven!");
                </script>
            <?php
        }
    }
}


if(isset($_POST["ontslag"])){
    $reason = $_POST["reason"];
    $change_date = date('d/m/Y');
    $change_slachtoffer = $username;
    $change_type = "Ontslag";
    $changer = $usernamea;
    if($user == "DNEX"){
        ?>
            <script>
                alert("Deze gebruiker kan geen ontslag ontvangen!");
                </script>
            <?php
    }
    else{
        $old_rank = $rank_id;
        $userpromote = $handle->prepare("DELETE FROM user_ranks WHERE username = :username");
        $userpromote->execute(["username" => $username]);
        write_audit($changer, $change_type, $change_slachtoffer, $old_rank, 0,$reason, $change_date);
        header("Refresh:0");
    }
}

if(isset($_POST["custom"])){
    $_SESSION["custom"] = $username;
    $_SESSION["reason"] = $_POST["reason"];
    header("location:custom.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CakeRankings - Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <link rel="stylesheet" type="text/css" href="style/main.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head> 

<body>
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
    $qo = $_GET["queryoption"];
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
        $oude_rank_name = $handle->prepare("SELECT rank_name FROM ranks WHERE rank_id = :oude_rank");
        $oude_rank_name->execute(["oude_rank" => $oude_rank]);
        $nieuwe_rank_name = $handle->prepare("SELECT rank_name FROM ranks WHERE rank_id = :nieuwe_rank");
        $nieuwe_rank_name->execute(["nieuwe_rank" => $nieuwe_rank]);
        $nieuwe_rank_name = $nieuwe_rank_name->fetch(PDO::FETCH_ASSOC);
        $oude_rank_name = $oude_rank_name->fetch(PDO::FETCH_ASSOC);
        $oude_rank = $oude_rank_name["rank_name"];
        $nieuwe_rank = $nieuwe_rank_name["rank_name"];
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
        $oude_rank_name = $handle->prepare("SELECT rank_name FROM ranks WHERE rank_id = :oude_rank");
        $oude_rank_name->execute(["oude_rank" => $oude_rank]);
        $nieuwe_rank_name = $handle->prepare("SELECT rank_name FROM ranks WHERE rank_id = :nieuwe_rank");
        $nieuwe_rank_name->execute(["nieuwe_rank" => $nieuwe_rank]);
        $nieuwe_rank_name = $nieuwe_rank_name->fetch(PDO::FETCH_ASSOC);
        $oude_rank_name = $oude_rank_name->fetch(PDO::FETCH_ASSOC);
        $oude_rank = $oude_rank_name["rank_name"];
        $nieuwe_rank = $nieuwe_rank_name["rank_name"];
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
    <th colspan="3" class="nametable"> <?php echo $username ?> </th>
    <tr>
    <th scope="row">Rank</th>
    <th scope="row">Server</th>
</tr>
<tr>
<td><?php echo $rank ?></td>
<td><?php echo $node ?> </td>
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
}
?>
</div>
<p style="color: white; text-align: center;">Made with ♥ by Mohamed | <a href="https://github.com/Mohagames205/flowpanel">FlowPanel</a> version 1.2 PreRelease </p>
</main>
</body>
</html>