<?php
include 'includes/main_app_header.php';
require_once 'requires/Trainer.php';

    if($_SESSION['user']['type']!=='trainer'){
        header('Location: dashboard.php');
        exit();
    }

$trainerId     = $_SESSION['user']['id'];
$trainer       = new Trainer\Trainer($trainerId);

$action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : null;

?>
<title><?=$siteTitle;?>  | <?=$_SESSION['user']['name'];?></title>

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
    <div class="content-header mb-4"></div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content mb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-11 col-md-12 mx-auto">

                    <?php
                        include 'includes/alert.php';
                    ?>

                    <div class="card card-gray" >
                        <div class="card-header p-2">
                            <ul class="nav nav-pills card-title">
                                <li class="nav-item"><a class="nav-link active" href="#my-profile" data-toggle="tab">Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="#pro-pic" data-toggle="tab">Profile Picture</a></li>
                                <li class="nav-item"><a class="nav-link" href="#general" data-toggle="tab">General</a></li>
                                <li class="nav-item"><a class="nav-link" href="#pass" data-toggle="tab">Password</a></li>

                            </ul>
                            <div class="card-tools mr-2">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div><!-- /.card-header -->


                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="my-profile">
                                    <div class="w-100 text-center mb-4">

                                        <?php
                                        $profileImage = $_SESSION['user']['profile_picture']!=null ? $_SESSION['user']['profileImageDir'].$_SESSION['user']['profile_picture'] : 'images/default_image.png';

                                            ?>
                                        <img class="profile-page-propic" src="<?=$profileImage;?>">
                                        <h2 class="profile-username mt-3"><?=$_SESSION['user']['name'];?></h2>
                                        <h5 class="text-uppercase"><span class="badge badge-light"><?=$_SESSION['user']['type'];?></span></h5>

                                    </div>

                                        <div class="row">

                                            <div class="col-md-12 mx-auto">
                                                <ul class="list-group list-group-flush mb-3">
                                                    <li class="list-group-item p-3">
                                                        <b class="d-inline-block">My user ID</b>
                                                        <span class="d-inline-block float-right"><?=$_SESSION['user']['id'];?></span>
                                                    </li>
                                                    <li class="list-group-item p-3">
                                                        <b class="d-inline-block">Debut date</b>
                                                        <span class="d-inline-block float-right"><?=date('d M Y', strtotime($_SESSION['user']['debut_date']));?></span>
                                                    </li>
                                                    <li class="list-group-item p-3">
                                                        <b class="d-inline-block">Phone</b>
                                                        <span class="d-inline-block float-right"><?=$_SESSION['user']['phone'];?></span>
                                                    </li>                                                    <li class="list-group-item p-3">
                                                        <b class="d-inline-block">Email</b>
                                                        <span class="d-inline-block float-right">
                                                            <?php
                                                                echo $_SESSION['user']['email']!=null ? $_SESSION['user']['email'] : 'N/A';
                                                            ?>
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item p-3">
                                                        <b class="d-inline-block">Date of birth</b>
                                                        <span class="d-inline-block float-right">
                                                            <?php
                                                            echo $_SESSION['user']['dob']!=null ? date('d M Y', strtotime($_SESSION['user']['dob'])) : 'N/A';
                                                            ?>
                                                        </span>
                                                    </li>

                                                    <li class="list-group-item p-3">
                                                        <b class="d-block mb-3">Education</b>
                                                        <?php
                                                        echo $_SESSION['user']['education']!=null ? $_SESSION['user']['education'] : 'N/A';
                                                        ?>

                                                    </li>
                                                    <li class="list-group-item p-3">
                                                        <b class="d-block mb-3">About</b>
                                                        <?php
                                                        echo $_SESSION['user']['about']!=null ? $_SESSION['user']['about'] : 'N/A';
                                                        ?>
                                                    </li>

                                                </ul>
                                            </div>

                                        </div>

                                </div>

                                <div class="tab-pane" id="pro-pic">
                                    <p>Update Your Profile Picture <span class="text-danger">*</span></p>
                                    <form method="post" class="row" action="trainer-profile.php?action=update_propic" enctype="multipart/form-data">
                                        <div class="form-group col-md-12">
                                            <input id="image-uploader" name="profile_picture" type="file" accept=".jpg, .png, .jpeg" required>
                                            <span class="form-text text-sm text-info">We don't accept image size more than 2MB (Mega bytes), kindly reduce the image size then upload!</span>
                                        </div>

                                        <div class="form-group col-md-12 text-right">

                                        <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>

                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane" id="general">
                                    <p>Update Your Information</p>
                                    <form method="post" class="row" action="trainer-profile.php?action=update_gnrl">
                                        <div class="form-group col-md-12">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="name" minlength="2" maxlength="60" value="<?=$_SESSION['user']['name']; ?>" required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input class="form-control" type="tel" name="phone" minlength="4"  maxlength="30" value="<?=$_SESSION['user']['phone']; ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email</label>
                                            <input class="form-control" type="email" name="email" minlength="4"  maxlength="150" <?php if($_SESSION['user']['email']!=null){ echo 'value="'.$_SESSION['user']['email'].'"'; } ?> >
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Date of birth</label>
                                            <input class="form-control" type="date" name="dob" <?php if($_SESSION['user']['dob']!=null){ echo 'value="'.$_SESSION['user']['dob'].'"'; } ?> >
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Debut Date <span class="text-danger">*</span></label>
                                            <input class="form-control" type="date" name="debut_date" value="<?=$_SESSION['user']['debut_date']; ?>"  required>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Last Educational Status</label>
                                            <input class="form-control" type="text" name="education" maxlength="250" <?php if($_SESSION['user']['education']!=null){ echo 'value="'.$_SESSION['user']['education'].'"'; } ?> >
                                            <span class="form-text text-sm text-info">
                                                Keep it within 250 characters!</span>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>About</label>
                                            <textarea class="form-control" name="about" maxlength="1500"><?php if($_SESSION['user']['about']!=null){ echo $_SESSION['user']['about']; } ?></textarea>
                                            <span class="form-text text-sm text-info">Keep it within 1500 characters!</span>
                                        </div>
                                        <div class="form-group col-md-12 text-right">

                                            <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane" id="pass">
                                    <p>Update Your Password</p>
                                    <form method="post" class="row" action="trainer-profile.php?action=update_pwd">
                                        <div class="form-group col-md-12">
                                            <label>Previous password</label>
                                            <input class="form-control" type="password" name="prev_pwd" minlength="6" required>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>New password</label>
                                            <input class="form-control" type="password" name="new_pwd" minlength="6"  required>
                                            <span class="form-text text-sm text-info">
                                                Password should carry minimum of 6 characters!</span>
                                        </div>
                                        <div class="form-group col-md-12 mb-5">
                                            <label>Retype new password</label>
                                            <input class="form-control" type="password" name="retyped_new_pwd" minlength="6" required>
                                        </div>

                                        <div class="form-group col-md-12 text-right">

                                            <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>

                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php

    if($action=='update_propic'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $errors = array();
            $validImageExt     = array('jpg', 'jpeg', 'png');
            $validImageSize    = 2200000;

            $imageName         = $_FILES['profile_picture']['name'];
            $imageTempName     = $_FILES['profile_picture']['tmp_name'];
            $imageSize         = $_FILES['profile_picture']['size'];
            $imageExt          = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            if(!empty($imageName) && $imageSize>0){
                if(in_array($imageExt, $validImageExt)){
                    if($imageSize<=$validImageSize){
                        $finalImageName = strtolower(str_replace(" ", "",$_SESSION['user']['name'])."_r".rand(1000, 9999)."_dt".date('YmdHis')."_".str_replace(" ","_",$imageName));

                        $imageData = array('imageName'=>$finalImageName, 'imageTempName'=>$imageTempName, 'imageExt'=>$imageExt, 'previousProPic'=>$_SESSION['user']['profile_picture']);

                        $result = $trainer->updateProfilePicture($_SESSION['user']['id'], $imageData);

                        if($result){
                            $_SESSION['user']['profile_picture'] = $finalImageName;
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('Profile picture updated successfully!');
                        }
                        else{
                            $_SESSION['alert']['type'] = 'danger';
                            $_SESSION['alert']['msg']  = array('Failed to update profile picture (Possible reason: Image extension renamed manually)!');
                        }
                    }
                    else{
                        array_push($errors, 'Image size is very large, upload image within 2MB size!');
                    }
                }
                else{
                    array_push($errors, 'Invalid image type, upload jpg/jpeg/png images!');
                }
            }
            else{
                array_push($errors, 'Select a valid image!');
            }

            if(!empty($errors)){
                $_SESSION['alert']['type'] = 'danger';
                $_SESSION['alert']['msg']  = $errors;
            }

            header('Location: trainer-profile.php');
            exit();
        }
    }
    else if($action=='update_pwd'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            extract($_POST);
            $errors = array();

            if(empty(trim($prev_pwd))){
                array_push($errors, 'Previous password field is empty!');
            }

            if(empty(trim($new_pwd))){
                array_push($errors, 'New password field is empty!');
            }
            else if(mb_strlen($new_pwd)<6){
                array_push($errors, 'New password should not have less than 6 characters!');
            }

            if(empty(trim($retyped_new_pwd))){
                array_push($errors, 'Retype password field is empty!');
            }

            if($new_pwd!=$retyped_new_pwd){
                array_push($errors, 'New password and Retyped new password did not match!');
            }

            if(empty($errors)){
                $password = array('newPwd'=>$new_pwd, 'prevPwd'=>$prev_pwd);
                $result = $trainer->updatePassword($_SESSION['user']['id'], $password);
                if($result== -1){
                    array_push($errors, 'Inputted previous password did not match with the existing record!');
                }
                else if($result==1){
                    $_SESSION['alert']['type'] = 'success';
                    $_SESSION['alert']['msg']  = array('Password updated successfully!');
                }
                else{
                    array_push($errors, 'Failed to update password!');
                }
            }

            if(!empty($errors)){
                $_SESSION['alert']['type'] = 'danger';
                $_SESSION['alert']['msg']  = $errors;
            }

            header('Location: trainer-profile.php');
            exit();

        }
    }
    else if($action=='update_gnrl'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $errors = array();

            if(empty(trim($_POST['name']))){
                array_push($errors, 'Name is empty!');
            }
            else if(mb_strlen($_POST['name'])<2 || mb_strlen($_POST['name'])>60){
                array_push($errors, 'Name size should be between 2 and 60 characters!');
            }

            if(empty(trim($_POST['phone']))){
                array_push($errors, 'Phone number is empty!');
            }

            if(empty($errors)){
                $_POST['name'] = ucwords($_POST['name']);
                $generalUpdateData = array();

                foreach ($_POST as $key=> $val){
                    $val = trim($val);

                    if($key=='phone' && $val==$_SESSION['user']['phone']){
                        continue;
                    }

                    if(!empty($val)){
                        $generalUpdateData[$key] = $val;
                    }
                    else if(empty($val)){
                        $generalUpdateData[$key] = null;
                    }
                }

                $result = $trainer->updateGeneral($trainerId, $generalUpdateData);

                if($result==1){
                    foreach ($generalUpdateData as $key=> $val){
                        $_SESSION['user'][$key] = $val;
                    }
                    $_SESSION['alert']['type'] = 'success';
                    $_SESSION['alert']['msg']  = array('Your information has been updated successfully!');
                }
                else if($result==1062){
                    array_push($errors, 'The phone number you tried to insert has already been registered with another account!');
                }
                else{
                    array_push($errors, 'Failed to update your information!');
                }

            }

            if(!empty($errors)){
                $_SESSION['alert']['type'] = 'danger';
                $_SESSION['alert']['msg']  = $errors;
            }

            header('Location: trainer-profile.php');
            exit();

        }
    }

?>

<?php
    include 'includes/main_app_common_js.php';
?>

<script>
    $("#image-uploader").fileinput({
        'uploadUrl': '#',
        dropZoneTitle: 'Drag and Drop an Image',
        dropZoneClickTitle: '<br> (Or Click to Select an Image)',
        dropZoneEnabled: true,
        browseOnZoneClick: true,
        showRemove: true,
        showClose: false,
        removeIcon: '<i class="fas fa-trash-alt"></i>',
        removeClass: 'btn btn-danger',
        showUpload: false,
        showCancel: false,
        showCaption: false,
        showBrowse: false,
        allowedFileTypes: ["image"],
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        maxFileSize: 2200,
        fileActionSettings: {
            showRemove: false,
            showZoom: false,
            showUpload: false,
        },

    });

</script>
<?php
include 'includes/main_app_footer.php';
?>

