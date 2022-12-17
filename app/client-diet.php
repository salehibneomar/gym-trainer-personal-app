<?php
include 'includes/main_app_header.php';
require_once 'requires/Diet.php';

    if($_SESSION['user']['type']!=='client'){
        header('Location: dashboard.php');
        exit();
    }

    $clientId  = $_SESSION['user']['id'];
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
                                <h3 class="card-title">My All Diet Plans</h3>
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
                            $singleData = $diet->readByIdAndClientId($_GET['view_id'], $clientId);
                        }

                        if($singleData==null || $redirect){
                            header('Location: client-diet.php');
                            exit();
                        }
                    ?>
                        <div class="card card-gray" >
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
                                                    <div class="card-body">
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
                                                    <div class="card-body">
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
                                                    <div class="card-body">
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
                    else{
                        header('Location: client-diet.php');
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
        "ajax": "datatables/get_all_diets_by_client.php",
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

</script>
<?php
include 'includes/main_app_footer.php';
?>

