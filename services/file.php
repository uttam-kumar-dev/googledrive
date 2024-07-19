<?php

require_once '../config/config.php';

is_logged_in(false, true);

/**
 * This file is used to upload files , validate files,
 */

if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

    $file = $_FILES['file'];

    $all_size = getSizeAll() + $file['size'];

    if ($all_size > session()->get('storage')) {

        exit(json_encode(['file_name' => $file['name'], 'status' => 'error', 'msg' => 'Not Enough Space !!!',  'progress_bar' => $_POST['file_id']]));
    }

    $dir = 'user_'.session()->get('user_id');

    if(!is_dir('../file_system/'.$dir)){
        mkdir('../file_system/'.$dir);
    }



    $folder = $_POST['path'];
    $folder_id = 0;

    if (!empty($folder)) {

        $check_if_folder_exists = ORM::for_table('folders')->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->where('uuid', $folder)->find_one();

        if (!$check_if_folder_exists) {

            exit(json_encode(['file_name' => $file['name'], 'status' => 'error', 'msg' => 'Parent Directory does not exists',  'progress_bar' => $_POST['file_id']]));
        }

        $folder_id = $check_if_folder_exists->id;
    }

    $new_temp_file_name = 'FOLDER_ID_'.$folder_id.'_'.$file['name'];

    $file_path = $dir . '/' . $new_temp_file_name;

    if (file_exists('../file_system/' . $file_path)) {
        exit(json_encode(['file_name' => $file['name'], 'status' => 'error', 'msg' => 'File already exists',  'progress_bar' => $_POST['file_id']]));
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);


    $extension = pathinfo($file['name'], PATHINFO_EXTENSION); // Get the extension

    $insert = array(
        'uuid' => uuidv4(),
        'title' => $file['name'],
        'file_type' => $mime_type,
        'file_extension' => $extension,
        'user_id' => session()->get('user_id'),
        'folder_id' => $folder_id,
        'size' => $file['size'],
        'date_added' => time(),
        'last_updated' => time()
    );

    if (move_uploaded_file($file['tmp_name'], DOC_PATH . 'file_system/' . $file_path)) {

        $insert = ORM::for_table('files')->create($insert);

        try {

            $insert->save();

            if(isset($check_if_folder_exists)){
                increment_file_count($check_if_folder_exists->uuid);
            }

            exit(json_encode(['file_name' => $file['name'], 'status' => 'success', 'msg' => 'File uploaded successfully',  'progress_bar' => $_POST['file_id']]));
        } catch (Exception $e) {

            handle_errors($e);

            exit(json_encode(['file_name' => $file['name'], 'status' => 'error', 'msg' => 'Some unknown error occured !!',  'progress_bar' => $_POST['file_id']]));
        }
    }

    exit(json_encode(['file_name' => $file['name'], 'status' => 'error', 'msg' => 'File not uploaded . Some unknown error occured !!',  'progress_bar' => $_POST['file_id']]));


}
