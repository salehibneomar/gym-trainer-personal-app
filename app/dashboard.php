<?php
include 'includes/main_app_header.php';

?>
    <title><?=$siteTitle;?> | Dashboard</title>
<?php
include 'includes/main_app_common_css.php';
?>
  <!-- Navbar -->
  <?php
    include 'includes/main_app_navbar.php';
  ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
    include 'includes/main_app_sidebar.php';
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h5 class="m-0 text-uppercase">Dashboard</h5>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content mb-5">
      <div class="container-fluid">
        <!-- Info boxes -->
        <?php if($_SESSION['user']['type']==='trainer'){
            $trainerDashData = (new Dashboard\Dashboard())->trainerDashboardData();
            ?>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Clients</span>
                            <span class="info-box-number"><?=$trainerDashData->total_clients; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="far fa-sticky-note"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Routines</span>
                            <span class="info-box-number"><?=$trainerDashData->total_routines; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-utensils"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Diets</span>
                            <span class="info-box-number"><?=$trainerDashData->total_diets; ?></span>
                        </div>

                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-star-half-alt"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Ratings</span>
                            <span class="info-box-number"><?=$trainerDashData->total_ratings; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
            </div>
        <?php }else if($_SESSION['user']['type']==='client'){
            $clientDashData = (new Dashboard\Dashboard())->clientDashboardById($_SESSION['user']['id']);
            ?>
            <div class="row">
                <a <?php if($clientDashData['routine']!=null){ echo 'href=client-workout.php?action=workout_today&view_id='.$clientDashData['routine']->id; } ?> class="d-block col-sm-12 col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-dumbbell"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                              <?php
                                if($clientDashData['routine']!=null){ echo 'Today\'s Workouts'; }
                                else{ echo 'No Active Routine'; }
                              ?>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
                <!-- /.col -->
                <a <?php if($clientDashData['routine']!=null){ echo 'href=client-workout.php?action=view&view_id='.$clientDashData['routine']->id; } ?> class="d-block ol-sm-12 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="far fa-sticky-note"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                <?php
                                if($clientDashData['routine']!=null){ echo 'Current Routine'; }
                                else{ echo 'No Active Routine'; }
                                ?>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <a <?php if($clientDashData['diet']!=null){ echo 'href=client-diet.php?action=view&view_id='.$clientDashData['diet']->id; } ?> class="d-block col-sm-12 col-md-4">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-utensils"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                <?php if($clientDashData['diet']!=null){ echo 'Current Diet'; }else{ echo 'No Active Diet'; }  ?>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
                <!-- /.col -->

                <!-- /.col -->
            </div>
        <?php } ?>
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
include 'includes/main_app_common_js.php';
?>

<?php
include 'includes/main_app_footer.php';
?>
