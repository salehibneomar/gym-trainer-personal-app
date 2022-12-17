<?php
include 'includes/main_app_header.php';
require_once 'requires/Achievement.php';

if($_SESSION['user']['type']!=='trainer'){
    header('Location: dashboard.php');
    exit();
}

$trainerId = $_SESSION['user']['id'];
$achievement = new Trainer\Achievement($trainerId);

$action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : "view_all";
?>

<title><?=$siteTitle;?>  | Achievements</title>

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
                                <h3 class="card-title">All Achievements</h3>
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
                                        <th style="width: 3%">#SL</th>
                                        <th style="width: 7%">Type</th>
                                        <th>Title</th>
                                        <th style="width: 10%">Received</th>
                                        <th style="width: 2%">Remark</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $allData = $achievement->readAll();
                                    if($allData!=null){
                                        $sl = 0;
                                        foreach ($allData as $row){
                                            ++$sl;
                                            $formatedAttainedDate = date('d M y', strtotime($row->attained_date));
                                            ?>
                                            <tr>
                                                <td><span class="badge badge-light"><?=$sl;?></span></td>
                                                <td class="text-uppercase font-weight-bold"><?=$row->type;?></td>
                                                <td class="text-truncate"><?=$row->title;?></td>
                                                <td class="text-center" data-sort="<?=$row->attained_date;?>"><?=$formatedAttainedDate;?></td>
                                                <td class="text-center"><span class="badge badge-btn badge-success font-weight-bold"><?=$row->remark;?></span></td>
                                                <td >
                                                    <div class="text-center">
                                                        <a class="btn btn-info btn-sm mr-1 mb-1" href="achievement.php?action=edit&edit_id=<?=$row->id;?>"><i class="fas fa-edit"></i></a>
                                                        <button class="btn btn-sm btn-danger mr-1 mb-1" data-toggle="modal" data-target="#deletemodal_<?=$row->id;?>"><i class="fas fa-trash-alt"></i></button>
                                                    </div>
                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deletemodal_<?=$row->id;?>">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-gray">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    You won't be able to revert this operation.
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <a class="btn btn-success" href="achievement.php?action=delete&delete_id=<?=$row->id;?>">Okay</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php } unset($sl); } ?>
                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }else if($action=='add_new'){ ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Add New Achievement</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form action="achievement.php?action=insert" method="post" >
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Title <span class="text-danger">*</span></label> <br>
                                            <input class="form-control" type="text" minlength="2" maxlength="250" name="title" required autocomplete="off">
                                            <span class="form-text text-sm text-info">Keep it within 250 characters!</span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Select Achievement Type <span class="text-danger">*</span></label> <br>
                                            <select name="type" class="form-control" required>
                                                <option value="">--Type--</option>
                                                <option value="achievement">Achievement</option>
                                                <option value="certificate">Certificate</option>
                                                <option value="testimonial">Testimonial</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Choose Remark <span class="text-danger">*</span></label> <br>
                                            <select name="remark" class="form-control" required>
                                                <option value="">--Remark--</option>
                                                <?php
                                                    for($i=5; $i>=1; $i-=0.5){
                                                ?>
                                                        <option value="<?=$i;?>"><?=$i;?></option>
                                                <?php } unset($i); ?>
                                            </select>
                                            <span class="form-text text-sm text-info">On the scale of 5</span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Date Received <span class="text-danger">*</span></label> <br>
                                            <input class="form-control" type="Date" name="attained_date" required>
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
                                extract($_POST);
                                $title = ucwords(trim($title));
                                $errors = array();

                                if(empty($title)){
                                    array_push($errors, 'Title is empty!');
                                }

                                if(mb_strlen($title)>250){
                                    array_push($errors, 'Title cannot have more than 250 characters!');
                                }

                                if(empty($errors)){
                                    $achievementInsertionData = array('title'=>$title, 'type'=>$type, 'remark'=>$remark, 'attained_date'=>$attained_date);

                                    $result = $achievement->create($achievementInsertionData);

                                    if($result){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Your '.$type.' has been successfully added!');
                                    }
                                    else{
                                        $_SESSION['alert']['type'] = 'danger';
                                        $_SESSION['alert']['msg']  = array('Data insertion failed!');
                                    }
                                    header('Location: achievement.php');
                                    exit();
                                }
                                else{
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                    header('Location: achievement.php?action=add_new');
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
                            $singleData   = $achievement->readById($editId);
                        }

                        if($singleData==null || $redirect){
                            header('Location: achievement.php');
                            exit();
                        }

                        ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Update Achievement</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form action="achievement.php?action=update" method="post" >
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label>Title <span class="text-danger">*</span></label> <br>
                                            <input class="form-control" type="text" minlength="2" maxlength="250" name="title" required autocomplete="off" value="<?=$singleData->title;?>">
                                            <span class="form-text text-sm text-info">Keep it within 250 characters!</span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Select Achievement Type <span class="text-danger">*</span></label> <br>
                                            <select name="type" class="form-control" required>
                                                <?php $typeArray = array('achievement'=>'Achievement', 'certificate'=>'Certificate', 'testimonial'=>'Testimonial') ?>
                                                <?php foreach ($typeArray as $key=> $val){
                                                    $isSelected= $singleData->type==$key ? 'selected' : null; ?>
                                                    <option value="<?=$key;?>" <?=$isSelected;?>><?=$val;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Choose Remark <span class="text-danger">*</span></label> <br>
                                            <select name="remark" class="form-control" required>
                                                <?php
                                                for($i=5; $i>=1; $i-=0.5){
                                                    $isSelected = $singleData->remark==$i ? 'selected' : null;
                                                    ?>
                                                    <option value="<?=$i;?>" <?=$isSelected;?>><?=$i;?></option>
                                                <?php } unset($i, $isSelected); ?>
                                            </select>
                                            <span class="form-text text-sm text-info">On the scale of 5</span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Date Received <span class="text-danger">*</span></label> <br>
                                            <input class="form-control" type="Date" name="attained_date" required value="<?=$singleData->attained_date;?>">
                                        </div>
                                        <input type="hidden" name="editId" value="<?=$singleData->id;?>">
                                        <div class="form-group col-12 text-right">
                                            <button type="submit" class="btn btn-info" name="editBtn">Update&ensp;<i class="fas fa-edit"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }
                    else if($action=='update'){
                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            $errors = array();
                            extract($_POST);
                            $title = trim($title);

                            if(empty($title)){
                                array_push($errors, 'Title is empty!');
                            }

                            if(mb_strlen($title)>250){
                                array_push($errors, 'Title cannot have more than 250 characters!');
                            }

                            if(empty($errors)){
                                $achievementUpdateData = array('title'=>$title, 'type'=>$type, 'remark'=>$remark, 'attained_date'=>$attained_date);
                                $result = $achievement->update($editId, $achievementUpdateData);

                                if($result){
                                    $_SESSION['alert']['type'] = 'success';
                                    $_SESSION['alert']['msg']  = array('Your '.$type.' has been successfully updated!');
                                }
                                else{
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = array('Failed to update data!');
                                }
                                header('Location: achievement.php');
                                exit();
                            }
                            else{
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = $errors;
                                header('Location: achievement.php?action=edit&edit_id='.$editId);
                                exit();
                            }

                        }
                    }
                    else if($action=='delete'){
                        $result = false;
                        if(!isset($_GET['delete_id'])){
                            header('Location: achievement.php');
                            exit();
                        }

                        $deleteId = $_GET['delete_id'];
                        $result   = $achievement->delete($deleteId);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('Achievement has been successfully removed!');
                        }
                        else{
                            $_SESSION['alert']['type'] = 'danger';
                            $_SESSION['alert']['msg']  = array('Data deletion failed!');
                        }
                        header('Location: achievement.php');
                        exit();
                    }
                    else{
                        header('Location: achievement.php');
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


