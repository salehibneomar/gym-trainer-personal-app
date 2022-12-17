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

$table      = 'clients_view';
$primaryKey = 'id';
$trainerId  = $_SESSION['user']['id'];
$where      = "trainer_id='$trainerId' AND acc_status!='hidden'";


$columns = array(
    array( 'db'        => 'id',               'dt' => 0,
           'formatter' => function($d, $row){
                return '<span class="badge badge-light">'.$d.'</span>';
           }
         ),
    array( 'db'        => 'profile_picture',  'dt' => 1,
           'formatter' => function($d, $row){
                $image = (is_null($d) || empty($d)) ? "images/default_image.png" : "images/client/".$d;
                return '<img src="'.$image.'" class="client-table-profile-pic" >';
           }
         ),
    array( 'db'        => 'name',             'dt' => 2 ),
    array( 'db'        => 'phone',            'dt' => 3 ),
    array( 'db'        => 'gym_name',         'dt' => 4,
           'formatter' =>  function($d, $row){
                return mb_substr($d,0, 10);
           }
         ),
    array( 'db'        => 'acc_status',       'dt' => 5,
        'formatter'    => function($d, $row){
            $status = null;
            if($d=='locked'){
                $status = '<span class="badge badge-danger text-uppercase">'.$d.'</span>';
            }
            else if($d=='active'){
                $status = '<span class="badge badge-success text-uppercase">'.$d.'</span>';
            }
            else if($d=='pending'){
                $status = '<span class="badge badge-warning text-uppercase">'.$d.'</span>';
            }
            return $status;
        }
    ),
    array( 'db'        => 'id',               'dt' => 6,
        'formatter'    => function($d, $row){
            $view = '<a class="btn btn-success btn-sm mr-1 mb-1" href="client.php?action=view&view_id='.$d.'"><i class="fas fa-eye"></i></a>';
            $edit = '<a class="btn btn-info btn-sm mr-1 mb-1" href="client.php?action=edit&edit_id='.$d.'"><i class="fas fa-edit"></i></a>';
            return $view." ".$edit;
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

ob_end_flush();
