<?php

require_once '../config/config.php';

if (isset($_POST['folder_name']) && !empty($_POST['folder_name'])) {

    $error = array('open_modal' => 'yes', 'modal_id' => 'create_folder', 'error' => '');

    if (!csrf()->validate()) {
        $error['error'] = 'Invalid request , try again';
        session()->set_flash_message($error);
        header('location:../');
        exit;
    }

    $folder_name = clean($_POST['folder_name']);
    $folder_location = clean($_POST['__P_F__']);

    $folder_path = '';

    //checking if folder created inside another folder
    if ($folder_location) {

        $check_folder = ORM::for_table('folders')->where('uuid', $folder_location)->where('user_id', session()->get('user_id'))->where('is_deleted', 0)->find_one();

        if (!$check_folder) {
            $error['error'] = 'The parent folder does not exists';
            session()->set_flash_message($error);
            header('location:../pages/home.php');
            exit;
        }

        $folder_path = $check_folder->path;
    }

    //checking if user cross the nested folder limit

    $depth_count = array_filter(explode('/', $folder_path));

    if (count($depth_count) == getenv('MAX_FOLDER_DEPTH')) {
        $error['error'] = 'You can create only ' . getenv('MAX_FOLDER_DEPTH') . ' nested folder';
        session()->set_flash_message($error);
        header('location:../pages/home.php');
        exit;
    }

    //checking if folder exists with same name and same location

    $check = ORM::for_table('folders')->where('path', $folder_path)->where('title', $folder_name)->where('user_id', session()->get('user_id'))->where('is_deleted', 0)->find_one();

    if ($check) {
        $error['error'] = 'The folder name already exists';
        session()->set_flash_message($error);
        header('location:../pages/home.php');
        exit;
    }


    $create = ORM::for_table('folders')->create(array(
        'uuid' => uuidv4(),
        'path' => $folder_path,
        'title' => $folder_name,
        'user_id' => session()->get('user_id'),
        'date_added' => time(),
        'last_updated' => time()
    ));

    try {

        $create->save();
        $create->path = $create->path . '/' . $create->id;
        $create->save();

        header('location:../pages/home.php?fd=' . $create->uuid);
        exit;
    } catch (Exception $e) {
        $error['error'] = 'There are some error occured during folder creation';
        session()->set_flash_message($error);
        handle_errors($e);
    }

    header('location:../pages/home.php');
    exit;
}
