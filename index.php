<?php
include("kaas.php");
session_start();

if(isset($_SESSION["username"])){
	header("location:cr/home.php");
}

if(isset($_POST["login"])){
    if(empty($_POST["username"]) OR empty($_POST["password"])){
        echo "Alle velden invullen!";
    }
    else{
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $statement = $handle->prepare($query);
        $statement->execute(
            array(
                'username' => $_POST["username"],
                'password' => $_POST["password"]
            )
            );
            $count = $statement->rowCount();
            if ($count > 0){
                $gn = $handle->prepare("SELECT username FROM users WHERE username = :username");
                $gn->execute(["username" => $_POST["username"]]);
                $username = $gn->fetch(PDO::FETCH_ASSOC);
                $username = $username["username"];
                $_SESSION["username"] = $username; 
                header("location:cr/home.php");
            }
            else{
                $tag = "Wrong data!";
            }
            }
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CakeRanking</title>

    <!-- Custom styles for this template -->
    <link href="style/login.css" rel="stylesheet">
  </head>

<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->
    
    <h2 class="active"> Sign In </h2>

    <!-- Icon -->
    <div class="fadeIn first">
    
      <img src="foto/up.svg" id="icon" alt="User Icon" />
    </div>
            <?php 
        if(!isset($tag)){
            $tag = NULL;
        }
        echo "<p style='color: red'>$tag </p>"; ?>

    <!-- Login Form -->
    <form method="POST">
      <input type="text" name="username" id="login" class="fadeIn second"  placeholder="login">
      <input type="password" name="password" id="password" class="fadeIn third" placeholder="password">
      <input type="submit" class="fadeIn fourth" value="Log In" name="login">
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <p class="underlineHover"> © CakeCraft 2018-2019 </p>
    </div>

  </div>
</div>
  </body>
</html>
