<?php

date_default_timezone_set('Asia/Dhaka');
ob_start();
session_start();

if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
    header('Location: index.php');
    exit();
}
else if($_SESSION['user']['type']!=='trainer'){
    header('Location: dashboard.php');
    exit();
}

$table      = 'ratings';
$primaryKey = 'id';
$trainerId  = $_SESSION['user']['id'];
$where      = "trainer_id='$trainerId'";
$remarkData = null;

$columns = array(
    array( 'db'        => 'id',                 'dt' => 0,
           'formatter' => function($d, $row){
                return '<span class="badge badge-light">'.$d.'</span>';
           }
         ),
    array( 'db'        => 'star',               'dt' => 1),
    array( 'db'        => 'remark',             'dt' => 2,
        'formatter' => function($d, $row){
        global $remarkData;
        $remarkData = $d;
            return '<span class="text-truncate-dt">'.$d.'</span>';
        }
    ),
    array( 'db'        => 'date_posted',         'dt' => 3),
    array( 'db'        => 'r_status',            'dt' => 4,
        'formatter'    => function($d, $row){
            $status = null;
            if($d=='hidden'){
                $status = '<span class="badge badge-danger text-uppercase">'.$d.'</span>';
            }
            else if($d=='live'){
                $status = '<span class="badge badge-success text-uppercase">'.$d.'</span>';
            }
            else if($d=='pending'){
                $status = '<span class="badge badge-warning text-uppercase">'.$d.'</span>';
            }
            return $status;
        }
    ),
    array( 'db'        => 'client_id',             'dt' => 5,
        'formatter' => function($d, $row){
            return '<a class="btn btn-primary btn-sm mr-1 mb-1" href="client.php?action=view&view_id='.$d.'"><i class="fas fa-user"></i></a>';
        }
    ),
    array( 'db'        => 'id',                    'dt' => 6,
        'formatter'    => function($d, $row){
        global $remarkData;

            $view = '<button type="button" class="btn btn-success btn-sm mr-1 mb-1" data-toggle="modal" data-target="#viewmodal_'.$d.'"><i class="fas fa-eye"></i></button>

                                                    <div class="modal fade" id="viewmodal_'.$d.'">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-gray">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Whole Remark</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    '.$remarkData.'
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';

            $delete = '<button type="button" class="btn btn-danger btn-sm mr-1 mb-1" data-toggle="modal" data-target="#deletemodal_'.$d.'"><i class="fas fa-trash"></i></button>

                                                    <div class="modal fade" id="deletemodal_'.$d.'">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-gray">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                                                                </div>
                                                                <div class="modal-body">
                                                                    You won\'t be able to revert this operation.
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <a class="btn btn-success" href="rating.php?action=delete&delete_id='.$d.'">Okay</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';

            $edit = '<a href="rating.php?action=edit&edit_id='.$d.'" class="btn btn-info btn-sm mr-1 mb-1"><i class="fas fa-edit"></i></a>';

            return $view." ".$edit." ".$delete;
        }
    )

);


require_once '../requires/DB.php';

$dbInfo = DB::getDb()->info();

$sql_details = array(
    'user' => $dbInfo['user'],
    'pass' => $dbInfo['pass'],
    'db'   => $dbInfo['db'],
    'host' => $dbInfo['host']
);

require( 'ssp.class.php' );

echo json_encode(
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, $where )
);

unset($remarkData);

ob_end_flush();
