<?php
require_once 'config/config.php';
require_once 'class/AccessControl.php';
require_once 'class/FilePreview.php';

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
    header('location:/404.php');
    exit;
}



$access = new AccessControl($file_obj, $user_id);
$read_status = $access->canRead();
$read_file = false;

if($read_status === AccessControl::PRIVATE_FILE){
    header('location:404.php');
    exit;
}

if($read_status === AccessControl::AUTHENTICATION_NEEDED){
    session()->set('auth_needed', true);
    header('location:auth_needed.php');
    exit;
}

if($read_status == AccessControl::YES){

$file_path = 'file_system/user_'.$access->get_user_id().'/';

if(isset($file_obj->folder_id)){
    $file_path.= 'FOLDER_ID_'.$file_obj->folder_id.'_'.$file_obj->title;
}

$f = new FilePreview($file_path, $file_obj, ORM::class);

$f->buildPreviewOrDownload();
    

}