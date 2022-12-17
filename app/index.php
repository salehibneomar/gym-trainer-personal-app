<?php
  include 'includes/auth_header.php';

?>
  <div class="login-box">
    <div class="login-logo">
      <img src="<?=$siteIcon;?>" alt="" width="50" height="50">
      <b><?=$siteTitle;?></b>
    </div>

      <?php
        include 'includes/alert.php';
      ?>

  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
    
      <p class="login-box-msg">Sign in with your credentials</p>

      <form action="" method="post">

        <div class="input-group mb-3">
          <input type="tel" class="form-control" placeholder="Phone" name="phone" required maxlength="30" minlength="4" autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-4">
          <input type="password" class="form-control" placeholder="Password" name="pwd" required minlength="6" autocomplete="off">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
          <div class="form-group mb-4">
              <select class="form-control" name="userType" required>
                  <option value="">--Select User Type--</option>
                  <option value="trainer">Trainer</option>
                  <option value="client">Client</option>
              </select>
          </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block" name="loginBtn">Sign In<i class="fas fa-sign-out-alt ml-2"></i></button>
        </div>

      </form>

        <?php

            if(isset($_POST['loginBtn'])){
                $phone = trim($_POST['phone']);
                $pwd   = trim($_POST['pwd']);
                $errors = array();

                if(empty($phone)){
                    array_push($errors, 'Phone is empty!');
                }
                if(empty($pwd)){
                    array_push($errors, 'Password is empty!');
                }

                if(!isset($_POST['userType'])){
                    array_push($errors, 'Select an user type!');
                }

                if(empty($errors)){
                    if($_POST['userType']=='trainer'){
                        $trainer = new Trainer\Trainer();
                        $data = $trainer->auth($phone, $pwd);

                        if($data==null){
                            array_push($errors, 'Invalid credentials!');
                        }
                        else if($data['acc_status']=='locked'){
                            array_push($errors, 'Your ID is locked contact developer!');
                        }
                        else if($data['acc_status']=='unlocked'){
                            $_SESSION['user'] = $data;
                            $_SESSION['user']['type'] = 'trainer';
                            $_SESSION['user']['profileImageDir'] = $trainer->getProfileImageDir();
                            var_dump($_SESSION['user']);
                            header('Location: dashboard.php');
                            exit();
                        }
                        else{
                            array_push($errors, 'Unknown error occurred!');
                        }
                    }
                    else if($_POST['userType']=='client'){
                        $client = new Client\Client();
                        $data = $client->auth($phone, $pwd);

                        if($data==null){
                            array_push($errors, 'Invalid credentials!');
                        }
                        else if($data['acc_status']=='locked'){
                            array_push($errors, 'Your ID is locked or your trainer has retired from your gym, contact your trainer of more information!');
                        }
                        else if($data['acc_status']=='pending'){
                            array_push($errors, 'Your ID is not activated yet, contact your trainer!');
                        }
                        else if($data['acc_status']=='active'){
                            $_SESSION['user'] = $data;
                            $_SESSION['user']['type'] = 'client';
                            $_SESSION['user']['profileImageDir'] = $client->getProfileImageDir();
                            var_dump($_SESSION['user']);
                            header('Location: dashboard.php');
                            exit();
                        }
                        else{
                            array_push($errors, 'Error occurred, try again later!');
                        }
                    }
                }

                if(!empty($errors)){
                    $_SESSION['alert']['type'] = 'danger';
                    $_SESSION['alert']['msg']  = $errors;
                    header('Location: index.php');
                    exit();
                }

            }

        ?>

      <p class="mb-0 mt-3">
        <a href="register.php" class="text-info">Don't have an account? Register</a>
      </p>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<?php
  include 'includes/auth_footer.php';
?>

