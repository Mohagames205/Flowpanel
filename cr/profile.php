<?php
session_start();
$extra_css = "<link href='style/profile.css' rel='stylesheet' type='text/css'>";
if(isset($_SESSION["username"])){
    $username = $_SESSION["username"];
    require("includes/header.inc.php");
    $gebruiker = new User($username);
}

else{
    header("location:../index.php");
    die();
}

?>
<div class="container emp-profile">
            <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img">
                            <img src="https://cdn3.iconfinder.com/data/icons/fillies-small/64/id-card-512.png" alt=""/>
                            <div class="file btn btn-lg btn-primary">
                                Verander foto (soon)
                                <input type="file" name="file"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                                    <h5>
                                        <?php echo $gebruiker->username;?>
                                    </h5>
                                    <h6>
                                        <?php echo $gebruiker->motto;?>
                                    </h6>
                                    <p class="proile-rating">Aantal promoties gegeven <span><?php echo $gebruiker->promoCount($username);?></span></p>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Statistieken</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-8">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>User Id</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $gebruiker->user_id; ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Promotag</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $gebruiker->promo_tag; ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Rang</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo get_rank_name($gebruiker->rank_id);?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Afdeling</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $gebruiker->afdeling;?></p>
                                            </div>
                                        </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Aantal promoties gekregen</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>soon</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Aantal promoties gegeven</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p><?php echo $gebruiker->promoCount($username); ?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Aantal trainingen ontvangen</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>soon</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Aantal trainingen gegeven</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>soon</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Lid sinds</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Soon</p>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>           
        </div>