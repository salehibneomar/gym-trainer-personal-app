<?php
include 'includes/main_app_header.php';

if($_SESSION['user']['type']!=='trainer'){
    header('Location: dashboard.php');
    exit();
}

$action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : null;

?>
<title><?=$siteTitle;?>  | Site Settings</title>

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

                                <li class="nav-item"><a class="nav-link active" href="#app_name" data-toggle="tab">App Name</a></li>
                                <li class="nav-item"><a class="nav-link" href="#app_icon" data-toggle="tab">App Icon</a></li>
                                <li class="nav-item"><a class="nav-link" href="#app_banner" data-toggle="tab">App Banner</a></li>

                            </ul>
                            <div class="card-tools mr-2">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div><!-- /.card-header -->


                        <div class="card-body">
                            <div class="tab-content">

                                <div class="active tab-pane" id="app_name">
                                    <div class="row p-2">
                                        <div class="col-md-12 mb-4">
                                            <h5>Current Name:</h5>
                                            <p class="text-info"><?=$siteTitle;?></p>
                                        </div>
                                        <div class="col-md-12">
                                            <form class="row" method="post" action="site-settings.php?action=update_name">
                                                <div class="col-md-12 form-group">
                                                    <label>Change Name <span class="text-danger">*</span></label></label>
                                                    <input type="text" class="form-control" maxlength="20" minlength="3" name="title" required>
                                                    <span class="form-text text-sm text-info">
                                                        Keep it within 20 characters!
                                                    </span>
                                                </div>
                                                <div class="form-group col-md-12 text-right">

                                                    <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="app_icon">
                                    <div class="row p-2">
                                        <div class="col-md-12 mb-4">
                                            <h5>Current Icon: </h5>
                                            <img width="80" height="80" src="<?=$siteIcon;?>">
                                        </div>
                                        <div class="col-md-12">
                                            <form class="row" method="post" enctype="multipart/form-data" action="site-settings.php?action=update_icon">
                                                <div class="col-md-12 form-group">
                                                    <label>Change Icon <span class="text-danger">*</span></label></label>
                                                    <input id="icon-uploader" name="icon" type="file" accept="image/png" required>
                                                    <span class="mt-3 form-text text-sm text-info">We don't accept image size more than 64KB (Kilo bytes), and the image should be in png format!</span>
                                                </div>
                                                <div class="form-group col-md-12 text-right">

                                                    <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="app_banner">
                                    <div class="row p-2">
                                        <div class="col-md-12 mb-4">
                                            <h5>Current Banner: </h5>
                                            <img width="220" height="110" src="<?=$siteInfoData->banner;?>">
                                        </div>
                                        <div class="col-md-12">
                                            <form class="row" method="post"  enctype="multipart/form-data" action="site-settings.php?action=update_banner">
                                                <div class="col-md-12 form-group">
                                                    <label>Change Banner <span class="text-danger">*</span></label></label>
                                                    <input id="banner-uploader" name="banner" type="file" accept=".jpg, .jpeg" required>
                                                    <span class="mt-3 form-text text-sm text-info">We don't accept image size more than 3MB (Mega bytes)</span>
                                                </div>
                                                <div class="form-group col-md-12 text-right">

                                                    <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

    if($action=='update_name'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $title = trim($_POST['title']);
            $errors = array();

            if(empty($title)){
                $errors[] = 'App name cannot be empty!';
            }
            else if(mb_strlen($title)<3){
                $errors[] = 'App name should contain at least three characters!';
            }
            else if(mb_strlen($title)>20){
                $errors[] = 'App name should not contain more than 20 characters!';
            }

            if(empty($errors)){
                $result = $siteSetting->updateTitle($title);
                if($result){
                    $_SESSION['alert']['type'] = 'success';
                    $_SESSION['alert']['msg']  = array('App name updated successfully!');
                }
                else{
                    $errors[] = 'Failed to update app name!';
                }
            }

            if(!empty($errors)){
                $_SESSION['alert']['type'] = 'danger';
                $_SESSION['alert']['msg']  = $errors;
            }

            header('Location: site-settings.php');
            exit();
        }
    }
    else if($action=='update_icon'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $errors = array();
            $validIconSize  = 66000;

            $iconName         = $_FILES['icon']['name'];
            $iconTempName     = $_FILES['icon']['tmp_name'];
            $iconSize         = $_FILES['icon']['size'];
            $iconExt          = strtolower(pathinfo($iconName, PATHINFO_EXTENSION));

            if(!empty($iconName) && $iconSize>0){
                if($iconExt=='png'){
                    if($iconSize<=$validIconSize){
                        $finalIconName = strtolower("r".rand(1000, 9999)."_dt".date('YmdHis')."_".str_replace(" ","_",$iconName));

                        $iconData = array('iconTmpName'=>$iconTempName, 'iconName'=>$finalIconName);

                        $result = $siteSetting->updateIcon($iconData);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('App icon updated successfully!');
                        }
                        else{
                            $errors[] = 'Failed to update app icon!';
                        }
                    }
                    else{
                        $errors[] = 'Icon image size exceeds 64KB';
                    }
                }
                else{
                    $errors[] = 'Icon should be a png image file';
                }
            }
            else{
                $errors[] = 'Select a valid icon!';
            }

            if(!empty($errors)){
                $_SESSION['alert']['type'] = 'danger';
                $_SESSION['alert']['msg']  = $errors;
            }

            header('Location: site-settings.php');
            exit();
        }
    }
    else if($action=='update_banner'){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $errors = array();
            $validBannerExt     = array('jpg', 'jpeg');
            $validBannerSize    = 3200000;

            $bannerName         = $_FILES['banner']['name'];
            $bannerTempName     = $_FILES['banner']['tmp_name'];
            $bannerSize         = $_FILES['banner']['size'];
            $bannerExt          = strtolower(pathinfo($bannerName, PATHINFO_EXTENSION));

            if(!empty($bannerName) && $bannerSize>0){
                if(in_array($bannerExt, $validBannerExt)){
                    if($bannerSize<=$validBannerSize){
                        $replaceCharacters = array('(', ')', '*', '/', '\\', '|', ',', '%', '#', '@', '!', '~', '`', '+', ';', ':', '<', '>', '?', " ");
                        $finalBannerName = strtolower("r".rand(1000, 9999)."_dt".date('YmdHis')."_".str_replace($replaceCharacters,"_",$bannerName));

                        $bannerData = array('bannerName'=>$finalBannerName, 'bannerTmpName'=>$bannerTempName, 'previousBanner'=>$siteInfoData->banner);

                        $result = $siteSetting->updateBanner($bannerData);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('App banner updated successfully!');
                        }
                        else{
                            $errors[] = 'Failed to update app banner!';
                        }
                    }
                    else{
                       $errors[] = 'Banner size is very large, upload banner within 3MB size!';
                    }
                }
                else{
                    $errors[] = 'Invalid banner type, upload jpg/jpeg banner!';
                }
            }
            else{
                $errors[] = 'Select a valid banner!';
            }

            if(!empty($errors)){
                $_SESSION['alert']['type'] = 'danger';
                $_SESSION['alert']['msg']  = $errors;
            }

            header('Location: site-settings.php');
            exit();
        }
    }

?>

<?php
include 'includes/main_app_common_js.php';
?>

<script>
    $("#icon-uploader").fileinput({
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
        allowedFileExtensions: ["png"],
        maxFileSize: 64,
        fileActionSettings: {
            showRemove: false,
            showZoom: false,
            showUpload: false,
        },

    });

    $("#banner-uploader").fileinput({
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
        allowedFileExtensions: ["jpg", "jpeg"],
        maxFileSize: 3200,
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

