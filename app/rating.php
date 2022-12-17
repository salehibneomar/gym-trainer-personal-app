<?php
include 'includes/main_app_header.php';
require_once 'requires/Gym.php';

if($_SESSION['user']['type']!=='trainer'){
    header('Location: dashboard.php');
    exit();
}

$trainerId = $_SESSION['user']['id'];
$action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : "view_all";
$rating = new Rating\Rating();

?>

<title><?=$siteTitle;?>  | Ratings</title>

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
                                <h3 class="card-title">All Ratings</h3>
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
                                        <th style="width: 8%;">ID#</th>
                                        <th style="width: 5%;">Star</th>
                                        <th>Remark</th>
                                        <th style="width: 10%;">Date</th>
                                        <th style="width: 8%;">Status</th>
                                        <th style="width: 3%;">By</th>
                                        <th style="width: 15%;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div><!-- /.card-body -->
                        </div>
                    <?php } else if($action=='edit'){
                        $redirect = false;
                        if(!isset($_GET['edit_id'])){
                            $redirect = true;
                        }

                        $singleData = null;
                        if(!$redirect){
                            $editId = array('id'=>$_GET['edit_id'], 'trainer_id'=>$trainerId);
                            $singleData   = $rating->readById($editId);
                        }

                        if($singleData==null || $redirect){
                            header('Location: rating.php');
                            exit();
                        }

                        ?>
                        <div class="card card-gray" >
                            <div class="card-header p-3">
                                <h3 class="card-title">Edit Status</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form action="rating.php?action=update" method="post">
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label>Rating Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="r_status" required>
                                                <option value="">-- Select --</option>
                                                <option value="live" <?php if($singleData->r_status=='live'){ echo 'selected'; }  ?> >Live</option>
                                                <option value="hidden" <?php if($singleData->r_status=='hidden'){ echo 'selected'; }  ?> >Hidden</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="editId" value="<?=$singleData->id;?>" readonly required>
                                        <div class="col-md-12 form-group text-right">
                                            <button type="submit" class="btn btn-info" >Update&ensp;<i class="fas fa-edit"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    <?php }
                    else if($action=='update'){
                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            if(!isset($_POST['r_status']) || empty($_POST['editId'])){
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg'] = array('Empty form fields detected!');
                            }
                            else{
                                $result = $rating->updateStatus($_POST['editId'], $_POST['r_status']);

                                if($result){
                                    $_SESSION['alert']['type'] = 'success';
                                    $_SESSION['alert']['msg'] = array('Rating status successfully updated!');
                                }
                                else{
                                    $_SESSION['alert']['type'] = 'danger';
                                    $_SESSION['alert']['msg'] = array('Failed to update rating status!');
                                }

                            }
                            header('Location: rating.php');
                            exit();
                        }
                    }
                    else if($action=='delete'){
                        $result = false;
                        if(!isset($_GET['delete_id'])){
                            header('Location: rating.php');
                            exit();
                        }

                        $deleteId = array('id'=>$_GET['delete_id'], 'trainer_id'=>$trainerId);
                        $result   = $rating->delete($deleteId);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('Successfully deleted!');
                        }
                        else{
                            $_SESSION['alert']['type'] = 'danger';
                            $_SESSION['alert']['msg']  = array('Data deletion failed!');
                        }
                        header('Location: rating.php');
                        exit();
                    }
                    else{
                        header('Location: rating.php');
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
        "ajax": "datatables/get_all_ratings.php",
        "pageLength": 5,
        "lengthMenu": [5, 10, 15],
        "columnDefs": [
            {
                "targets": 6,
                "orderable": false
            }
        ],
        "order": [[ 0, "desc" ]]
    });
</script>

<?php
include 'includes/main_app_footer.php';
?>

