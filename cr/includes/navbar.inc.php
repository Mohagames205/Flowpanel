<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" href="home.php">FlowPanel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="home.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profiel</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Lijsten</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="lijsten.php?functie=promolijst">Promotielijst</a>
          <a class="dropdown-item" href="lijsten.php?functie=ranglijst">Ranglijst</a>
          <a class="dropdown-item" href="lijsten.php?functie=werklijst">Werknemers</a>
        </div>
      </li>
    </ul>
    <span class="navbar-text">
    <a href="logout.php"><span class="fas fa-sign-out-alt"></span> Uitloggen</a>
    </span>
  </div>
</nav>