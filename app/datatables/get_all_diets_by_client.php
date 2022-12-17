<?php

date_default_timezone_set('Asia/Dhaka');
ob_start();
session_start();

if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
    header('Location: index.php');
    exit();
}
else if($_SESSION['user']['type']!=='client'){
    header('Location: dashboard.php');
    exit();
}

$table      = 'diets_view';
$primaryKey = 'id';
$clientId   = $_SESSION['user']['id'];
$where      = "client_id='$clientId'";
$sl         = 0;

$columns = array(
    array( 'db'        => 'id',               'dt' => 0,
        'formatter' => function($d, $row){
            global $sl;
            return '<span class="badge badge-light">'.(++$sl).'</span>';
        }
    ),
    array( 'db'     => 'title',               'dt' => 1,
        'formatter' => function($d, $row){
            return '<span class="text-truncate-dt">'.$d.'</span>';
        }
    ),
    array( 'db'        => 'd_status',          'dt' => 2,
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
    array( 'db'        => 'id',          'dt' => 3,
        'formatter'    => function($d, $row){
            return '<a href="client-diet.php?action=view&view_id='.$d.'" class="btn btn-success btn-sm mr-1 mb-1"><i class="fas fa-eye"></i></a>';
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
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, $where)
);

unset($sl);

ob_end_flush();
