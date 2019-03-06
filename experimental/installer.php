<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FlowPanel - Installer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <?php
    require_once("query.php");
    $behaviour_json = file_get_contents('../config/behaviour.json');
    $behaviourdata = json_decode($behaviour_json, true);
    $state = $behaviourdata["installer"];
    if($state == "disable"){
        header("LOCATION:/");
        die();
    }

    if(isset($_POST["dbform"])){
        $path = "soon";
        $dbhost = htmlspecialchars($_POST["dbhost"]);
        $username = htmlspecialchars($_POST["username"]);
        $password = htmlspecialchars($_POST["password"]);
        $dbname = $_POST["dbname"];

        try {
            
            $dbpref = htmlspecialchars($_POST["dbpref"]);
            if($dbpref == "y"){
                $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected successfully"; 
            }
            if($dbpref == "n"){
                $conn = new PDO("mysql:host=$dbhost", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->exec('CREATE DATABASE ' . $dbname);
                $conn = null;

                $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $conn->exec($querydb);

                $conn = null;
            }
            if($dbpref == "tbl"){
                $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $username, $password);
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Connected successfully"; 
                $conn->exec($querydb);
                $conn = null;

            }

            else{
                echo "Er is iets misgelopen";
            }



            $conn = null;
            $behaviourdata["installer"] = "disable";
            $behaviourdata["path"] = $path;

            $behaviour = json_encode($behaviourdata, JSON_PRETTY_PRINT);
            file_put_contents('../config/behaviour.json', $behaviour);
            
            $database = array("dbhost" => $dbhost, "username" => $username, "password" => $password, "dbname" => $dbname);
            $database_json = json_encode($database);
            echo $database_json;
            $fp = fopen('../config/database.json', 'w');
            fwrite($fp, $database_json);
            fclose($fp);
            header("LOCATION:/");
            die();
            }
        catch(PDOException $e)
            {
            echo "<b>Connection failed: </b>" . $e->getMessage();
            }

        
    }

     ?>
     <h1>Flowpanel installer </h1>
     <hr>
     <h2>Gelieve alle instructies te volgen, anders zal het systeem niet naar behoren functioneren.</h2>
     <hr>
     <h3>Gelieve een database aan te maken, daarna vul je de velden hieronder in met de juiste informatie</h3>
    <form method="POST" name="databaseform">
    <input type="text" name="dbhost" placeholder="Database Host" required><br><br>
    <input type="text" name="username" placeholder="username" required><br><br>
    <input type="password" name="password" placeholder="password"><br><br>
    <input type="text" name="dbname" placeholder="Database name" required><br><br>
    <hr>
    <h3>Database tabellen</h3>
    <input type="radio" name="dbpref" value="y" required>Mijn tabellen zijn al aangemaakt<br>
    <input type="radio" name="dbpref" value="tbl" required>Mijn tabellen zijn nog <b>niet</b> aangemaakt.<br>
    <input type="radio" name="dbpref" value="n" required>Mijn database is nog <b>niet</b> aangemaakt (deze optie werkt mogelijk niet)<br><br>
    <hr>
    <button type="submit" name="dbform">Submit</button>
    </form>

</body>
</html>