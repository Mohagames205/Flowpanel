<?php
include("../connect.php");
session_start();
if(isset($_SESSION["warned"]) AND isset($_SESSION["username"])){
    require("includes/header.inc.php");
    $usernamea = $_SESSION["username"];
    $bericht = "Welkom $usernamea";
    $username = $_SESSION["warned"];
    $warner = $_SESSION["warner"];
}
else{
    die();
    header("LOCATION:../index.php");
}

if(isset($_POST["warn"])){
    $reden = htmlspecialchars($_POST["reden"]);
    $warn_type = htmlspecialchars($_POST["warn_type"]);
    if($warn_type == "3w" OR $warn_type == "2w" OR $warn_type == "1w"){
        $warn_query = $handle->prepare("INSERT INTO warns values(warn_id, :waarschuwer, :gewaarschuwde, :reden, :warn_type)");
        $warn_query->execute(["waarschuwer" => $warner, "gewaarschuwde" => $username, "reden" => $reden, "warn_type" => $warn_type]);
        unset($_SESSION['warned']);
        unset($_SESSION['warner']);
        header("location:home.php");
    }
    else{
        ?>
        <script> alert("Ongeldige waarschuwingstype!"); </script>
        <?php

    }
}


?>



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


