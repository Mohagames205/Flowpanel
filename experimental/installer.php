<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FlowPanel - Installer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css">
</head>
<body>
    <?php
    if(isset($_POST["dbform"])){
        $dbhost = htmlspecialchars($_POST["dbhost"]);
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);
        $dbname = htmlspecialchars($_POST["dbname"]);

        $database = array("dbhost" => $dbhost, "username" => $username, "password" => $password, "dbname" => $dbname, "installer" => false);
        $database_json = json_encode($database);
        echo $database_json;
        $fp = fopen('config/database.json', 'w');
        fwrite($fp, $database_json);
        fclose($fp);
    }

     ?>
    <form method="POST" name="databaseform">
    <input type="text" name="dbhost" placeholder="Database Host" required><br><br>
    <input type="text" name="username" placeholder="username" required><br><br>
    <input type="password" name="password" placeholder="password"><br><br>
    <input type="text" name="dbname" placeholder="Database name" required><br><br>
    <button type="submit" name="dbform">Submit</button>
    </form>

</body>
</html>