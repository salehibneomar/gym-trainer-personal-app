<?php
include 'includes/main_app_header.php';
require_once 'requires/SocialMedia.php';

    if($_SESSION['user']['type']!=='trainer'){
        header('Location: dashboard.php');
        exit();
    }

    $trainerId = $_SESSION['user']['id'];
    $socialMedia = new Trainer\SocialMedia($trainerId);

    $action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : "view_all";
?>

<title><?=$siteTitle;?>  | Social Media</title>

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
                                <h3 class="card-title">All Social Media</h3>
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
                                        <th style="width: 20%">Platform</th>
                                        <th>Link</th>
                                        <th style="width: 5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $allData = $socialMedia->readAll();
                                            if($allData!=null){
                                                foreach ($allData as $row){

                                        ?>
                                                    <tr>
                                                        <td class="text-uppercase"><span class="font-weight-bold badge badge-btn badge-light
"><?=$row->icon." ".$row->platform;?></span></td>
                                                        <td class="text-truncate"><a href="<?=$row->link;?>"><?=$row->link;?></a></td>
                                                        <td>
                                                            <div class="text-center">
                                                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletemodal_<?=$row->id;?>"><i class="fas fa-trash-alt"></i></button>
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
                                                                            <a class="btn btn-success" href="social-media.php?action=delete&delete_id=<?=$row->id;?>">Okay</a>
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
                                <h3 class="card-title">Add New Social Media Handle</h3>
                                <div class="card-tools mr-2">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div><!-- /.card-header -->

                            <div class="card-body">
                                <form action="social-media.php?action=insert" method="post" >
                                    <div class="row">
                                        <div class="form-group col-lg-3 col-md-4 col-sm-12">
                                            <label>Platform <span class="text-danger">*</span></label> <br>
                                            <select class="form-control select2" style="width: 100%;" name="social_media_handle" required>
                                                <option value="">--Select--</option>
                                                <option value='facebook___<i class="fab fa-facebook"></i>'>Facebook</option>
                                                <option value='instagram___<i class="fab fa-instagram"></i>'>Instagram</option>
                                                <option value='twitter___<i class="fab fa-twitter"></i>'>Twitter</option>
                                                <option value='linkedin___<i class="fab fa-linkedin"></i>'>LinkedIn</option>
                                                <option value='youtube___<i class="fab fa-youtube"></i>'>YouTube</option>
                                                <option value='whatsapp___<i class="fab fa-whatsapp"></i>'>WhatsApp</option>
                                            </select>
                                            <span class="form-text text-sm text-info">You can also create custom platform by typing in the input field</span>
                                        </div>
                                        <div class="form-group col-lg-9 col-md-8 col-sm-12">
                                            <label>Paste link here <span class="text-danger">*</span></label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                                                </div>
                                                <input type="url" class="form-control" name="link" required>
                                            </div>

                                        </div>
                                        <div class="form-group col-12 text-right">
                                            <button type="submit" class="btn btn-primary" name="addBtn">Add&ensp;<i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.card-body -->
                        </div>
                    <?php } else if($action=='insert'){

                        if($_SERVER['REQUEST_METHOD']=='POST'){
                            extract($_POST);

                            $social_media_handle = explode("___", $social_media_handle);
                            $icon = '<i class="fas fa-globe"></i>';
                            $platform = $social_media_handle[0];

                            if(count($social_media_handle)==2){
                                $icon = $social_media_handle[1];
                            }

                            $socialMediaInsertionData = array('platform'=>$platform, 'link'=>$link, 'icon'=>$icon);

                            $result = $socialMedia->create($socialMediaInsertionData);
                            if($result) {
                                $_SESSION['alert']['type'] = 'success';
                                $_SESSION['alert']['msg']  = array('Social Media has been successfully added!');
                                header('Location: social-media.php');
                            }
                            else{
                                $_SESSION['alert']['type'] = 'danger';
                                $_SESSION['alert']['msg']  = array('Data insertion failed!');
                                header('Location: social-media.php?action=add_new');
                            }
                            exit();

                        }

                      }
                    else if($action=='delete'){
                        $result = false;
                        if(!isset($_GET['delete_id'])){
                            header('Location: social-media.php');
                            exit();
                        }

                        $deleteId = $_GET['delete_id'];
                        $result   = $socialMedia->delete($deleteId);

                        if($result){
                            $_SESSION['alert']['type'] = 'success';
                            $_SESSION['alert']['msg']  = array('Social Media has been successfully removed!');
                        }
                        else{
                            $_SESSION['alert']['type'] = 'danger';
                            $_SESSION['alert']['msg']  = array('Data deletion failed!');
                        }
                        header('Location: social-media.php');
                        exit();

                    }
                    else{
                        header('Location: social-media.php');
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
        tags: true
    });

    $('#data_tables').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10]
    });
</script>
<?php
include 'includes/main_app_footer.php';
?>

