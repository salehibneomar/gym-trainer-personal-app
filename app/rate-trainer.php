<?php
include 'includes/main_app_header.php';

if($_SESSION['user']['type']!=='client'){
    header('Location: dashboard.php');
    exit();
}

$clientId     = $_SESSION['user']['id'];
$rating       = new Rating\Rating();

?>
<title><?=$siteTitle;?>  | Post Review</title>

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

                    if(!$rating->exists($clientId)){
                        $action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : null;
                        if($action==null){
                    ?>
                            <div class="card card-gray" >
                                <div class="card-header p-3">
                                    <h3 class="card-title">Rate Your Trainer</h3>
                                    <div class="card-tools mr-2">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div><!-- /.card-header -->


                                <div class="card-body">
                                    <form method="post" action="rate-trainer.php?action=insert">
                                        <div class="row">
                                            <div class="col-md-12 form-group clearfix">
                                                <label class="d-block mb-3">Star</label>

                                                <?php
                                                for($i=1; $i<=5; ++$i){
                                                    ?>
                                                    <div class="icheck-danger d-inline mr-4">
                                                        <input type="radio" id="star_<?=$i;?>" name="star"  value="<?=$i;?>" required>
                                                        <label for="star_<?=$i;?>"><?=$i;?></label>
                                                    </div>

                                                <?php } unset($i); ?>


                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>Remark</label>
                                                <textarea class="form-control" name="remark" minlength="1" maxlength="250" required></textarea>
                                                <span class="form-text text-sm text-info">Keep it short! (Within 250 characters)</span>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="text-warning">NOTE: Rating can be submitted once, so think before submitting.</p>
                                            </div>
                                            <div class="col-md-12 form-group text-right">
                                                <button type="submit" class="btn btn-primary">Submit&ensp;<i class="fas fa-paper-plane"></i></button>


                                            </div>
                                        </div>
                                    </form>
                                </div><!-- /.card-body -->
                            </div>
                    <?php }
                    else if($action=='insert'){
                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            extract($_POST);
                            $errors = array();

                            if(!isset($star) || empty(trim($remark))){
                                $errors[] = 'One or both of the fields were empty!';
                            }
                            else if(mb_strlen($remark)>250){
                                $errors[] = 'Remark should not contain more than 250 characters!';
                            }

                            if($star<1 || $star>5){
                                $errors[] = 'Invalid stars!';
                            }

                            if(empty($errors)){
                                $insertionData = array('star'=>$star, 'remark'=>$remark, 'date_posted'=>date('Y-m-d'), 'client_id'=>$clientId, 'trainer_id'=>$_SESSION['user']['trainer_id']);

                                $result = $rating->create($insertionData);

                                if($result){
                                     $_SESSION['alert']['type'] = 'success';
                                     $_SESSION['alert']['msg']  = array('Your rating has been submitted successfully');
                                }
                                else{
                                    $errors[] = 'Failed to submit rating!';
                                }
                            }

                            if(!empty($errors)){
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = $errors;
                            }

                            header('Location: rate-trainer.php');

                        }
                    }else{ header('Location: rate-trainer.php'); } }else{ ?>
                        <div class="alert alert-default-success border border-success text-center p-5" role="alert">
                            <h4>You have already submitted a rating! <br> If you think that was by mistake then contact your trainer.</h4>
                        </div>
                    <?php } ?>
                    <!-- /.card -->
                </div>
            </div>
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


