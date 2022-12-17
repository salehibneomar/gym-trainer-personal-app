<?php
include 'includes/main_app_header.php';
require_once 'requires/Diet.php';

    if($_SESSION['user']['type']!=='trainer'){
        header('Location: dashboard.php');
        exit();
    }

    $diet      = new Diet\Diet();
    $action    = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : "view_all";
?>

<title><?=$siteTitle;?>  | Diet</title>

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

                    if($action=='view_all'){ ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">All Diet Plans</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body table-overflow-fix">
                                <table id="data_tables" class="w-100 table table-bordered">
                                    <thead class="bg-gray">
                                    <tr>
                                        <th style="width: 8%">ID#</th>
                                        <th style="width: 20%">For</th>
                                        <th >Title</th>
                                        <th style="width: 8%">Status</th>
                                        <th style="width: 15%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }else if($action=='add_new'){
                            $step = isset($_GET['step']) && is_numeric(trim($_GET['step'])) ? $_GET['step'] : 1;
                            if($step==1){
                        ?>
                                <div class="card card-gray" >
                                    <div class="card-header p-3">
                                        <h3 class="card-title">Create New Diet Plan For Client</h3>
                                        <div class="card-tools mr-2">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div><!-- /.card-header -->

                                    <div class="card-body">
                                        <form class="row" method="post" action="diet.php?action=add_new&step=2">
                                            <div class="form-group col-md-12">
                                                <label>Enter ID of client</label>
                                                <input type="number" name="client_id" class="form-control" min="1" required>
                                            </div>
                                            <div class="form-group col-md-12 text-right">
                                                <button type="submit" class="btn btn-primary" name="stepOneAuth">Submit</button>
                                            </div>
                                        </form>
                                    </div><!-- /.card-body -->

                                </div>
                        <?php }else if($step==2){
                                $client_id = $client_name = null;
                                if(isset($_POST['stepOneAuth'])){
                                    $errors = array();
                                    if(empty(trim($_POST['client_id']))){
                                        $errors[] = 'Client ID was empty!';
                                    }
                                    else{
                                        $existById = (new Client\Client())->existById($_POST['client_id']);
                                        if(!$existById){
                                            $errors[] = 'Invalid Client ID, try again!';
                                        }
                                        else{
                                            $client_id   = $_POST['client_id'];
                                            $client_name = $existById->name;
                                        }
                                    }

                                    if(!empty($errors)){
                                        $_SESSION['alert']['type'] = 'danger';
                                        $_SESSION['alert']['msg'] = $errors;
                                        header('Location: diet.php?action=add_new');
                                        exit();
                                    }
                                }
                                else{
                                    header('Location: diet.php?action=add_new');
                                    exit();
                                }
                         ?>
                                <div class="card card-gray" >
                                    <div class="card-header p-3">
                                        <h3 class="card-title">New Diet Plan <br><br> For <b><?=$client_name;?></b></h3>
                                        <div class="card-tools mr-2">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div><!-- /.card-header -->

                                    <div class="card-body">
                                        <form class="row" method="post" action="diet.php?action=insert">
                                            <div class="form-group col-md-12">
                                                <label>Title</label>
                                                <input type="text" class="form-control" name="title" minlength="2" maxlength="250" required>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Breakfast</label>
                                                <textarea class="form-control" name="breakfast" minlength="3" maxlength="10000" required></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Lunch</label>
                                                <textarea class="form-control" name="lunch" minlength="3" maxlength="10000" required></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Dinner</label>
                                                <textarea class="form-control" name="dinner" minlength="3" maxlength="10000" required></textarea>
                                            </div>
                                            <input type="hidden" name="client_id" value="<?=$client_id;?>" readonly required>
                                            <div class="form-group col-md-12 text-right">
                                                <button type="submit" class="btn btn-primary">Create&ensp;<i class="fas fa-plus"></i></button>
                                            </div>
                                        </form>
                                    </div><!-- /.card-body -->

                                </div>
                        <?php }

                        ?>
                    <?php } else if($action=='insert'){

                            if($_SERVER['REQUEST_METHOD']=='POST'){
                                $errors = array();
                                foreach ($_POST as $key=> $val){
                                    if(empty(trim($val))){
                                        $errors[] = ucfirst($key)." was empty!";
                                    }
                                }

                                if(empty($errors)){
                                    $insertionData = array();
                                    foreach ($_POST as $key=> $val){
                                        $insertionData[$key] = $val;
                                    }

                                    $result = $diet->create($insertionData);

                                    if($result){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Diet plan created successfully!');
                                        header('Location: diet.php');
                                        exit();
                                    }
                                    else{
                                        $errors[] = 'Data insertion failed!';
                                    }
                                }

                                if(!empty($errors)){
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                    header('Location: diet.php?action=add_new');
                                    exit();
                                }
                            }

                      }
                    else if($action=='view'){
                        $redirect = false;
                        if(!isset($_GET['view_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $singleData = $diet->readById($_GET['view_id']);
                        }

                        if($singleData==null || $redirect){
                            header('Location: diet.php');
                            exit();
                        }
                    ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Diet Plan of <b><?=$singleData->client_name; ?></b>&emsp;<span class="badge badge-light"><?=$singleData->client_id;?></span></h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <h5>Title: <?=$singleData->title;?></h5>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="accordion" id="dietAccordion">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="mb-0 text-center">
                                                        <button class="btn w-100 text-light btn-link" type="button" data-toggle="collapse" data-target="#collapseBreakfast" aria-expanded="true" aria-controls="collapseBreakfast">
                                                            <b>BREAKFAST</b>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseBreakfast" class="collapse" data-parent="#dietAccordion">
                                                    <div class="card-body bg-dark">
                                                        <?=$singleData->breakfast;?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="mb-0 text-center">
                                                        <button class="btn w-100 text-light btn-link" type="button" data-toggle="collapse" data-target="#collapseLunch" aria-expanded="true" aria-controls="collapseLunch">
                                                            <b>LUNCH</b>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseLunch" class="collapse" data-parent="#dietAccordion">
                                                    <div class="card-body bg-dark">
                                                        <?=$singleData->lunch;?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="mb-0 text-center">
                                                        <button class="btn w-100 text-light btn-link" type="button" data-toggle="collapse" data-target="#collapseDinner" aria-expanded="true" aria-controls="collapseDinner">
                                                            <b>DINNER</b>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseDinner" class="collapse" data-parent="#dietAccordion">
                                                    <div class="card-body bg-dark">
                                                        <?=$singleData->dinner;?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card-body -->

                        </div>
                    <?php }
                    else if($action=='delete'){
                        $result = false;
                        if(!isset($_GET['delete_id'])){
                            header('Location: diet.php');
                            exit();
                        }

                        $deleteId = $_GET['delete_id'];
                        $result   = $diet->delete($deleteId);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('Diet plan has been successfully deleted!');
                        }
                        else{
                            $_SESSION['alert']['type'] = 'danger';
                            $_SESSION['alert']['msg']  = array('Data deletion failed!');
                        }
                        header('Location: diet.php');
                        exit();
                    }
                    else if($action=='edit'){
                        $redirect = false;
                        if(!isset($_GET['edit_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $singleData = $diet->readById($_GET['edit_id']);
                        }

                        if($singleData==null || $redirect){
                            header('Location: diet.php');
                            exit();
                        }
                        ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Update diet plan of <br><br><b><?=$singleData->client_name;?></b></h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form class="row" method="post" action="diet.php?action=update">
                                    <div class="form-group col-md-12">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="title" minlength="2" maxlength="250" value="<?=$singleData->title;?>" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Breakfast</label>
                                        <textarea class="form-control" name="breakfast" minlength="3" maxlength="10000" required><?=$singleData->breakfast; ?></textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Lunch</label>
                                        <textarea class="form-control" name="lunch" minlength="3" maxlength="10000" required><?=$singleData->lunch; ?></textarea>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Dinner</label>
                                        <textarea class="form-control" name="dinner" minlength="3" maxlength="10000" required><?=$singleData->dinner; ?></textarea>
                                    </div>
                                    <div class="form-group col-md-12 mt-3">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input custom-control-input-danger" type="checkbox" id="retireCheckBox" name="d_status" <?php if($singleData->d_status=='archived') { echo 'checked'; } ?> >
                                            <label for="retireCheckBox" class="custom-control-label"> <?php if($singleData->d_status=='archived'){ echo 'This diet is achieved, uncheck it make it active'; }else{ echo 'This diet is active, check it to archive'; } ?></label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="prev_dstatus" value="<?=$singleData->d_status; ?>" readonly required>
                                    <input type="hidden" name="id" value="<?=$singleData->id; ?>" readonly required>
                                    <input type="hidden" name="client_id" value="<?=$singleData->client_id; ?>" readonly required>
                                    <div class="form-group col-md-12 text-right">
                                        <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->

                        </div>
                    <?php }
                    else if($action=='update'){
                        $errors = array();
                        foreach ($_POST as $key=> $val){
                            if(empty(trim($val))){
                                $errors[] = ucfirst($key)." was empty!";
                            }
                        }

                        if(!isset($_POST['d_status'])){
                            $_POST['d_status'] = 'active';
                        }
                        else{
                            $_POST['d_status'] = 'archived';
                        }


                        if(empty($errors)){
                            if($_POST['d_status']==$_POST['prev_dstatus']){
                                unset($_POST['d_status']);
                            }
                            $updateData = array();
                            foreach ($_POST as $key=> $val){
                                if($key!='id' && $key!='prev_dstatus' && $key!='client_id'){
                                    $updateData[$key] = $val;
                                }
                            }

                            $updateId = array('id'=>$_POST['id'], 'client_id'=>$_POST['client_id']);
                            $result = $diet->update($updateId, $updateData);

                            if($result){
                                $_SESSION['alert']['type'] = 'success';
                                $_SESSION['alert']['msg']  = array('Diet plan updated successfully!');
                                header('Location: diet.php');
                                exit();
                            }
                            else{
                                $errors[] = 'Failed to update!';
                            }

                            if(!empty($errors)){
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = $errors;
                            }

                            header('Location: diet.php?action=edit&edit_id='.$_POST['id']);
                            exit();
                        }
                    }
                    else{
                        header('Location: diet.php');
                    }
                    ?>

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
<script>

    $('#data_tables').DataTable({
        "processing": true,
        "language": {
            "processing": "Getting data..."
        },
        "serverSide": true,
        "ajax": "datatables/get_all_diets.php",
        "pageLength": 5,
        "lengthMenu": [5, 10, 15],
        "columnDefs": [
            {
                "targets": 4,
                "orderable": false
            }
        ],
        "order": [[ 0, "desc" ]]
    });

</script>
<?php
include 'includes/main_app_footer.php';
?>

