<?php

require_once 'requires/DB.php';
require_once 'requires/SiteSetting.php';
require_once 'requires/Client.php';
require_once 'requires/Rating.php';
require_once 'requires/Dashboard.php';

//error_reporting(0);
ini_set('session.cache_limiter','public');
ob_start();
date_default_timezone_set('Asia/Dhaka');
session_cache_limiter(false);
session_start();

    if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
        header('Location: index.php');
        exit();
    }


$siteSetting    = new Trainer\SiteSetting();
$siteInfoData   = $siteSetting->read();
$siteTitle      = $siteInfoData->title;
$siteIcon       = $siteInfoData->icon;
$siteOwnerName  = $siteInfoData->trainer_name;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
