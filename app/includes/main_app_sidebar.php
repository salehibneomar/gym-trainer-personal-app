<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard.php" class="brand-link">
      <img src="<?=$siteIcon;?>" alt="Logo" class="brand-image img-circle" style="opacity: .8">
      <span class="brand-text font-weight-light"><b><?=$siteTitle;?></b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php if($_SESSION['user']['type']==='trainer'){ ?>
          <li class="nav-header">PERSONAL</li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-hashtag"></i>
                    <p>
                        Social Media
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="social-media.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>View All</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="social-media.php?action=add_new" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add New</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            Achievements
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="achievement.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View All</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="achievement.php?action=add_new" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                    </ul>
                </li>

          <li class="nav-header">WORKPLACE</li>
             <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-dumbbell"></i>
                        <p>
                            GYM
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="gym.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View All</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="gym.php?action=add_new" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">PROFESSIONAL</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Clients
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">
                                <?php
                                 echo (new Client\Client())->getPendingCount($_SESSION['user']['id']);
                                ?>
                            </span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="client.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View All</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="client.php?action=add_new" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add New</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-running"></i>
                        <p>
                            Workout
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="workout.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View All</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="workout.php?action=add_new" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New Routine</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-utensils"></i>
                        <p>
                            Diet
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="diet.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>View All</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="diet.php?action=add_new" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New Plan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="rating.php" class="nav-link">
                        <i class="nav-icon far fa-star"></i>
                        <p>
                            Ratings
                            <i class="fas fa-angle-left right d-none"></i>
                            <span class="badge badge-info right">
                                <?php
                                echo (new Rating\Rating())->getPendingCount($_SESSION['user']['id']);
                                ?>
                            </span>
                        </p>
                    </a>
                </li>

            <?php } else if($_SESSION['user']['type']==='client'){ ?>

                <li class="nav-item">
                    <a href="client-workout.php" class="nav-link">
                        <i class="nav-icon fas fa-running"></i>
                        <p>
                            Workout Routine
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="client-diet.php" class="nav-link">
                        <i class="nav-icon fas fa-utensils"></i>
                        <p>
                            Diet
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="rate-trainer.php" class="nav-link">
                        <i class="nav-icon far fa-star"></i>
                        <p>
                            Rate Trainer
                        </p>
                    </a>
                </li>
            <?php } ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>