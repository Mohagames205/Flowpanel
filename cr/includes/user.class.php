    
<?php
class User{

    public $gez_user;
    public $gez_rank_id;
    public $userstate;

    public $perm_id;
    public $username;
    public $rank_id;
    public $afdeling;
    public $user_id;
    public $promo_tag = "soon";
    public $motto;

    public function __construct($username){
        include("../connect.php");
        $siteUser = $handle->prepare("SELECT * FROM users WHERE username = :username");
        $siteUser->execute(["username" => $username]);
        $siteUser = $siteUser->fetch(PDO::FETCH_ASSOC);

        $off_siteUser = $handle->prepare("SELECT * FROM user_ranks WHERE username = :username");
        $off_siteUser->execute(["username" => $username]);
        $off_siteUser = $off_siteUser->fetch(PDO::FETCH_ASSOC);
        
        $this->perm_id = $siteUser["perm_id"];
        $this->username = $siteUser["username"];
        $this->rank_id = $off_siteUser["rank_id"];
        $this->afdeling = $off_siteUser["afdeling_id"];
        $this->user_id = $siteUser["user_id"];
        $this->motto = $off_siteUser["motto"];
    }

    public function getUserdata($username){
        include("../connect.php");
        $userData = $handle->prepare("SELECT * FROM user_ranks WHERE username = :username");
        $userData->execute(["username" => $username]);
        $userData = $userData->fetch(PDO::FETCH_ASSOC);
        
        if(!empty($userData)){
            $this->userstate = "EX";
            $this->gez_user = $userData["username"];
            $this->gez_rank_id = $userData["rank_id"];
            $this->gez_afdeling = $userData["afdeling_id"];
        }
        else{
            $this->userstate = "DNEX";
            $this->gez_user = $username;
            $this->gez_rank_id = 0;
        }

    }


    public function promote(string $gez_user){
        $this->getUserdata($gez_user);
        include("../connect.php");
        $username = $this->username;
        $rank_id = $this->gez_rank_id;
        $perm_id = $this->perm_id;
        $reason = htmlspecialchars($_POST["reason"]);
        $change_date = date('d/m/Y');
        $change_slachtoffer = $gez_user;
        $change_type = "Promotie";
        $changer = $this->username;
        $perm = get_perm($perm_id, $change_type, $rank_id);
        if($this->userstate == "DNEX"){
            if($perm == "allow"){
                $userinsert = $handle->prepare("INSERT INTO user_ranks (username, rank_id, node) VALUES(:username, :rank_id, :node)");
                $userinsert->execute(["username" => $gez_user, "rank_id" => 1, "node" => "B"]);
                $new_rank = 2;
                rank_audit($changer, $change_type, $change_slachtoffer, $rank_id, $new_rank,$reason, $change_date);
                header("Refresh:0");
            }
            #PDNEX
            else{
                ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error"); </script> <?php
            }
            
        }
        #PEX
        elseif($perm == "allow"){
            $new_rank_id = $rank_id + 1;
            $new_rank = $new_rank_id;
            $old_rank = $rank_id;
            $userpromote = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
            $userpromote->execute(["rank_id" => $new_rank_id, "username" => $gez_user]);
            rank_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank,$reason, $change_date);
            header("Refresh:0");
        }

        else{
            ?>
            <script>
                swal("No permission", "You don't have the appropriate permissions to complete this action.", "error");
                </script>
            <?php
        }
        }

    public function demote(string $gez_user){
        include("../connect.php");
        $this->getUserdata($gez_user);
        $perm_id = $this->perm_id;
        $rank_id = $this->gez_rank_id;
        $reason = htmlspecialchars($_POST["reason"]);
        $change_date = date('d/m/Y');
        $change_slachtoffer = $this->gez_user;
        $change_type = "Degradatie";
        $changer = $this->username;
        $perm = get_perm($perm_id, $change_type, $rank_id);
        if($this->userstate == "DNEX"){
            ?>
                <script>
                    swal("Error", "Deze gebruiker kan geen degradatie ontvangen!", "error");
                </script>
                <?php
        }
        #DEX
        else{
            if($rank_id >= 1){
                if($perm == "allow"){
                    $old_rank = $rank_id;
                    $new_rank_id = $rank_id - 1;
                    $userpromote = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
                    $userpromote->execute(["rank_id" => $new_rank_id, "username" => $this->gez_user]);
                    rank_audit($changer, $change_type, $change_slachtoffer, $old_rank, $new_rank_id,$reason, $change_date);
                    header("Refresh:0");
                }
                else{
                    ?><script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error");</script> <?php
                }
                
            }
            else{
                ?><script>swal("Error", "Deze gebruiker kan geen degradatie ontvangen!", "error");</script><?php
            }
        }
    }

    public function ontslagen($gez_user){
        include("../connect.php");
        $this->getUserdata($gez_user);
        $reason = htmlspecialchars($_POST["reason"]);
        $change_date = date('d/m/Y');
        $perm = get_perm($this->perm_id, "Ontslag", $this->gez_rank_id);

        if($this->userstate == "DNEX"){
            ?><script>swal("Error", "Deze gebruiker kan geen ontslag ontvangen!", "error");</script><?php
        }

        if($perm == "allow"){
            $userpromote = $handle->prepare("UPDATE user_ranks SET rank_id = :rank_id WHERE username = :username");
            $userpromote->execute(["username" => $this->gez_user, "rank_id" => -1]);
            rank_audit($this->username, "Ontslag", $this->gez_user, $this->gez_rank_id, -1 ,$reason, $change_date);
            header("Refresh:0");
        }

        else{
            ?> <script> swal("No permission", "You don't have the appropriate permissions to complete this action.", "error"); </script> <?php
        }
    }

    public function promoCount($username){
        include("../connect.php");
        $promoCount = $handle->prepare("SELECT * FROM audit_log WHERE changer = :username");
        $promoCount->execute(["username" => $username]);
        $promoCount = $promoCount->rowCount();
        return $promoCount;
    }
}