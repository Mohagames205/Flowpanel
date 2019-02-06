<?php
session_start();
include("../../connect.php");
if(isset($_POST["logout"])){
    session_destroy();
    header("location:../../../index.php");
  }

if(isset($_SESSION["warned"])){
    $usernamea = $_SESSION["username"];
    $bericht = "Welkom $usernamea";
    $username = $_SESSION["warned"];
    $warner = $_SESSION["warner"];
}

if(isset($_POST["warn"])){
    $reden = htmlspecialchars($_POST["reden"]);
    $warn_type = htmlspecialchars($_POST["warn_type"]);
    if($warn_type == "3w" OR $warn_type == "2w" OR $warn_type == "1w"){
        $warn_query = $handle->prepare("INSERT INTO warns values(warn_id, :waarschuwer, :gewaarschuwde, :reden, :warn_type)");
        $warn_query->execute(["waarschuwer" => $warner, "gewaarschuwde" => $username, "reden" => $reden, "warn_type" => $warn_type]);
        unset($_SESSION['warned']);
        unset($_SESSION['warner']);
        header("location:../home.php");
    }
    else{
        ?>
        <script> alert("Ongeldige waarschuwingstype!"); </script>
        <?php

    }
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
    <link rel="stylesheet" type="text/css" href="../style/main.css"/>
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




<div class="tabel">
<table class="table table-striped table-dark table-bordered">
    <th colspan="3" class="nametable"> <?php echo $username ?> </th>
</table>
<form method="POST">


    <select name="warn_type" id="name" required>
    <option value="1w">1ste klasse</option>
    <option value="2w">2de klasse</option>
    <option value="3w">3de klasse</option>
    </select><br><br>
    <input type="text" name="reden" id="name" placeholder="reden" required>
    <br>
    <br>
    <button type="submit" id="ontslag" name="warn">Waarschuwen</button>
    </form>
</div>
<br>
<hr>
<p style="color: white; text-align: center;">Made with ♥ by Mohamed | <a href="https://github.com/Mohagames205/flowpanel">FlowPanel</a> version 1.2 PreRelease </p>
</main>
</body>
</html>


