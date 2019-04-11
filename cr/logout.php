<?php
    session_start();
    session_destroy();
    header("location:../index.php");
    
    die();
?>

<h1> Aan het uitloggen </h1>