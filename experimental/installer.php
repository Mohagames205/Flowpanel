<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FlowPanel - Installer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
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
        $path = $_POST["path"];
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
                $conn = null; 
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


            $tag = null;
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
            $tag = "<b>Connection failed: </b>" . $e->getMessage();
            }

        
    }

     ?>
     <div class="title">
     <h1>Flowpanel installer </h1>
     <hr>
     </div>
     
     <main>
     <h2> Database instellingen </h2>
     <hr>
     <?php if(!empty($tag)){
         ?>
        <div class="alert alert-danger">
        <?php echo $tag; ?> 
        </div>
         <?php
     }
     ?>
     <h3>Databaseinfo:</h3>
     <p>Gelieve een database aan te maken voor Flowpanel.</p>
     <br>
    <form method="POST" name="databaseform">
    <input type="text" name="dbhost" placeholder="Database Host" required><br><br>
    <input type="text" name="username" placeholder="username" required><br><br>
    <input type="password" name="password" placeholder="password"><br><br>
    <input type="text" name="dbname" placeholder="Database name" required><br><br>
    <hr>
    <h3>Installatie locatie</h3>
    <p>Hier vul je in waar je de flowpanel files hebt gestoken. Vb: <i>localhost/panel, localhost, mysite.com/panel</i><br>Het is aangeraden om de files in de root webfolder te steken.</p>
    <input type="text" name="path" placeholder="Installatielocatie" required>
    <hr>
    <h3>Database tabellen</h3>
    <input type="radio" name="dbpref" value="y" required>Mijn tabellen zijn al aangemaakt<br>
    <input type="radio" name="dbpref" value="tbl" required>Mijn tabellen zijn nog <b>niet</b> aangemaakt (deze optie is aanbevolen).<br>
    <input type="radio" name="dbpref" value="n" required>Mijn database is nog <b>niet</b> aangemaakt (deze optie werkt mogelijk niet).<br><br>
    <hr>
    <button type="submit" name="dbform">Opslaan</button>
    </form>
</main>
</body>
</html>