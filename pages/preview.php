<?php

require_once '../class/FilePreview.php';

$file = $_GET['file'];

$get_file = get_file($file);

if($get_file){
    $obj = new FilePreview($get_file,$get_file,ORM::class);
    if($obj->canPreview()){

    $file = 'FOLDER_ID_'.$get_file->folder_id.'_'.$get_file->title;

    $path = DOC_PATH.'file_system/user_'.session()->get('user_id').'/'.$file;

    if($obj->isImageFile()){

        echo '<img class="img-fluid" src="data:image/jpeg;base64,'.base64_encode(file_get_contents($path)).'">';


    }else{
        echo '<textarea class="form-control" rows="15">'.file_get_contents($path).'</textarea>';
    }



    }
    
}else{
    require_once '404.php';
}

