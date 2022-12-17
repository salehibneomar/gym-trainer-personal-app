<?php
include 'includes/auth_header.php';
$trainerId = $siteInfoData->trainer_id;
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

            <p class="login-box-msg">Register an account</p>

            <form method="post" action="">
                <div class="form-group mb-3">
                    <select class="form-control select2" style="width: 100%;" name="gym_id" required>
                        <option value="" >-- Select Your GYM--</option>
                        <?php
                        $gym = new Trainer\Gym($trainerId);
                        $allGymData = $gym->readAll();
                        if($allGymData!=null){
                            foreach ($allGymData as $row){
                                if($row->id==1){ continue; }
                                ?>
                                <option value="<?=$row->id;?>" ><?=$row->name;?></option>
                            <?php } } ?>
                    </select>
                    <span class="form-text text-sm text-info">If you don't see your GYM listed here contact your Trainer.</span>
                </div>
                <div class="input-group mb-3">
                    <input type="tel" class="form-control" placeholder="Name" name="name" required maxlength="60" minlength="3" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="tel" class="form-control" placeholder="Phone" name="phone" required maxlength="30" minlength="4" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-phone"></i>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="pwd" required minlength="6" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-key"></i>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Retype Password" name="retyped_pwd" required minlength="6" autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fas fa-key"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block" name="registerBtn">Register&ensp;<i class="fas fa-user-plus"></i></button>
                </div>
            </form>

            <?php

                if(isset($_POST['registerBtn'])){
                    $hasEmpty = false;
                    $errors   = array();
                    foreach ($_POST as $key=> $val){
                        $_POST[$key] = trim($val);
                        if((empty($_POST[$key]) || !isset($_POST[$key])) && $key!='registerBtn'){
                            $hasEmpty = true;
                        }
                    }

                    if($hasEmpty){
                        $errors[] = 'Some fields were empty!';
                    }
                    else{
                        if(mb_strlen($_POST['name'])<3 && mb_strlen($_POST['name']>60)){
                            $errors[] = 'Name should contain at least 3 characters and not more than 60 characters!';
                        }

                        if($_POST['pwd']!==$_POST['retyped_pwd']){
                            $errors[] = 'Password and Retyped did not match!';
                        }
                    }

                    if(empty($errors)){
                        $_POST['name'] = ucwords($_POST['name']);
                        $registrationData = array();
                        unset($_POST['retyped_pwd'], $_POST['registerBtn']);

                        foreach ($_POST as $key=> $val){
                            $registrationData[$key] = $val;
                        }

                        $registrationData['acc_status'] = 'pending';
                        $registrationData['acc_creation_date'] = date('Y-m-d');
                        $registrationData['trainer_id'] = $trainerId;

                        $client = new Client\Client();

                        $result = $client->create($registrationData);


                        if($result==1){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('Your account has been successfully created, wait for account activation!');
                        }
                        else if($result==1062){
                            $errors[] = 'The phone number you tried to insert has already been registered with another account!';
                        }
                        else{
                            $errors[] = 'Failed to create account, try again later!';
                        }
                    }

                    if(!empty($errors)){
                        $_SESSION['alert']['type'] = 'danger';
                        $_SESSION['alert']['msg']  = $errors;
                    }

                    header('Location: register.php');
                    exit();
                }

            ?>

            <p class="mb-0 mt-3">
                <a href="index.php" class="text-info">Already have an account? Login</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->
<?php
include 'includes/auth_footer.php';
?>


