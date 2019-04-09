<?php

class Lists{
    public function getRanks(){
        include("../connect.php");
        $getRanks = $handle->prepare("SELECT * FROM ranks");
        $getRanks->execute();
        $table = "<div class='tablebox'>
        <table class='table table-striped table-dark table-bordered table-hover'><thead><th colspan='2' class='nametable'>Rangenlijst</th><thead><tr>
            <th scope='row'>Rank ID</th>
            <th scope='row'>Rank naam</th>
            </tr></thead>";
        foreach($getRanks as $rankdata){
            $rank_name = $rankdata["rank_name"];
            $rank_id = $rankdata["rank_id"];
            $table .= "<tr><td>$rank_id</td><td>$rank_name</td>";

        }

        $table .= "</table></div>";
        return $table;
    }

    public function getWerkers(){
        include("../connect.php");
        $getWerkers = $handle->prepare("SELECT * FROM user_ranks ORDER BY rank_id DESC");
        $getWerkers->execute();
        $table = "<div class='tablebox'>
        <table class='table table-striped table-dark table-bordered table-hover'><thead><th colspan='2' class='nametable'>Leden</th><tr>
        <th scope='row'>Gebruikersnaam</th>
        <th scope='row'>Rang</th>
        </tr></thead>";
        foreach($getWerkers as $werkerdata){
            $username = $werkerdata["username"];
            $rank = get_rank_name($werkerdata["rank_id"]);
            $table .= "<tr><td>$username</td><td>$rank</td></tr>";
        }
        $table .= "</table></div>";
        return $table;

    }

    public function getAudit($type){
        include("../connect.php");
        $username = $_SESSION["username"];
        
        switch($type){
            case "scope":
                $gez_user = htmlspecialchars($_GET["naam"]);
                $audit = $handle->prepare("SELECT * FROM audit_log WHERE change_slachtoffer = :username ORDER BY audit_id DESC");
                $audit->execute(["username" => $gez_user]);
                break;
            case "global":
                $audit = $handle->query("SELECT * FROM audit_log ORDER BY audit_id DESC");
                break;
            case "personal":
                $audit = $handle->prepare("SELECT * FROM audit_log WHERE changer = :usernamea ORDER BY audit_id DESC");
                $audit->execute(["usernamea" => $username]);
                break;
        }
        $table = "<tr>
        <th scope='row'>User</th>
        <th scope='row'>Rank</th>
        <th scope='row'>Reason</th>
        <th scope='row'>Change</th>
        </tr></thead>";
        if($audit->rowCount() > 0){
            foreach($audit as $data){
                $changer = $data["changer"];
                $oude_rank = $data["old_rank_id"];
                $nieuwe_rank = $data["new_rank_id"];
                $change_type = $data["change_type"];
                $slachtoffer = $data["change_slachtoffer"]; 
                $reason = $data["reason"];
                $audit_id = $data["audit_id"];
                $oude_rank = get_rank_name($oude_rank);
                $nieuwe_rank = get_rank_name($nieuwe_rank);
                $tstat = get_color($change_type);
                $table .= "<tr><td>$changer <b>&rarr;</b> $slachtoffer</td>
                <td>$oude_rank <b>&rarr;</b> $nieuwe_rank</td>
                <td>$reason</td>
                <td bgcolor='$tstat'>$change_type <br><form method='post'><button type='submit' name='del' value=$audit_id>Verwijder</button></td></tr>";
            }
            $table .= "</table>";
            return $table;
        }
    }
}