<?php

$behaviour = file_get_contents("config/behaviour.json");
$array = json_decode($behaviour, true);

if($array["installer"] == "enable"){
  header("location:experimental/installer.php");
  die();
}
else{
  include("connect.php");
  session_start();
  
  if(isset($_SESSION["username"])){
    header("location:cr/home.php");
  }
  if(isset($_POST["register"])){
      $username = htmlspecialchars($_POST["username"]);
      $password = htmlspecialchars($_POST["password"]);
      $cpassword = htmlspecialchars($_POST["cpassword"]);
      $username = htmlspecialchars($_POST["username"]);
      $statement = $handle->prepare("SELECT * FROM users WHERE username = :username");
      $statement->execute(["username" => $username]);
      $count = $statement->rowCount();

      if($password != $cpassword){
          $tag = "De 2 wachtwoorden zijn niet hetzelfde!";
      }
      elseif($count < 0){
          $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
          $register_query = $handle->prepare("INSERT INTO users (user_id, username, password, perm_id) values(user_id, :username, :password, perm_id)");
          $register_query->execute(["username" => $username, "password" => $hashed_pw]);
          header("location:index.php");
          die();
      }
      else{
        $tag = "De gebruiker bestaat al!";
      }
  }
  

  ?>
  
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Inlog pagina om toegang te krijgen tot het staffpaneel">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
      <title>Flowpanel | Register</title>

      <link href="style/login.css" rel="stylesheet">
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
    background: url(/cr/loader.gif) center no-repeat #fff;
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
  <div class="wrapper fadeInDown">
    <div id="formContent">

      
      <h2 class="active"> Register </h2>
  

      <div class="fadeIn first">
      
        <img src="foto/up.svg" id="icon" alt="User Icon" />
      </div>
              <?php 
          if(!isset($tag)){
              $tag = NULL;
          }
          echo "<p style='color: red'>$tag </p>"; ?>
  

      <form method="POST">
        <input type="text" name="username" id="username" class="fadeIn second"  placeholder="username">
        <input type="password" name="password" id="password" class="fadeIn third" placeholder="password">
        <input type="password" name="cpassword" id="password" class="fadeIn third" placeholder="confirm password">
        <input type="submit" class="fadeIn fourth" value="register" name="register">
        
      </form>
  

      <div id="formFooter">
        <p class="underlineHover"> © Flowpanel 2018-2019 </p>
      </div>
  
    </div>
  </div>
    </body>
  </html>
<?php
}
?>