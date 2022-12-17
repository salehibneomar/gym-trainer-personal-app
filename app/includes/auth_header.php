<?php

require_once 'requires/DB.php';
require_once 'requires/SiteSetting.php';
require_once 'requires/Trainer.php';
require_once 'requires/Client.php';
require_once 'requires/Gym.php';

//error_reporting(0);
ini_set('session.cache_limiter','public');
ob_start();
date_default_timezone_set('Asia/Dhaka');
session_cache_limiter(false);
session_start();

$siteSetting    = new Trainer\SiteSetting();
$siteInfoData   = $siteSetting->read();
$siteTitle      = $siteInfoData->title;
$siteIcon       = $siteInfoData->icon;

    if(isset($_SESSION['user']) && !empty($_SESSION['user'])){
        header('Location: dashboard.php');
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$siteTitle;?></title>
    <link rel="shortcut icon" href="<?=$siteIcon;?>" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    </head>
<body class="hold-transition dark-mode login-page">

<!-- Preloader -->
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble" src="<?=$siteIcon;?>" alt="ICON" height="60" width="60">
</div>