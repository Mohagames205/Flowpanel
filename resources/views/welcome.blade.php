<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Inlog pagina om toegang te krijgen tot het staffpaneel">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <title>Flowpanel | Login</title>

    <!-- Custom styles for this template -->
    <link href="css/login.css" rel="stylesheet">
</head>

<body>

<div class="se-pre-con"></div>
<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Tabs Titles -->

        <h2 class="active"> Sign In </h2>

        <!-- Icon -->
        <div class="fadeIn first">

            <img src="assets/profile.svg" id="icon" alt="User Icon" />
        </div>

    <!-- Login Form -->
        <form method="POST">
            <input type="text" name="username" id="login" class="fadeIn second"  placeholder="login">
            <input type="password" name="password" id="password" class="fadeIn third" placeholder="password">
            <input type="submit" class="fadeIn fourth" value="Log In" name="login">
            <p> Nog geen account? Klik hier om eentje aan te maken!</p>
        </form>
        <!-- Remind Passowrd -->
        <div id="formFooter">

            <p class="underlineHover"> © Flowpanel 2019-2020 </p>
        </div>

    </div>
</div>
</body>
</html>
