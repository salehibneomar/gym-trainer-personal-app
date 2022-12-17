<?php
include 'includes/main_app_header.php';
require_once 'requires/Gym.php';

if($_SESSION['user']['type']!=='trainer'){
    header('Location: dashboard.php');
    exit();
}

$trainerId = $_SESSION['user']['id'];
$gym       = new Trainer\Gym($trainerId);

$action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : "view_all";
?>
<title><?=$siteTitle;?>  | GYM</title>
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
                                <h3 class="card-title">All GYM</h3>
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
                                        <th style="width: 3%">#ID</th>
                                        <th>Name</th>
                                        <th style="width: 7%">Status</th>
                                        <th style="width: 15%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $allData = $gym->readAll();
                                    if($allData!=null){
                                        foreach ($allData as $row){
                                                $status = is_null($row->retire_date)? '<span class="badge badge-success">ACTIVE</span>' : '<span class="badge badge-danger">RETIRED</span>';

                                            ?>
                                            <tr>
                                                <td><span class="badge badge-light"><?=$row->id;?></span></td>
                                                <td class="text-truncate"><?=$row->name;?></td>
                                                <td class="text-center"><?=$status;?></td>
                                <td>

                                    <div class="text-center">
                                        <button class="text-center btn btn-sm btn-primary mr-1 mb-1" data-toggle="modal" data-target="#viewmodal_<?=$row->id;?>"><i class="fas fa-eye"></i></button>

                                        <a class="btn btn-info btn-sm mr-1 mb-1" href="gym.php?action=edit&edit_id=<?=$row->id;?>"><i class="fas fa-edit"></i></a>

                                        <?php if($row->id!=1){ ?>
                                        <button class="btn btn-sm btn-danger mr-1 mb-1" data-toggle="modal" data-target="#deletemodal_<?=$row->id;?>"><i class="fas fa-trash-alt"></i></button>
                                        <?php } ?>
                                    </div>

                                    <?php if($row->id!=1){ ?>
                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="deletemodal_<?=$row->id;?>">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gray">
                                                    <h5 class="modal-title">Are you sure?</h5>
                                                </div>
                                                <div class="modal-body">
                                                    You won't be able to revert this operation.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <a class="btn btn-success" href="gym.php?action=delete&delete_id=<?=$row->id;?>">Okay</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <!-- Modal View -->
                                    <div class="modal fade" id="viewmodal_<?=$row->id;?>">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-gray">
                                                    <h5 class="modal-title overflow-auto"><?=$row->name;?></h5>
                                                </div>
                                                <div class="modal-body">
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item"><b>Joined Date:</b> <br>
                                                            <?=$row->joined_date;?>
                                                        </li>
                                                        <?php
                                                        if(!is_null($row->retire_date)){
                                                            ?>
                                                            <li class="list-group-item"><b>Retire Date:</b> <br>
                                                                <?=$row->retire_date;?>
                                                            </li>
                                                        <?php }
                                                        if(!is_null($row->salary)){
                                                            ?>
                                                            <li class="list-group-item"><b>Salary:</b> <br>
                                                                <?=number_format($row->salary);?>
                                                            </li>
                                                        <?php }
                                                        if(!is_null($row->location)){
                                                            ?>
                                                            <li class="list-group-item"><b>Location:</b> <br>
                                                                <?=$row->location;?>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }else if($action=='add_new'){ ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Add New GYM</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form action="gym.php?action=insert" method="post" >
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>GYM name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" minlength="1"  maxlength="250" required>
                                            <span class="form-text text-sm text-info">Keep it within 250 characters!</span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Joined Date <span class="text-danger">*</span></label>
                                            <input type="date" name="joined_date" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Salary</label>
                                            <input type="number" name="salary" class="form-control" min="0">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Location</label>
                                            <textarea class="form-control" name="location" maxlength="2000"></textarea>
                                        </div>
                                        <div class="form-group col-12 text-right">
                                            <button type="submit" class="btn btn-primary" name="addBtn">Add&ensp;<i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    <?php } else if($action=='insert'){
                        if($_SERVER['REQUEST_METHOD']=="POST"){
                            $_POST['name'] = ucwords(trim($_POST['name']));
                            $errors = array();

                            if(empty($_POST['name'])){
                                array_push($errors, 'Title is empty!');
                            }

                            if(mb_strlen($_POST['name'])>250){
                                array_push($errors, 'Keep the GYM name within 250 characters!');
                            }

                            if(empty($errors)){
                                $gymInsertionData = array();
                                foreach ($_POST as $key=> $val){
                                    if(!empty(trim($val))){
                                        $gymInsertionData[$key] = $val;
                                    }
                                }

                                $result = $gym->create($gymInsertionData);

                                if($result){
                                    $_SESSION['alert']['type'] = 'success';
                                    $_SESSION['alert']['msg']  = array('GYM has been successfully added!');
                                }
                                else{
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = array('Data insertion failed!');
                                }
                                header('Location: gym.php');
                                exit();
                            }
                            else{
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = $errors;
                                header('Location: gym.php?action=add_new');
                                exit();
                            }
                        }
                    }
                    else if($action=='edit'){

                        $redirect = false;
                        if(!isset($_GET['edit_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $editId = $_GET['edit_id'];
                            $singleData   = $gym->readById($editId);
                        }

                        if($singleData==null || $redirect){
                           header('Location: gym.php');
                            exit();
                        }

                        ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Update GYM</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form action="gym.php?action=update" method="post" >
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>GYM name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" minlength="1"  maxlength="250" value="<?=$singleData->name;?>" required>
                                            <span class="form-text text-sm text-info">Keep it within 250 characters!</span>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Joined Date <span class="text-danger">*</span></label>
                                            <input type="date" name="joined_date" class="form-control" value="<?=$singleData->joined_date;?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Salary</label>
                                            <input type="number" name="salary" class="form-control" min="0" value="<?=$singleData->salary;?>">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Location</label>
                                            <textarea class="form-control" name="location" maxlength="2000"><?=$singleData->location;?></textarea>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input custom-control-input-danger" type="checkbox" id="retireCheckBox" <?php if(!is_null($singleData->retire_date)){ echo 'checked'; } ?> name="retire_date" >
                                                <label for="retireCheckBox" class="custom-control-label">Mark as retired</label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="<?=$singleData->id;?>">
                                        <input type="hidden" name="prev_retire_status" value="<?php if(is_null($singleData->retire_date)){ echo 'na'; }else{ echo $singleData->retire_date; } ?>">
                                        <div class="form-group col-12 text-right">
                                            <button type="submit" class="btn btn-info">Update&ensp;<i class="fas fa-edit"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }
                    else if($action=='update'){
                        if($_SERVER['REQUEST_METHOD']=='POST'){

                            $_POST['name'] = trim($_POST['name']);
                            $errors        = array();
                            $activate      = false;

                            if(empty($_POST['name'])){
                                array_push($errors, 'Title was empty!');
                            }

                            if(mb_strlen($_POST['name'])>250){
                                array_push($errors, 'Keep the GYM name within 250 characters!');
                            }

                            if(isset($_POST['retire_date']) && $_POST['prev_retire_status']=='na'){
                                $_POST['retire_date'] = date('Y-m-d');
                            }
                            else if(!isset($_POST['retire_date']) && $_POST['prev_retire_status']!='na'){
                                $_POST['retire_date'] = "";
                                $activate = true;
                            }
                            else{
                                unset($_POST['retire_date']);
                            }

                            if(empty($errors)){
                                $gymUpdateData = array();
                                foreach ($_POST as $key=> $val){
                                    $val = trim($val);
                                    if($key!='id' && !empty($val) && $key!='prev_retire_status'){
                                        $gymUpdateData[$key] = $val;
                                    }
                                    else if(empty($val) && $key!='prev_retire_status'){
                                        $gymUpdateData[$key] = null;
                                    }
                                }

                                $result = $gym->update($_POST['id'], $gymUpdateData);

                                if($result){
                                    $operationMsg = array();
                                    $_SESSION['alert']['type'] = 'success';
                                    $operationMsg[] = 'GYM information updated successfully!';

                                    if(isset($_POST['retire_date'])){;
                                        $client = new Client\Client();
                                        if($activate){
                                            if($client->activeAllstudentsByGym($_POST['id'])){
                                                $operationMsg[] = 'All of the client\'s status has been set to active';
                                            }
                                            else{
                                                $operationMsg[] = '<span class="text-danger">Failed to set all clients status as active!</span>';
                                            }
                                        }
                                        else{
                                            if($client->deactiveAllstudentsByGym($_POST['id'])){
                                                $operationMsg[] = 'All of the client\'s status has been set to locked';
                                            }
                                            else{
                                                $operationMsg[] ='<span class="text-danger">Failed to set all clients status as locked!</span>';
                                            }

                                        }
                                    }

                                    $_SESSION['alert']['msg'] = $operationMsg;
                                }
                                else{
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = array('Data insertion failed!');
                                }
                                header('Location: gym.php');
                            }
                            else{
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = $errors;
                                header('Location: gym.php?action=edit&edit_id='.$_POST['id']);
                            }
                            exit();
                        }
                    }
                    else if($action=='delete'){
                        $result = false;
                        if(!isset($_GET['delete_id'])){
                            header('Location: gym.php');
                            exit();
                        }

                        $deleteId = $_GET['delete_id'];
                        $result   = $gym->delete($deleteId);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('GYM removed successfully!');
                        }
                        else{
                            $_SESSION['alert']['type'] = 'danger';
                            $_SESSION['alert']['msg']  = array('Data deletion failed!');
                        }
                        header('Location: gym.php');
                        exit();

                    }
                    else{
                        header('Location: gym.php');
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
        "pageLength": 5,
        "lengthMenu": [5, 10]
    });
</script>
<?php
include 'includes/main_app_footer.php';
?>

