<?php
    session_start();
    header("location:../index.php");
    session_destroy();
    die();
?>

<h1> Aan het uitloggen </h1>