<?php
include 'includes/main_app_header.php';
require_once 'requires/Gym.php';
require_once 'requires/HealthIssue.php';
require_once 'requires/BodyMeasurement.php';

if($_SESSION['user']['type']!=='trainer'){
    header('Location: dashboard.php');
    exit();
}

$trainerId = $_SESSION['user']['id'];
$action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : "view_all";
$client = new Client\Client();
$healthIssue = new Client\HealthIssue();
$bodyMeasurement = new Client\BodyMeasurement();

?>

<title><?=$siteTitle;?>  | Clients</title>

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
                                <h3 class="card-title">All Clients</h3>
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
                                        <th style="width: 5%;">ID#</th>
                                        <th style="width: 8%;">Picture</th>
                                        <th>Name</th>
                                        <th style="width: 12%;">Phone</th>
                                        <th style="width: 10%;">GYM</th>
                                        <th style="width: 8%;">Status</th>
                                        <th style="width: 12%;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }else if($action=='add_new'){ ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Add New Client</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form method="post" action="client.php?action=insert">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" maxlength="60" minlength="3" autocomplete="off" required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="phone" maxlength="30" minlength="4" autocomplete="off" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="pwd" minlength="6" autocomplete="off" required>
                                            <span class="form-text text-sm text-info">Should have at least 6 characters</span>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>GYM <span class="text-danger">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="gym_id" required>
                                                <option value="">--Select--</option>
                                                <?php
                                                $gym = new Trainer\Gym($trainerId);
                                                $allGymData = $gym->readAll();
                                                if($allGymData!=null){
                                                    foreach ($allGymData as $row){

                                                        ?>
                                                        <option value="<?=$row->id;?>"><?=$row->name;?></option>
                                                    <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12 text-right">
                                            <button type="submit" class="btn btn-primary" >Add&ensp;<i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    <?php } else if($action=='insert'){

                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            $errors = array();
                            $_POST['name']  = ucwords(trim($_POST['name']));
                            $_POST['phone'] = trim($_POST['phone']);
                            $_POST['pwd']   = trim($_POST['pwd']);

                            if(empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['pwd'])){
                                $errors[] = 'One or more fields were empty!';
                            }

                            if(!isset($_POST['gym_id'])){
                                $errors[] = 'GYM was not selected!';
                            }

                            if(empty($errors)){
                                $clientInsertionData = array();
                                foreach ($_POST as $key=> $val){
                                    $clientInsertionData[$key] = $val;
                                }
                                $clientInsertionData['acc_creation_date'] = date('Y-m-d');
                                $clientInsertionData['acc_status'] = 'active';
                                $clientInsertionData['trainer_id'] = $trainerId;

                                $result = $client->create($clientInsertionData);

                                if($result==1){
                                    $_SESSION['alert']['type'] = 'success';
                                    $_SESSION['alert']['msg']  = array('Client has been successfully added!');
                                    header('Location: client.php');
                                    exit();
                                }
                                else if($result==1062){
                                    $errors[] = 'The phone number you tried to insert has already been registered with another account!';
                                }
                                else{
                                    $errors[] = 'Data insertion failed!';
                                }
                            }

                            if(!empty($errors)){
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = $errors;
                            }

                            header('Location: client.php?action=add_new');
                            exit();
                        }

                    }
                    else if($action=='view'){
                        $redirect = false;
                        if(!isset($_GET['view_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $viewId = array('client_id'=>$_GET['view_id'], 'trainer_id'=>$trainerId);
                            $singleData   = $client->readById($viewId);
                        }

                        if($singleData==null || $redirect){
                            header('Location: client.php');
                            exit();
                        }

                        $healthIssueData = $healthIssue->readByClient($singleData->id);
                        $bodyMeasurementData = $bodyMeasurement->readByClient($singleData->id);
                    ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Client View</h3>
                                <div class="card-tools mr-2">
                                    <?php if($healthIssueData==null){ ?>
                                        <a href="client.php?action=health&issue_id=<?=$singleData->id; ?>" class="btn btn-sm btn-danger mr-2 mb-2"><i class="fas fa-notes-medical"></i></a>
                                    <?php } ?>
                                    <?php if($bodyMeasurementData==null){ ?>
                                        <a href="client.php?action=body&measurement_id=<?=$singleData->id; ?>" class="btn btn-sm btn-warning mb-2 mr-2"><i class="fas fa-child"></i></a>
                                    <?php } ?>

                                    <button type="button" class="btn btn-tool mb-2" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <div class="w-100 text-center mb-4">

                                    <?php

                                    $profileImage = $singleData->profile_picture!=null ? $client->getProfileImageDir().$singleData->profile_picture : 'images/default_image.png';

                                    ?>
                                    <img class="profile-page-propic" src="<?=$profileImage;?>">
                                    <h2 class="profile-username mt-3"><?=$singleData->name;?></h2>
                                    <?php if($singleData->acc_status=='active'){ ?>
                                    <h5 class="text-uppercase"><span class="badge badge-success"><?=$singleData->acc_status;?></span></h5>
                                    <?php }else if($singleData->acc_status=='locked'){ ?>
                                        <h5 class="text-uppercase"><span class="badge badge-danger"><?=$singleData->acc_status;?></span></h5>
                                    <?php } else if($singleData->acc_status=='pending'){ ?>
                                        <h5 class="text-uppercase"><span class="badge badge-warning"><?=$singleData->acc_status;?></span></h5>
                                    <?php } ?>
                                </div>

                                <div class="row">

                                    <div class="col-md-12 mx-auto">
                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item p-3">
                                                <b class="d-inline-block">Client ID</b>
                                                <span class="d-inline-block float-right"><?=$singleData->id;?></span>
                                            </li>
                                            <li class="list-group-item p-3">
                                                <b class="d-inline-block">GYM name</b>
                                                <span class="d-inline-block float-right"><?=$singleData->gym_name;?></span>
                                            </li>
                                            <li class="list-group-item p-3">
                                                <b class="d-inline-block">Joined date</b>
                                                <span class="d-inline-block float-right"><?=date('d M Y', strtotime($singleData->acc_creation_date));?></span>
                                            </li>
                                            <li class="list-group-item p-3">
                                                <b class="d-inline-block">Phone</b>
                                                <span class="d-inline-block float-right"><?=$singleData->phone;?></span>
                                            </li>                                                    <li class="list-group-item p-3">
                                                <b class="d-inline-block">Email</b>
                                                <span class="d-inline-block float-right">
                                                            <?php
                                                            echo $singleData->email!=null ? $singleData->email : 'N/A';
                                                            ?>
                                                        </span>
                                            </li>
                                            <li class="list-group-item p-3">
                                                <b class="d-inline-block">Date of birth</b>
                                                <span class="d-inline-block float-right">
                                                            <?php
                                                            echo $singleData->dob!=null ? date('d M Y', strtotime($singleData->dob)) : 'N/A';
                                                            ?>
                                                        </span>
                                            </li>
                                            <?php if($healthIssueData!=null){ ?>
                                                <li class="list-group-item p-3 mt-3">
                                                    <p class="mb-3 p-0"><b>Health Issue:</b><a href="client.php?action=health_edit&issue_id=<?=$singleData->id; ?>" class="ml-4 btn btn-sm btn-info"><i class="fas fa-edit"></i></a></p>
                                                    <?=$healthIssueData->issue; ?>
                                                </li>
                                            <?php } ?>

                                            <?php if($bodyMeasurementData!=null){ ?>
                                                <li class="list-group-item p-3 mt-3">
                                                    <p class="mb-3 p-0"><b>Body Measurements:</b><a href="client.php?action=body_edit&measurement_id=<?=$singleData->id; ?>" class="ml-4 btn btn-sm btn-info"><i class="fas fa-edit"></i></a></p>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item pt-0"><b>Initial</b>&ensp;(<?=$bodyMeasurementData->initial_date; ?>)<br>
                                                            <span class="text-muted text-sm">(KG for Weight and Others are Inches):</span> <br>
                                                            <?=$bodyMeasurementData->initial_measurement; ?>

                                                        </li>

                                                        <?php
                                                        if($bodyMeasurementData->updated_date!=null){
                                                         ?>
                                                            <li class="list-group-item"><b>Updated</b>&ensp;(<?=$bodyMeasurementData->updated_date; ?>)<br>
                                                                <span class="text-muted text-sm">(KG for Weight and Others are Inches):</span> <br>
                                                                <?=$bodyMeasurementData->updated_measurement; ?>

                                                            </li>
                                                        <?php } ?>

                                                    </ul>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>

                                </div>
                            </div><!-- /.card-body -->
                        </div>
                    <?php } else if($action=='edit'){
                        $redirect = false;
                        if(!isset($_GET['edit_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $editId = array('client_id'=>$_GET['edit_id'], 'trainer_id'=>$trainerId);
                            $singleData   = $client->readById($editId);
                        }

                        if($singleData==null || $redirect){
                            header('Location: client.php');
                            exit();
                        }
                    ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Update Client Information</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form method="post" action="client.php?action=update">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" maxlength="60" minlength="3" autocomplete="off" value="<?=$singleData->name;?>" required>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" name="phone" maxlength="30" minlength="4" autocomplete="off" value="<?=$singleData->phone;?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Password </label>
                                            <input type="password" class="form-control" name="pwd" minlength="6" autocomplete="off" >
                                            <span class="form-text text-sm text-info">Should have at least 6 characters</span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>GYM <span class="text-danger">*</span></label>
                                            <select class="form-control select2" style="width: 100%;" name="gym_id" required>
                                                <?php
                                                $gym = new Trainer\Gym($trainerId);
                                                $allGymData = $gym->readAll();
                                                if($allGymData!=null){
                                                    foreach ($allGymData as $row){
                                                        $gym_selected = $singleData->gym_id == $row->id ? 'selected' : null;
                                                        ?>
                                                        <option value="<?=$row->id;?>" <?=$gym_selected;?> ><?=$row->name;?></option>
                                                    <?php } } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Account Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="acc_status" required>
                                                <option value="">-- Select --</option>
                                                <option value="active" <?php if($singleData->acc_status=='active'){ echo 'selected'; }  ?> >Active</option>
                                                <option value="locked" <?php if($singleData->acc_status=='locked'){ echo 'selected'; }  ?> >Locked</option>
                                                <option value="hidden" >Hide</option>
                                            </select>
                                            <span class="form-text text-sm text-info">If you hide one client, you won't be able to retrieve that data from admin panel, so be careful while selecting client account status. </span>
                                        </div>
                                        <input type="hidden" name="id" value="<?=$singleData->id;?>" readonly required>
                                        <div class="form-group col-md-12 text-right">
                                            <button type="submit" class="btn btn-info" >Update&ensp;<i class="fas fa-edit"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }
                        else if($action=='update'){
                            if($_SERVER['REQUEST_METHOD']=='POST'){
                                $errors = array();
                                $_POST['name']  = ucwords(trim($_POST['name']));
                                $_POST['phone'] = trim($_POST['phone']);
                                $_POST['pwd']   = trim($_POST['pwd']);

                                if(empty($_POST['name']) || empty($_POST['phone'])){
                                    $errors[] = 'One or more fields were empty!';
                                }

                                if(!isset($_POST['gym_id'])){
                                    $errors[] = 'GYM was not selected!';
                                }

                                if(!isset($_POST['acc_status'])){
                                    $errors[] = 'Account status was not selected!';
                                }

                                if(empty($errors)){
                                    $clientUpdateData = array();
                                    foreach ($_POST as $key=> $val){
                                        if($key!='id' && !empty($val)){
                                            $clientUpdateData[$key] = $val;
                                        }
                                    }

                                    $updateId = array('client_id'=>$_POST['id'], 'trainer_id'=> $trainerId);

                                    $result = $client->adminUpdate($updateId, $clientUpdateData);

                                    if($result==1){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Client information has been successfully updated!');
                                        header('Location: client.php');
                                        exit();
                                    }
                                    else if($result==1062){
                                        $errors[] = 'The phone number you tried to insert has already been registered with another account!';
                                    }
                                    else{
                                        $errors[] = 'Failed to update!';
                                    }
                                }

                                if(!empty($errors)){
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                }

                                header('Location: client.php?action=edit&edit_id='.$_POST['id']);
                                exit();
                            }
                        }
                        else if($action=='health'){
                        ?>
                            <div class="card card-gray" >
                                <div class="card-header p-3">
                                    <h3 class="card-title">Health Issue</h3>
                                    <div class="card-tools mr-2">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div><!-- /.card-header -->

                                <div class="card-body">
                                    <form class="row" method="post" action="client.php?action=health_insert">
                                        <div class="col-md-12 form-group">
                                            <label>Write health issue</label>
                                            <textarea class="form-control" name="issue" maxlength="15000" rows="5" required></textarea>
                                        </div>
                                        <div class="col-md-12 form-group text-right mt-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                        <input type="hidden" name="client_id" value="<?=$_GET['issue_id']; ?>" readonly required>
                                    </form>
                                </div><!-- /.card-body -->
                            </div>
                    <?php }
                        else if($action=='health_insert'){
                            if($_SERVER['REQUEST_METHOD']=='POST'){
                                $errors = array();
                                if(empty(trim($_POST['issue']))){
                                    $errors[] = 'Issue cannot be empty if submitted!';
                                }
                                else{
                                    $insertionData = array('issue'=>$_POST['issue'], 'client_id'=>$_POST['client_id']);

                                    $result = $healthIssue->create($insertionData);

                                    if($result){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Health issue been successfully added!');
                                    }
                                    else{
                                        $errors[] = 'Data insertion failed!';
                                    }

                                }
                                if(!empty($errors)){
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                }
                                header('Location: client.php?action=view&view_id='.$_POST['client_id']);
                                exit();
                            }
                        }
                        else if($action=='health_edit'){
                            $redirect = false;
                            if(!isset($_GET['issue_id'])){
                                $redirect = true;
                            }

                            $singleData = null;
                            if(!$redirect){
                                $singleData = $healthIssue->readByClient($_GET['issue_id']);
                            }

                            if($singleData==null || $redirect){
                                header('Location: client.php?action=view&view_id='.$_POST['issue_id']);
                                exit();
                            }
                    ?>
                            <div class="card card-gray" >
                                <div class="card-header p-3">
                                    <h3 class="card-title">Update Health Issue</h3>
                                    <div class="card-tools mr-2">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div><!-- /.card-header -->

                                <div class="card-body">
                                    <form class="row" method="post" action="client.php?action=health_update">
                                        <div class="col-md-12 form-group">
                                            <label>Write health issue</label>
                                            <textarea class="form-control" name="issue" maxlength="15000" rows="5" required><?=$singleData->issue; ?></textarea>
                                        </div>
                                        <div class="col-md-12 form-group text-right mt-3">
                                            <button type="submit" class="btn btn-info">Update</button>
                                        </div>
                                        <input type="hidden" name="client_id" value="<?=$singleData->client_id; ?>" readonly required>
                                    </form>
                                </div><!-- /.card-body -->
                            </div>
                    <?php }
                        else if($action=='health_update'){
                            if($_SERVER['REQUEST_METHOD']=='POST'){
                                $errors = array();
                                if(empty(trim($_POST['issue']))){
                                    $errors[] = 'Issue cannot be empty if inserted once!';
                                }
                                else{
                                    $updateData = array('issue'=>$_POST['issue']);

                                    $result = $healthIssue->updateByClient($_POST['client_id'], $updateData);

                                    if($result){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Health issue been successfully updated!');
                                    }
                                    else{
                                        $errors[] = 'Data update failed!';
                                    }

                                }
                                if(!empty($errors)){
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                }
                                header('Location: client.php?action=view&view_id='.$_POST['client_id']);
                                exit();
                            }
                        }
                        else if($action=='body'){

                    ?>
                            <div class="card card-gray" >
                                <div class="card-header p-3">
                                    <h3 class="card-title">Body Measurement (Initial)</h3>
                                    <div class="card-tools mr-2">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div><!-- /.card-header -->

                                <div class="card-body">
                                    <form class="row" method="post" action="client.php?action=body_insert">

                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Weight (KG)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="WEIGHT" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Arm (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="ARM" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Chest (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="CHEST" required>
                                        </div>

                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Waist (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="WAIST" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Hip (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="HIP" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Leg (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="LEG" required>
                                        </div>

                                        <div class="col-md-12 form-group text-right mt-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                        <input type="hidden" name="client_id" value="<?=$_GET['measurement_id']; ?>" readonly required>
                                    </form>
                                </div><!-- /.card-body -->
                            </div>
                    <?php
                        }
                        else if($action=='body_insert'){
                            if($_SERVER['REQUEST_METHOD']=='POST'){
                                $errors = array();
                                foreach ($_POST as $key=> $val){
                                    if(empty(trim($val))){
                                        $errors[] = $key.' was empty!';
                                    }
                                }

                                if(empty($errors)){
                                    $initial_measurement = array();
                                    foreach($_POST as $key=> $val){
                                        if($key!='client_id'){
                                            $initial_measurement[] = $key.' = '.$val;
                                        }
                                    }
                                    $initial_measurement = implode(", ",$initial_measurement);
                                    $insertionData = array('initial_measurement'=>$initial_measurement, 'initial_date'=>date('Y-m-d'), 'client_id'=>$_POST['client_id']);
                                    $result = $bodyMeasurement->create($insertionData);

                                    if($result){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Body measurement been successfully added!');
                                    }
                                    else{
                                        $errors[] = 'Data insertion failed!';
                                    }
                                }

                                if(!empty($errors)){
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                }
                                header('Location: client.php?action=view&view_id='.$_POST['client_id']);
                                exit();

                            }
                        }
                        else if($action=='body_edit'){
                            $redirect = false;
                            if(!isset($_GET['measurement_id'])){
                                $redirect = true;
                            }

                            $singleData = null;
                            if(!$redirect){
                                $singleData = $bodyMeasurement->readByClient($_GET['measurement_id']);
                            }

                            if($singleData==null || $redirect){
                                header('Location: client.php?action=view&view_id='.$_POST['issue_id']);
                                exit();
                            }
                     ?>
                            <div class="card card-gray" >
                                <div class="card-header p-3">
                                    <h3 class="card-title">Body Measurement Update<a href="client.php?action=body_delete&amp;measurement_id=<?=$singleData->client_id; ?>" class="ml-4 btn btn-sm btn-danger"><i class="fas fa-trash"></i>&ensp;Delete</a></h3>
                                    <div class="card-tools mr-2">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div><!-- /.card-header -->

                                <div class="card-body">
                                    <form class="row" method="post" action="client.php?action=body_update">

                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Weight (KG)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="WEIGHT" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Arm (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="ARM" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Chest (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="CHEST" required>
                                        </div>

                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Waist (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="WAIST" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Hip (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="HIP" required>
                                        </div>
                                        <div class="col-lg-4 col-md-6 form-group">
                                            <label>Leg (Inch)</label>
                                            <input class="form-control" type="number" min="0" step="0.1" name="LEG" required>
                                        </div>

                                        <div class="col-md-12 form-group text-right mt-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                        <input type="hidden" name="client_id" value="<?=$singleData->client_id; ?>" readonly required>
                                    </form>
                                </div><!-- /.card-body -->
                            </div>
                    <?php }
                        else if($action=='body_update'){
                            if($_SERVER['REQUEST_METHOD']=='POST'){
                                $errors = array();
                                foreach ($_POST as $key=> $val){
                                    if(empty(trim($val))){
                                        $errors[] = $key.' was empty!';
                                    }
                                }

                                if(empty($errors)){
                                    $updated_measurement = array();
                                    foreach($_POST as $key=> $val){
                                        if($key!='client_id'){
                                            $updated_measurement[] = $key.' = '.$val;
                                        }
                                    }
                                    $updated_measurement = implode(", ",$updated_measurement);
                                    $updateData = array('updated_measurement'=>$updated_measurement, 'updated_date'=>date('Y-m-d'));
                                    $result = $bodyMeasurement->updateByClient($_POST['client_id'], $updateData);


                                    if($result){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Body measurement been successfully updated!');
                                    }
                                    else{
                                        $errors[] = 'Data update failed!';
                                    }
                                }

                                if(!empty($errors)){
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                }
                                header('Location: client.php?action=view&view_id='.$_POST['client_id']);
                                exit();

                            }
                        }
                        else if($action=='body_delete'){
                            if(isset($_GET['measurement_id'])){
                                $clientId = $_GET['measurement_id'];

                                $result = $bodyMeasurement->deleteByClient($clientId);

                                if($result){
                                    $_SESSION['alert']['type'] = 'success';
                                    $_SESSION['alert']['msg']  = array('Body measurement has been successfully deleted!');
                                }
                                else{
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = array('Failed to delete data!');
                                }
                            }

                            header('Location: client.php?action=view&view_id='.$_GET['measurement_id']);
                            exit();
                        }
                        else{
                            header('Location: client.php');
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
    $('.select2').select2({
        tags: false
    });

    $('#data_tables').DataTable({
        "processing": true,
        "language": {
            "processing": "Getting data..."
        },
        "serverSide": true,
        "ajax": "datatables/get_all_clients.php",
        "pageLength": 5,
        "lengthMenu": [5, 10, 15],
        "columnDefs": [
            {
                "targets": 6,
                "orderable": false
            }
        ],
        "order": [[ 2, "asc" ]]
    });
</script>

<?php
include 'includes/main_app_footer.php';
?>

