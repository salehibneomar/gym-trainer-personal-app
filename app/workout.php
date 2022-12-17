<?php
include 'includes/main_app_header.php';
require_once 'requires/Workout.php';

    if($_SESSION['user']['type']!=='trainer'){
        header('Location: dashboard.php');
        exit();
    }

    $trainerId = $_SESSION['user']['id'];
    $workout   = new Workout\Workout();

    $action    = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : "view_all";
?>

<title><?=$siteTitle;?>  | Workout</title>

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
                                <h3 class="card-title">All Workout Routines</h3>
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
                                        <h3 class="card-title">Create New Workout Routine For Client</h3>
                                        <div class="card-tools mr-2">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div><!-- /.card-header -->

                                    <div class="card-body">
                                        <form class="row" method="post" action="workout.php?action=add_new&step=2">
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
                                        header('Location: workout.php?action=add_new');
                                        exit();
                                    }
                                }
                                else{
                                    header('Location: workout.php?action=add_new');
                                    exit();
                                }
                         ?>
                                <div class="card card-gray" >
                                    <div class="card-header p-3">
                                        <h3 class="card-title">New Workout Routine <br><br> For <b><?=$client_name;?></b></h3>
                                        <div class="card-tools mr-2">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div><!-- /.card-header -->

                                    <div class="card-body">
                                        <form class="row" method="post" action="workout.php?action=insert">
                                            <div class="form-group col-md-9">
                                                <label>Title</label>
                                                <input type="text" class="form-control" name="title" minlength="2" maxlength="250" required>
                                                <span class="form-text text-sm text-info">Keep Title, Note within 250 characters and Workout Name within 100 characters!</span>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Interval</label>
                                                <select class="form-control" name="interval" required>
                                                    <option value="">--Select--</option>
                                                    <?php
                                                        for($days=7, $init=30; $days<=168; $days+=7){
                                                            $option = $value = null;
                                                            $cal_1  = $days/7;
                                                            $cal_2  = ($days/7)/4;
                                                            if($cal_1<=3 && $cal_2<1){
                                                                $option = $cal_1 == 1 ? $cal_1.' Week' : $cal_1.' Weeks';
                                                            }
                                                            else if($cal_1>3){
                                                                $option = $cal_2 == 1 ? $cal_2.' Month' : $cal_2.' Months';
                                                            }
                                                            $value = $days;
                                                            if($days%28==0){
                                                                $value = ($init-$value)+$value;
                                                                $init+=30;
                                                            }
                                                    ?>
                                                        <option value="<?=$value."__".$option; ?>"><?=$option;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="client_id" value="<?=$client_id;?>" readonly required>
                                            <div class="form-group col-md-12 table-overflow-fix mt-4">
                                                <p class="w-100">Workout List</p>
                                                <table class="table table-bordered table-striped" id="workout-table">
                                                    <tbody class="workout-tbody-template">
                                                        <tr>
                                                            <td colspan="2"><input class="form-control" type="text" placeholder="Workout" name="name[]" maxlength="100" required></td>
                                                            <td class="text-center"><b>DROP</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 40%;">
                                                                <select class="form-control" name="days[]" required>
                                                                    <option value="">--Day--</option>
                                                                    <option value="ED">EVERY</option>
                                                                    <option value="SUN">SUN</option>
                                                                    <option value="MON">MON</option>
                                                                    <option value="TUE">TUE</option>
                                                                    <option value="WED">WED</option>
                                                                    <option value="THU">THU</option>
                                                                    <option value="FRI">FRI</option>
                                                                    <option value="SAT">SAT</option>
                                                                </select>
                                                            </td>
                                                            <td style="width: 30%;"><input class="form-control" type="number" min="0" placeholder="Set" name="sets[]" required></td>
                                                            <td style="width: 30%;"><input class="form-control" type="number" min="0" placeholder="Rep" name="reps[]" required></td>
                                                        </tr>
                                                    <tr>
                                                        <td colspan="3"><input class="form-control" type="text" placeholder="Note" maxlength="250" name="note[]" required></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="form-group col-12 text-right">
                                                <button id="add-workout-btn"  type="button" class="btn btn-success btn-sm">Increase Row&ensp;<i class="fas fa-plus"></i></button>
                                            </div>
                                            <div class="form-group col-12 text-right mt-4">
                                                <button  type="submit" class="btn btn-primary w-100">Create Routine&ensp;<i class="fas fa-plus"></i></button>
                                            </div>
                                        </form>
                                    </div><!-- /.card-body -->

                                </div>
                        <?php }

                        ?>
                    <?php } else if($action=='insert'){

                            if($_SERVER['REQUEST_METHOD']=='POST'){
                                $errors = array();

                                if(empty(trim($_POST['title'])) || !isset($_POST['interval'])){
                                    $errors[] = 'Empty fields found, try again!';
                                }
                                else if(mb_strlen($_POST['title'])>250 || mb_strlen($_POST['title'])<2){
                                    $errors[] = 'Invalid title size!';
                                }

                                if(empty($errors)){
                                    extract($_POST);
                                    $interval = explode("__",$interval);
                                    $end_date = date('Y-m-d', strtotime(($interval[0]+1).' days'));
                                    $name     = implode("_sep_",$name);
                                    $days     = implode("_sep_",$days);
                                    $sets     = implode("_sep_",$sets);
                                    $reps     = implode("_sep_",$reps);
                                    $note     = implode("_sep_",$note);

                                    $insertionData = array('title'=>$title,'interval_days'=>$interval[1], 'start_date'=>date('Y-m-d'), 'end_date'=>$end_date, 'name'=>$name, 'days'=>$days, 'sets'=>$sets, 'reps'=>$reps, 'note'=>$note, 'client_id'=>$client_id);

                                    $result = $workout->create($insertionData);

                                    if($result){
                                        $_SESSION['alert']['type'] = 'success';
                                        $_SESSION['alert']['msg']  = array('Workout routine created successfully!');
                                        header('Location: workout.php');
                                        exit();
                                    }
                                    else{
                                        $errors[] = 'Data insertion failed!';
                                    }

                                }

                                if(!empty($errors)){
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg']  = $errors;
                                    header('Location: workout.php?action=add_new');
                                    exit();
                                }

//                                var_dump("<br>".$title."<br>".$end_date."<br>".$name."<br>".$days."<br>".$sets."<br>".$reps."<br>".$note."<br>".$client_id);

                            }

                      }
                    else if($action=='view'){
                        $redirect = false;
                        if(!isset($_GET['view_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $singleData = $workout->readById($_GET['view_id']);
                        }

                        if($singleData==null || $redirect){
                            header('Location: workout.php');
                            exit();
                        }

                    ?>

                        <div class="card card-gray">
                            <div class="card-header p-3">
                                <h3 class="card-title">Workout Routine of <b><?=$singleData->client_name; ?></b>&emsp;<span class="badge badge-light"><?=$singleData->client_id; ?></span></h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="accordion" id="workoutAccordion">

                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="mb-0 text-center">
                                                        <button class="btn w-100 text-light btn-link" type="button" data-toggle="collapse" data-target="#collapseInfo" aria-expanded="true" aria-controls="collapseInfo">
                                                            <b>INFO</b>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseInfo" class="collapse show" data-parent="#workoutAccordion">

                                                    <div class="card-body bg-dark">
                                                        <div class="text-right">
                                                            <a href="workout.php?action=print&print_id=<?=$singleData->id; ?>" class="btn btn-primary"><i class="fas fa-file-powerpoint"></i></a>
                                                        </div>
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item"><b>Routine Title:</b><br>
                                                                <?=$singleData->title; ?>
                                                            </li>
                                                            <li class="list-group-item"><b>Creation Date:</b><br>
                                                                <?=date('d M Y', strtotime($singleData->start_date)); ?>
                                                            </li>
                                                            <?php if($singleData->last_updated!=null){ ?>
                                                                <li class="list-group-item"><b>Last Updated:</b><br>
                                                                    <?=date('d M Y', strtotime($singleData->last_updated)); ?>
                                                                </li>
                                                            <?php } ?>
                                                            <li class="list-group-item"><b>Interval:</b><br>
                                                                <?=$singleData->interval_days ; ?>
                                                            </li>
                                                        </ul>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="mb-0 text-center">
                                                        <button class="btn w-100 text-light btn-link" type="button" data-toggle="collapse" data-target="#collapseList" aria-expanded="true" aria-controls="collapseList">
                                                            <b>WORKOUTS</b>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseList" class="collapse" data-parent="#workoutAccordion">
                                                    <div class="card-body bg-dark">
                                                        <div class="table-overflow-fix">
                                                            <table class="table table-bordered" >
                                                                <thead>
                                                                <tr>
                                                                    <th style="width: 8%;">SL#</th>
                                                                    <th >Name</th>
                                                                    <th style="width: 12%;">Day</th>
                                                                    <th style="width: 10%;">Sets</th>
                                                                    <th style="width: 10%;">Reps</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                $names = explode('_sep_',$singleData->name);
                                                                $days  = explode('_sep_',$singleData->days);
                                                                $sets  = explode('_sep_',$singleData->sets);
                                                                $reps  = explode('_sep_',$singleData->reps);

                                                                $countRows = count($names);

                                                                for($row=0; $row<$countRows; ++$row){
                                                                    ?>
                                                                    <tr>
                                                                        <td><span class="badge badge-light"><?=($row+1);?></span></td>
                                                                        <td><?=$names[$row];?></td>
                                                                        <td><?php if($days[$row]=='ED'){ echo 'EVERY'; }else{ echo $days[$row]; } ?></td>
                                                                        <td><?=$sets[$row]; ?></td>
                                                                        <td><?=$reps[$row]; ?></td>
                                                                    </tr>
                                                                <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="mb-0 text-center">
                                                        <button class="btn w-100 text-light btn-link" type="button" data-toggle="collapse" data-target="#collapseNotes" aria-expanded="true" aria-controls="collapseNotes">
                                                            <b>NOTES</b>
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseNotes" class="collapse" data-parent="#workoutAccordion">
                                                    <div class="card-body">
                                                        <ul class="list-group">
                                                            <?php
                                                            $notes = explode('_sep_',$singleData->note);
                                                            for ($row=0; $row<$countRows; ++$row){
                                                                ?>
                                                                <li class="text-center list-group-item">
                                                                    <span class="badge badge-light">
                                                                        SL#<?=($row+1)." (".$names[$row].")";?>
                                                                    </span>
                                                                </li>

                                                                <li class=" list-group-item p-4">
                                                                    <?=$notes[$row]; ?>
                                                                </li>
                                                                <?php
                                                            }
                                                            ?>

                                                        </ul>
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
                            header('Location: workout.php');
                            exit();
                        }

                        $deleteId = $_GET['delete_id'];
                        $result   = $workout->delete($deleteId);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('Workout routine has been successfully deleted!');
                        }
                        else{
                            $_SESSION['alert']['type'] = 'danger';
                            $_SESSION['alert']['msg']  = array('Data deletion failed!');
                        }
                        header('Location: workout.php');
                        exit();
                    }
                    else if($action=='edit'){
                        $redirect = false;
                        if(!isset($_GET['edit_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $singleData = $workout->readById($_GET['edit_id']);
                        }

                        if($singleData==null || $redirect){
                            header('Location: workout.php');
                            exit();
                        }

                        $daysArray = array('ED'=>'EVERY',
                            'SUN'=> 'SUN',
                            'MON'=> 'MON',
                            'TUE'=> 'TUE',
                            'WED'=> 'WED',
                            'THU'=> 'THU',
                            'FRI'=> 'FRI',
                            'SAT'=> 'SAT'
                        );

                        ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Update Workout Routine of <br><br><b><?=$singleData->client_name; ?></b></h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form class="row" method="post" action="workout.php?action=update">
                                    <div class="form-group col-md-9">
                                        <label>Title</label>
                                        <input type="text" class="form-control" name="title" minlength="2" maxlength="250" value="<?=$singleData->title; ?>" required>
                                        <span class="form-text text-sm text-info">Keep Name,Title, Note within 250 characters</span>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Interval</label>
                                        <select class="form-control" name="interval">
                                            <option value="">--Select--</option>
                                            <?php
                                            for($days=7, $init=30; $days<=168; $days+=7){
                                                $option = $value = null;
                                                $cal_1  = $days/7;
                                                $cal_2  = ($days/7)/4;
                                                if($cal_1<=3 && $cal_2<1){
                                                    $option = $cal_1 == 1 ? $cal_1.' Week' : $cal_1.' Weeks';
                                                }
                                                else if($cal_1>3){
                                                    $option = $cal_2 == 1 ? $cal_2.' Month' : $cal_2.' Months';
                                                }
                                                $value = $days;
                                                if($days%28==0){
                                                    $value = ($init-$value)+$value;
                                                    $init+=30;
                                                }

                                                $value = $value."__".$option;

                                                ?>

                                                <option value="<?=$value; ?>" ><?=$option;?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="form-text text-muted">Previous Interval (<?=$singleData->interval_days; ?>)</span>
                                    </div>
                                    <div class="form-group col-md-12 mt-3">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input custom-control-input-danger" type="checkbox" id="workoutRoutineCheckBox" name="w_status" <?php if($singleData->w_status=='archived'){ echo 'checked'; } ?> >
                                            <label for="workoutRoutineCheckBox" class="custom-control-label">
                                            <?php if($singleData->w_status=='active'){ echo 'This routine is active, check it to archive'; }else if($singleData->w_status=='archived'){ echo 'This routine is achieved, uncheck it make it active'; } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" name="prev_wstatus" value="<?=$singleData->w_status; ?>" readonly required>
                                    <input type="hidden" name="id" value="<?=$singleData->id; ?>" readonly required>
                                    <input type="hidden" name="client_id" value="<?=$singleData->client_id; ?>" readonly required>
                                    <div class="form-group col-md-12 table-overflow-fix mt-4">
                                        <p class="w-100">Workout List</p>
                                        <?php
                                        $names = explode('_sep_',$singleData->name);
                                        $days  = explode('_sep_',$singleData->days);
                                        $sets  = explode('_sep_',$singleData->sets);
                                        $reps  = explode('_sep_',$singleData->reps);
                                        $notes = explode('_sep_',$singleData->note);

                                        $countRows = count($names);

                                            for($row=0; $row<$countRows; ++$row){

                                        ?>
                                        <table class="table table-bordered table-striped">
                                            <tbody class="workout-tbody-template">
                                            <tr>
                                                <td colspan="2"><input class="form-control" type="text" name="name[]" maxlength="100" value="<?=$names[$row]; ?>" required>
                                                    <span class="form-text text-muted">Name</span>
                                                </td>
                                                <td class="text-right"><span class="badge badge-light"><?=($row+1); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="width: 40%;">
                                                    <select class="form-control" name="days[]" required>
                                                        <option value="">--Day--</option>
                                                        <?php
                                                            foreach ($daysArray as $key=> $val){
                                                                $daySelected = $key==$days[$row] ? 'selected' : null;
                                                        ?>
                                                                <option value="<?=$key; ?>" <?=$daySelected; ?>><?=$val; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <span class="form-text text-muted">Day</span>
                                                </td>
                                                <td style="width: 30%;"><input class="form-control" type="number" min="0" name="sets[]" value="<?=$sets[$row]; ?>" required>
                                                <span class="form-text text-muted">Sets</span>
                                                </td>
                                                <td style="width: 30%;"><input class="form-control" type="number" min="0"  name="reps[]"  value="<?=$reps[$row]; ?>" required>
                                                    <span class="form-text text-muted">Reps</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><input class="form-control" type="text"  maxlength="250" name="note[]" value="<?=$notes[$row]; ?>" required>
                                                    <span class="form-text text-muted">Note</span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group col-12 text-right mt-4">
                                        <button  type="submit" class="btn btn-info w-100">Update Routine&ensp;<i class="fas fa-edit"></i></button>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->

                        </div>
                    <?php }
                    else if($action=='update'){
                        $errors = array();

                        if(empty(trim($_POST['title'])) || !isset($_POST['interval'])){
                            $errors[] = 'Empty fields found, try again!';
                        }
                        else if(mb_strlen($_POST['title'])>250 || mb_strlen($_POST['title'])<2){
                            $errors[] = 'Invalid title size!';
                        }

                        if(!isset($_POST['w_status'])){
                            $_POST['w_status'] = 'active';
                        }
                        else{
                            $_POST['w_status'] = 'archived';
                        }


                        if(empty($errors)){
                            $updateData = array();
                            extract($_POST);

                            $name     = implode("_sep_",$name);
                            $days     = implode("_sep_",$days);
                            $sets     = implode("_sep_",$sets);
                            $reps     = implode("_sep_",$reps);
                            $note     = implode("_sep_",$note);
                            $lastUpdated = date('Y-m-d');

                            $updateData = array('title'=>$title, 'last_updated'=>$lastUpdated, 'name'=>$name, 'days'=>$days, 'sets'=>$sets, 'reps'=>$reps, 'note'=>$note);

                            if($w_status!=$prev_wstatus){
                                $updateData['w_status'] = $w_status;
                            }

                            if(!empty(trim($interval))){
                                $interval = explode("__",$interval);
                                $end_date = date('Y-m-d', strtotime(($interval[0]+1).' days'));
                                $updateData['interval_days'] = $interval[1];
                                $updateData['end_date'] = $end_date;
                            }

                            $updateId = array('id'=>$id, 'client_id'=>$client_id);
                            var_dump($updateData);
                            $result = $workout->update($updateId, $updateData);

                            if($result){
                                $_SESSION['alert']['type'] = 'success';
                                $_SESSION['alert']['msg']  = array('Workout routine updated successfully!');
                                header('Location: workout.php');
                                exit();
                            }
                            else{
                                $errors[] = 'Failed to update!';
                            }

                            if(!empty($errors)){
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = $errors;
                            }

                            header('Location: workout.php?action=edit&edit_id='.$_POST['id']);
                            exit();
                        }
                    }
                    else if($action=='print'){
                        $redirect = false;
                        if(!isset($_GET['print_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $singleData = $workout->readById($_GET['print_id']);
                        }

                        if($singleData==null || $redirect){
                            header('Location: workout.php');
                            exit();
                        }
                    ?>
                        <div class="card card-gray">
                            <div class="card-header p-3">
                                <h3 class="card-title">
                                    Print Workout Routine <br>
                                    <small >
                                        (Don 't forget to reload the page after printing)
                                    </small>
                                </h3>
                                <div class="card-tools mr-2">
                                    <button id="print-button" type="button" class="btn btn-success" >
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <div class="card bg-light rounded-0" id="print-elem">
                                    <div class="card-body row px-3">
                                        <div class="col-12 text-right text-sm">
                                            <span><?=date('d-m-Y'); ?></span>
                                        </div>
                                        <div class="col-12">
                                            <h4 class="text-center mb-3 py-2 px-0">
                                                <img class="mr-2" src="<?=$siteIcon;?>" style="max-width:40px; min-width:40px; max-height:40px; min-height:40px; border-radius: 50%;">
                                                <b><?=$siteTitle; ?></b>
                                            </h4>
                                        </div>
                                        <div class="col-12 mb-4">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item bg-light">
                                                    <span class="d-inline-block">Trainer:</span>
                                                    <span class="d-inline-block float-right"><?=$siteOwnerName; ?></span>
                                                </li>
                                                <li class="list-group-item bg-light">
                                                    <span class="d-inline-block">Title:</span>
                                                    <span class="d-inline-block float-right"><?=$singleData->title; ?></span>
                                                </li>
                                                <li class="list-group-item bg-light">
                                                    <span class="d-inline-block">Creation Date:</span>
                                                    <span class="d-inline-block float-right"><?=date('d M y', strtotime($singleData->start_date)); ?></span>
                                                </li>
                                                <li class="list-group-item bg-light">
                                                    <span class="d-inline-block">Interval:</span>
                                                    <span class="d-inline-block float-right"><?=$singleData->interval_days; ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="text-center font-weight-bold">WORKOUTS</p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="table-overflow-fix">
                                                        <table class="table table-bordered" >
                                                            <thead>
                                                            <tr class="border-dark">
                                                                <th style="width: 6%;">SL#</th>
                                                                <th >Name</th>
                                                                <th style="width: 10%;">Day</th>
                                                                <th style="width: 8%;">Sets</th>
                                                                <th style="width: 8%;">Reps</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $names = explode('_sep_',$singleData->name);
                                                            $days  = explode('_sep_',$singleData->days);
                                                            $sets  = explode('_sep_',$singleData->sets);
                                                            $reps  = explode('_sep_',$singleData->reps);

                                                            $countRows = count($names);

                                                            for($row=0; $row<$countRows; ++$row){
                                                                ?>
                                                                <tr class="border-dark">
                                                                    <td><?=($row+1);?></td>
                                                                    <td><?=$names[$row];?></td>
                                                                    <td><?php if($days[$row]=='ED'){ echo 'EVERY'; }else{ echo $days[$row]; } ?></td>
                                                                    <td><?=$sets[$row]; ?></td>
                                                                    <td><?=$reps[$row]; ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <p class="text-center font-weight-bold">NOTES</p>
                                                </div>
                                                <div class="col-12 mb-5">
                                                    <ul class="list-group rounded-0">
                                                        <?php
                                                        $notes = explode('_sep_',$singleData->note);
                                                        for ($row=0; $row<$countRows; ++$row){
                                                            ?>
                                                            <li class=" list-group-item bg-light">
                                                                <u class="d-block text-sm mb-2">Refer to workout SL#<?=($row+1);?></u>
                                                                <p class="px-2"><?=$notes[$row]; ?></p>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card-body -->

                        </div>
                    <?php }
                    else{
                        header('Location: workout.php');
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
        "ajax": "datatables/get_all_workouts.php",
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


    let currWorkoutRow = 1;

    $('#add-workout-btn').click(function (){
        let workoutFieldTemplate = '<tbody class="workout-tbody-template">'+
            '<tr>'+
            '<td colspan="2"><input class="form-control" type="text" placeholder="Workout" name="name[]" maxlength="100" required></td>'+
            '<td class="text-center">' +
            '<button type="button" class="btn btn-sm btn-danger workout-del-btn">'+
            '<i class="fas fa-times"></i>'+
            '</button>' +
            '</td>'+
            '</tr>'+
            '<tr>'+
            '<td style="width: 40%;">'+
            '<select class="form-control" name="days[]" required>'+
            '<option value="">--Day--</option>'+
            '<option value="ED">EVERY</option>'+
            '<option value="SUN">SUN</option>'+
            '<option value="MON">MON</option>'+
            '<option value="TUE">TUE</option>'+
            '<option value="WED">WED</option>'+
            '<option value="THU">THU</option>'+
            '<option value="FRI">FRI</option>'+
            '<option value="SAT">SAT</option>'+
            '</select>'+
            '</td>'+
            '<td style="width: 30%;"><input class="form-control" type="number" min="0" placeholder="Set" name="sets[]" required></td>'+
            '<td style="width: 30%;"><input class="form-control" type="number" min="0" placeholder="Rep" name="reps[]" required></td>'+
            '</tr>'+
            '<tr>'+
            '<td colspan="3"><input class="form-control" type="text" placeholder="Note" maxlength="250" name="note[]" required></td>'+
            '</tr>'+
            '</tbody>';

        if(currWorkoutRow<200){
            currWorkoutRow++;
            $('#workout-table').append(workoutFieldTemplate);
        }

    });

    $('#workout-table').on('click', '.workout-del-btn', function (e) {
        $(this).closest('tbody').remove();
        currWorkoutRow--;
    });

    $(document).on('click', '#print-button', function (){
        const original   = $('body').html();
        const print_page = $('#print-elem').html();
        $('body').html(print_page);
        window.print();
        $('body').html(original);
    });

</script>
<?php
include 'includes/main_app_footer.php';
?>

