<?php
include 'includes/main_app_header.php';
require_once 'requires/Workout.php';

    if($_SESSION['user']['type']!=='client'){
        header('Location: dashboard.php');
        exit();
    }

    $clientId  = $_SESSION['user']['id'];
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
                                <h3 class="card-title">My All Workout Routines</h3>
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
                                        <th style="width: 5%">SL#</th>
                                        <th >Title</th>
                                        <th style="width: 8%">Status</th>
                                        <th style="width: 5%">View</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }
                    else if($action=='view'){
                        $redirect = false;
                        if(!isset($_GET['view_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $singleData = $workout->readByIdAndClientId($_GET['view_id'], $clientId);
                        }

                        if($singleData==null || $redirect){
                            header('Location: client-workout.php');
                            exit();
                        }

                        ?>

                        <div class="card card-gray">
                            <div class="card-header p-3">
                                <h3 class="card-title"><?=$singleData->title; ?></h3>
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

                                                <div id="collapseInfo" class="collapse" data-parent="#workoutAccordion">
                                                    <div class="card-body bg-dark">
                                                        <div class="text-right">
                                                            <a href="client-workout.php?action=print&print_id=<?=$singleData->id; ?>" class="btn btn-primary"><i class="fas fa-file-powerpoint"></i></a>
                                                        </div>
                                                        <ul class="list-group list-group-flush">
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
                                                            <?php if($singleData->w_status=='active'){ ?>
                                                                <li class="list-group-item"><b class="d-block mb-2">Remaining Days:</b>
                                                                    <?php
                                                                    $st   = date_create(date('Y-m-d'));
                                                                    $ed   = date_create($singleData->end_date);
                                                                    $diff = date_diff($st, $ed);

                                                                    if($diff->format('%R')=='-'){

                                                                        ?>
                                                                        <h5><span class="badge badge-danger p-2">Expired</span></h5>
                                                                    <?php }else if($diff->format('%a')==0){ ?>
                                                                        <h5><span class="badge badge-warning p-2">Last Day</span></h5>
                                                                    <?php }else{ $rem = $diff->format('%a'); ?>
                                                                        <h5><span class="badge badge-success p-2"><?php if($rem>1){ echo $rem.' Days'; }else{ echo $rem.' Day'; } ?></span></h5>
                                                                    <?php } ?>
                                                                </li>
                                                            <?php } ?>
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
                                                                        Refer to workout SL#<?=($row+1); ?>
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
                    else if($action=='print'){
                        $redirect = false;
                        if(!isset($_GET['print_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $singleData = $workout->readByIdAndClientId($_GET['print_id'], $clientId);
                        }

                        if($singleData==null || $redirect){
                            header('Location: client-workout.php');
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
                                                    <span class="d-inline-block">To:</span>
                                                    <span class="d-inline-block float-right"><?=$_SESSION['user']['name']; ?></span>
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
                    else if($action=='workout_today'){
                            $redirect = false;
                            if(!isset($_GET['view_id'])){
                                $redirect = true;
                            }

                            $singleData = null;
                            if(!$redirect){
                                $singleData = $workout->readByIdAndClientId($_GET['view_id'], $clientId);
                            }

                            if($singleData==null || $redirect){
                                header('Location: dashboard.php');
                                exit();
                            }
                            else if($singleData->w_status!='active'){
                                header('Location: dashboard.php');
                                exit();
                            }
                        $currDay = strtoupper(date('D'));
                        ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Today's Workouts (<b><?=$currDay;?></b>)
                                    <br>
                                    <span class="text-sm text-muted"><?=$singleData->title; ?></span>
                                </h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <?php
                                $names = explode('_sep_',$singleData->name);
                                $days  = explode('_sep_',$singleData->days);
                                $sets  = explode('_sep_',$singleData->sets);
                                $reps  = explode('_sep_',$singleData->reps);
                                $notes = explode('_sep_',$singleData->note);

                                $countRows = count($names);

                                ?>
                                <ul class="list-group list-group-flush">
                                    <?php for($row=0; $row<$countRows; ++$row){ if($days[$row]=='ED' || $days[$row]==$currDay){ ?>
                                    <li class="list-group-item bg-gray"><b class="text-uppercase"><?=$names[$row];?></b></li>
                                        <ul class="list-group rounded-0">
                                            <li class="list-group-item pl-5"><b class="text-sm">SET:&emsp;</b><?=$sets[$row]; ?></li>
                                            <li class="list-group-item pl-5"><b class="text-sm">REP:&emsp;</b><?=$reps[$row]; ?></li>
                                            <li class="list-group-item pl-5"><b class="d-block text-sm mb-2">NOTE:</b>
                                                <?=$notes[$row]; ?>
                                            </li>
                                        </ul>
                                    <?php } } ?>
                                </ul>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }
                    else{
                        header('Location: client-workout.php');
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
        "ajax": "datatables/get_all_workouts_by_client.php",
        "pageLength": 5,
        "lengthMenu": [5, 10, 15],
        "columnDefs": [
            {
                "targets": 3,
                "orderable": false
            }
        ],
        "info": false,
        "order": [[ 2, "asc" ]]
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

