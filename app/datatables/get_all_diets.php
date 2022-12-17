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

$table      = 'diets_view';
$primaryKey = 'id';
$globalClientId   = null;

$columns = array(
    array( 'db'        => 'id',               'dt' => 0,
        'formatter' => function($d, $row){
            return '<span class="badge badge-light">'.$d.'</span>';
        }
    ),
    array( 'db'     => 'client_id',         'dt' => 5,
        'formatter' => function($d, $row){
            global $globalClientId;
            $globalClientId = $d;
        }
    ),
    array( 'db'     => 'client_name',           'dt' => 1,
        'formatter' => function($d, $row){
            global $globalClientId;
            return '<a class="btn btn-sm btn-primary" href="client.php?action=view&view_id='.$globalClientId.'"><i class="fas fa-user"></i>&ensp;'.$d.'</a>';
        }
    ),
    array( 'db'     => 'title',               'dt' => 2,
        'formatter' => function($d, $row){
            return '<span class="text-truncate-dt">'.$d.'</span>';
        }
    ),
    array( 'db'        => 'd_status',          'dt' => 3,
        'formatter'    => function($d, $row){
            $status = null;
            if($d=='active'){
                $status = '<span class="badge badge-success text-uppercase">'.$d.'</span>';
            }
            else if($d=='archived'){
                $status = '<span class="badge badge-secondary text-uppercase">'.$d.'</span>';
            }
            return $status;
        }
    ),
    array( 'db'        => 'id',                 'dt' => 4,
        'formatter'    => function($d, $row){

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
                                                                    <a class="btn btn-success" href="diet.php?action=delete&delete_id='.$d.'">Okay</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';

            $edit = '<a href="diet.php?action=edit&edit_id='.$d.'" class="btn btn-info btn-sm mr-1 mb-1"><i class="fas fa-edit"></i></a>';
            $view = '<a href="diet.php?action=view&view_id='.$d.'" class="btn btn-success btn-sm mr-1 mb-1"><i class="fas fa-eye"></i></a>';

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
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns)
);

unset($globalClientId);

ob_end_flush();
