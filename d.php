<?php
require_once 'config/config.php';
require_once 'class/AccessControl.php';

if(!isset($_GET['fid']) || empty($_GET['fid'])){
    header('location:404.php');
    exit;
}

$user_id = null;

if(is_logged_in()){
    $user_id = session()->get('user_id');
}

$fid = stripslashes(trim($_GET['fid']));

$check_is_folder = get_folder($fid, false);

$file_obj = $check_is_folder;

if(!$check_is_folder){

    $check_is_file = get_file($fid, false);

    $file_obj = $check_is_file;

}

if(!$file_obj){
    header('location:404.php');
    exit;
}

$access = new AccessControl($file_obj, $user_id);
$read_status = $access->canRead();

$read_file = false;

if($read_status == AccessControl::PRIVATE_FILE){
    header('location:404.php');
    exit;
}

if($read_status == AccessControl::AUTHENTICATION_NEEDED){
    session()->set('auth_needed', true);
    header('location:auth_needed.php');
    exit;
}

if($read_status == AccessControl::YES){

    //logic for zipping and sending file to browser
    

}
