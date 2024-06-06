<?php

require_once '../config/config.php';

/**
 * This file is used to upload files , validate files,
 */

 $response = [];

function is_valid_extension($file_name){
    $ext = explode('.', $file_name);

    if(count($ext) > 2){
        $response[] = array('status'=>'error', 'msg'=>'Invalid file extension', 'file_name'=>$file_name);
        return false;
    }

    return true;
} 


if(isset($_FILES['file']) && $_FILES['file']['size'] > 0){

    $file = $_FILES['file'];

    move_uploaded_file($_FILES['files']['tmp_name'], DOC_PATH.'file_system/'.$_FILES['files']['name']);

    echo json_encode(['file_name'=>$_FILES['files']['name'], 'status'=>'success', 'progress_bar'=>$_POST['file_id']]);

    // $_FILES = [];
}