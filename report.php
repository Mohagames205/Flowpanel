<?php

if(isset($_POST["report"])){
    $reporter = $_POST["reporter"];
    $reported_user = $_POST["reported_user"];
    $reason = $_POST["reason"];
    $priority = $_POST["priotity"];

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CakeCraft | Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <form method="POST">
    <input type="text" name="reporter" placeholder="Your username">
</body>
</html>