<?php

require_once '../config/config.php';
require_once '../class/FilePreview.php';

is_logged_in(true);

if(isset($_GET['fd'],$_GET['d']) && !any_empty($_GET['fd'], $_GET['d']))
{
    $check = get_folder($_GET['fd']);

    if(!$check){
        $check = get_file($_GET['fd']);

        if(!$check){
            header('location:'.BASE_URL.'home.php');
            exit;
        }
    }

    $file_path = DOC_PATH.'file_system/user_'.session()->get('user_id').'/';

    if(isset($check->file_extension)){
        $file_path.='FOLDER_ID_'.$check->folder_id.'_'.$check->title;
    }else{
        $file_path.=$check->title;
    }

    $f = new FilePreview($file_path, $check, ORM::class);

    $f->download();

}

header('location:'.BASE_URL.'home.php');
exit;