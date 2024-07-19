<?php

require_once '../config/config.php';

is_logged_in(false, true);

function successResponse($is_starred)
{
    $msg = $is_starred == 0  ? 'Starred Unmarked' : 'Starred Marked';
    exit(json_encode(['status' => 'success', 'msg' => $msg, 'token' => csrf()->string()]));
}

if (
    isset($_POST['csrf_input'], $_POST['type_of'], $_POST['fid'], $_POST['is_starred']) && !empty($_POST['csrf_input']) &&
    !empty($_POST['csrf_input']) && !empty($_POST['type_of']) && !empty($_POST['fid']) && !empty($_POST['type_of'])

) {

    $type = $_POST['type_of'];
    $fid = $_POST['fid'];
    $is_starred = $_POST['is_starred'];

    if (!in_array($is_starred, [0, 1])) {
        exit(json_encode(['status' => 'error', 'msg' => 'Can not mark the starred , invalid starred type']));
    }

    if (!csrf()->validate()) {
        exit(json_encode(['status' => 'error', 'msg' => 'Invalid request']));
    }

    if ($type == 'folder') {

        $folder = get_folder($fid);

        if (!$folder) {

            exit(json_encode(['status' => 'error', 'msg' => 'Invalid file ID']));
        }

        $folder->is_starred = $is_starred;
        $folder->save();

        successResponse($is_starred);
    } else if ($type == 'file') {

        $file = get_file($fid);

        if (!$folder) {

            exit(json_encode(['status' => 'error', 'msg' => 'Invalid file ID']));
        }

        $file->is_starred = $is_starred;
        $file->save();

        successResponse($is_starred);
    } else {

        exit(json_encode(['status' => 'error', 'msg' => 'Invalid file type']));
    }

    exit(json_encode(['status' => 'error', 'msg' => 'Invalid file ID']));
}

echo json_encode(['status' => 'error', 'msg' => 'Invalid request']);
