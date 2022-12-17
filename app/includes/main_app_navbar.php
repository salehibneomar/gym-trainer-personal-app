<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link text-lg text-uppercase" data-toggle="dropdown" href="#" style="">
          <i class="fas fa-user-circle"></i>&ensp;<?=explode(" ",$_SESSION['user']['name'])[0];?>&ensp;<i class="fas fa-caret-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <?php if($_SESSION['user']['type']==='trainer'){ ?>
                <a href="trainer-profile.php" class="dropdown-item">
                    <i class="fas fa-address-card mr-3"></i>Profile
                </a>
                <a href="site-settings.php" class="dropdown-item">
                    <i class="fas fa-sliders-h mr-3"></i>Settings
                </a>
            <?php }
                else if($_SESSION['user']['type']==='client'){
            ?>
                <a href="client-profile.php" class="dropdown-item">
                    <i class="fas fa-address-card mr-3"></i>Profile
                </a>
            <?php } ?>
          <div class="dropdown-divider"></div>
          <a href="logout.php" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-3"></i>Logout
          </a>
        </div>
      </li>
    </ul>
</nav>