<?php
    require_once 'app/requires/DB.php';
    require_once 'app/requires/SiteSetting.php';
    require_once 'app/requires/Frontend.php';

    //error_reporting(0);
    date_default_timezone_set('Asia/Dhaka');
    ob_start();

    $siteSetting    = new Trainer\SiteSetting();
    $siteInfoData   = $siteSetting->read();

    $frontendData   = new Frontend();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?=$siteInfoData->trainer_name; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="icon" type="image/x-icon" href="<?='app/'.$siteInfoData->icon; ?>">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/venobox/venobox.css" rel="stylesheet">
  <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Mobile nav toggle button ======= -->
  <button type="button" class="mobile-nav-toggle d-xl-none"><i class="icofont-navigation-menu"></i></button>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex flex-column justify-content-center">

    <nav class="nav-menu">
      <ul>
        <li class="active"><a href="#hero"><i class="bx bx-home"></i> <span>Home</span></a></li>
        <li><a href="#about"><i class="bx bx-user"></i> <span>About</span></a></li>
        <li><a href="#stats"><i class="icofont-bulb-alt"></i><span>Stats</span></a></li>
        <li><a href="#achievement"><i class="icofont-badge"></i><span>Achievements</span></a></li>
        <li><a href="#ratings"><i class="icofont-ui-rating"></i> <span>Ratings</span></a></li>
        <li><a href="http://127.0.0.1/gym_trainer_personal_app/app/"><i class="icofont-login"></i><span>App</span></a></li>

      </ul>
    </nav><!-- .nav-menu -->

  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-center"
           style="background-image: url(<?='app/'.$siteInfoData->banner; ?>);">
    <div class="container" data-aos="zoom-in" data-aos-delay="100">
      <h1><?=$siteInfoData->trainer_name; ?></h1>

      <div class="social-links">
          <?php
            $socialMedia = $frontendData->getTrainerSocialMedia($siteInfoData->trainer_id);
            if($socialMedia!=null){
                foreach ($socialMedia as $val){
          ?>
                    <a href="<?=$val->link; ?>" ><?=$val->icon; ?></a>
          <?php } } ?>

      </div>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= About Section ======= -->
      <?php
          $trainerAbout = $frontendData->getTrainerAbout($siteInfoData->trainer_id);
          if($trainerAbout!=null){
      ?>
    <section id="about" class="about">
      <div class="container" data-aos="fade-up">

        <div class="section-title mb-3">
          <h2>About</h2>
          <p>
              <?=$trainerAbout->about; ?>
          </p>
        </div>

        <div class="row">
          <div class="col-lg-4">
            <img src="app/images/trainer/<?=$trainerAbout->profile_picture; ?>" class="img-fluid" alt="image">
          </div>
          <div class="col-lg-8 pt-4 pt-lg-0 content mt-4">
            <div class="row">
              <div class="col-lg-6">
                <ul>
                  <li><i class="icofont-rounded-right"></i> <strong>Name:</strong>
                      <?=$trainerAbout->name; ?></li>
                  <li><i class="icofont-rounded-right"></i> <strong>Debut date:</strong>
                      <?=date('d M Y', strtotime($trainerAbout->debut_date)); ?></li>
                  <li><i class="icofont-rounded-right"></i> <strong>Email:</strong>
                      <?php
                        $email = $trainerAbout->email!=null ? $trainerAbout->email : 'N/A';
                        echo $email;
                      ?>
                  </li>
                </ul>
              </div>
              <div class="col-lg-6">
                <ul>
                  <li><i class="icofont-rounded-right"></i> <strong>Age:</strong>
                      <?php
                        if($trainerAbout->dob!=null){
                            $age = date_diff(date_create(date('Y-m-d')), date_create($trainerAbout->dob));
                            $age = $age->format('%y');
                            echo $age;
                        }
                        else{
                            echo 'N/A';
                        }
                      ?>
                  </li>
                  <li><i class="icofont-rounded-right"></i> <strong>Degree:</strong>
                      <?php
                        $education = $trainerAbout->education!=null ? $trainerAbout->education : 'N/A';
                        echo $education;
                      ?>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- End About Section -->
     <?php } ?>


      <?php

        $stats = $frontendData->getStats();
        if($stats!=null){

      ?>
    <!-- ======= Facts Section ======= -->
    <section id="stats" class="facts">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Stats</h2>
        </div>

        <div class="row">

          <div class="col-lg-3 col-md-6">
            <div class="count-box">
                <i class="icofont-users"></i>
              <span data-toggle="counter-up"><?=$stats->total_clients; ?></span>
              <p>Trained Clients</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
            <div class="count-box">
                <i class="icofont-gym-alt-3"></i>
              <span data-toggle="counter-up"><?=$stats->total_gyms; ?></span>
              <p>Attended GYMs</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
                <i class="icofont-badge"></i>
              <span data-toggle="counter-up"><?=$stats->total_ach; ?></span>
              <p>Achievements</p>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
                <i class="icofont-notepad"></i>
              <span data-toggle="counter-up"><?=$stats->total_routines; ?></span>
              <p>Created Routines</p>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Facts Section -->

    <?php } ?>

   <?php

    $achievements = $frontendData->getAchievements($siteInfoData->trainer_id);

    if($achievements!=null){

   ?>
    <!-- ======= Resume Section ======= -->
    <section id="achievement" class="resume">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Achievements</h2>
        </div>

        <div class="row">
            <?php $achCounter = 0;
            foreach($achievements as $val){
                  if($achCounter%2==0){
            ?>
                      <div class="col-lg-6">
                          <h3 class="resume-title text-uppercase"><?=$val->type; ?></h3>
                          <div class="resume-item">
                              <h4><?=$val->title; ?></h4>
                              <h5>RECEIVED: <?=date('d M Y', strtotime($val->attained_date)); ?></h5>
                              <p><em>REMARK: <?=$val->remark.'/5'; ?></em></p>
                          </div>
                      </div>
            <?php }else{?>
                      <div class="col-lg-6">
                          <h3 class="resume-title text-uppercase"><?=$val->type; ?></h3>
                          <div class="resume-item">
                              <h4><?=$val->title; ?></h4>
                              <h5>RECEIVED: <?=date('d M Y', strtotime($val->attained_date)); ?></h5>
                              <p><em>REMARK: <?=$val->remark.'/5'; ?></em></p>
                          </div>
                      </div>
            <?php } ++$achCounter; } unset($achCounter); ?>

        </div>

      </div>
    </section><!-- End Resume Section -->
    <?php } ?>


      <?php
        $testimonials = $frontendData->getTrainerTestimonial($siteInfoData->trainer_id);
        if($testimonials!=null){
      ?>
    <!-- ======= Testimonials Section ======= -->
    <section id="ratings" class="testimonials section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Testimonials</h2>
        </div>

        <div class="owl-carousel testimonials-carousel" data-aos="zoom-in" data-aos-delay="100">

            <?php
               foreach ($testimonials as $val){
            ?>
          <div class="testimonial-item">
            <img src="<?php if($val->client_dp==null || empty($val->client_dp)){ echo 'app/images/default_image.png'; }else{ echo 'app/images/client/'.$val->client_dp; } ?>" class="testimonial-img" alt="">
            <h3><?=$val->client_name; ?></h3>
            <h4><?=$val->star.'/5'; ?></h4>
            <p>
              <i class="bx bxs-quote-alt-left quote-icon-left"></i>
              <?=$val->remark; ?>
              <i class="bx bxs-quote-alt-right quote-icon-right"></i>
            </p>
          </div>

         <?php } ?>

        </div>

      </div>
    </section><!-- End Testimonials Section -->
      <?php } ?>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <h3><?=$siteInfoData->trainer_name; ?></h3>

      <div class="social-links">
          <?php
          if($socialMedia!=null){
              reset($socialMedia);
            foreach ($socialMedia as $val){
          ?>
                <a href="<?=$val->link; ?>" ><?=$val->icon; ?></a>
          <?php } } ?>

      </div>
      <div class="copyright">
        &copy; Copyright <strong><span><?=$siteInfoData->title; ?></span></strong>. All Rights Reserved
      </div>
      <div class="credits">
            <a href="https://www.google.com/search?q=saleh+ibne+omar"><i class="icofont-code-alt"></i>&ensp;DEVELOPER's INFO</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top"><i class="bx bx-up-arrow-alt"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>

<?php
    unset($socialMedia, $testimonials, $trainerAbout, $achievements, $stats);

    DB::getDb()->destroyConn();
    ob_end_flush();
?>