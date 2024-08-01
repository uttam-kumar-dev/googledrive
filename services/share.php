<?php

require_once '../config/config.php';

is_logged_in(false, true);

/**
 * This file is used to manage the share files ,
 */

if (isset($_POST['fid'], $_POST['type_of']) && !any_empty($_POST['fid'], $_POST['type_of'])) {

    $fid = $_POST['fid'];
    $type = $_POST['type_of'];

    if (!in_array($type, ['file', 'folder'])) {
        sendErrorResponse(['msg' => 'Invalid file type']);
    }

    if ($type == 'file') {
        $f = get_file($fid);
    } else {
        $f = get_folder($fid);
    }

    $check_share = ORM::for_table('file_sharing_access')->where('user_id', session()->get('user_id'))->where('file_id', $f->uuid)->where_not_equal('share_with', 0)->find_many();

    $check_anyone = ORM::for_table('file_sharing_access')->where('user_id', session()->get('user_id'))->where('file_id', $f->uuid)->where('share_with', 0)->find_many()->count();

    $file_access = '<form method="post" id="file_share_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <h5 class="modal-title mb-3" id="modal_files_title">Share Files</h5>

                    <input type="text" name="share_list" class="form-control" placeholder="Enter comma separated emails"
                        style="height:50px;">
                    <input type="hidden" name="fid" value="'.$fid.'" />
                    <p class="fw-bold my-3">People with access</p>

                    <table class="table align-middle table-nowrap table-hover mb-0">
                        <tbody>
                            <tr>
                                <td style="width: 50px;">
                                    <div class="user_first_letter d-flex justify-content-center align-items-center"
                                        style="background-color: blue; color: white;">' . strtoupper(session()->get('name')[0]) . '</div>
                                </td>

                                <td class="w-100">
                                    <p class="mb-0">' . session()->get('name') . '</p>
                                    <span style="font-size:14px;">' . session()->get('email') . '</span>
                                </td>

                                <td>
                                    <span class="disabled-text">Owner</span>
                                </td>
                            </tr>';


    if ($check_share->count() > 0) {
        foreach ($check_share as $file) {
            $user = ORM::for_table('users')->where('id', $file->share_with)->find_one();
            $file_access .= '<tr data-shareid="' . $file->id . '">
                                <td style="width: 50px;">
                                    <div class="user_first_letter d-flex justify-content-center align-items-center"
                                        style="background-color: blue; color: white;">' . strtoupper($user->name[0]) . '</div>
                                </td>

                                <td class="w-100">
                                    <p class="mb-0">' . $user->name . '</p>
                                    <span style="font-size:14px;">' . $user->email . '</span>
                                </td>

                                <td>
                                    <span class="disabled-text link-primary remove_file_access" role="button">Remove</span>
                                </td>
                            </tr>';
        }
    }


    $file_access .= '</tbody>
                    </table>

                    <p class="fw-bold mt-4">General access</p>

                    <div class="d-flex align-items-center">
                        <div class="user_first_letter d-flex justify-content-center align-items-center me-2"
                            style="background: navajowhite;"><i class=\'bx bx-lock\'></i></div>
                        <div>
                            <select class="form-control border-0 shadow-none" name="file_access_level" id="file_access_dropdown">
                                <option value="restricted" ' . ($check_anyone == 0 ? 'selected' : '') . '>Restricted </option>
                                <option value="anyone" ' . ($check_anyone > 0 ? 'selected' : '') . '>Anyone with this link</option>
                            </select>
                            <span id="restricted" class="file_access_helper_text" style="font-size: 11px;display: ' . ($check_anyone > 0 ? 'none' : 'block') . ';margin-top: -.4rem;margin-left: .7rem;" class="mt-n1">Only people with access can open with the link</span>
                            <span id="anyone" class="file_access_helper_text" style="font-size: 11px;display: ' . ($check_anyone > 0 ? 'block' : 'none') . ';margin-top: -.4rem;margin-left: .7rem;" class="mt-n1">Anyone on the internet with the link can view</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" data-bs-dismiss="modal" onclick="copylink(\'' . generatelink($fid) . '\')" class="btn btn-outline-primary rounded-5"> <i class=\'bx bx-link-alt\'></i> Copy link</button>
                    <button type="submit" id="share_file_btn" data-bs-dismiss="modal" class="btn btn-primary rounded-5">Share</button>
                </div>
            </form>';


    sendSuccessResponse(['msg' => $file_access]);
}

if (isset($_POST['share_id'], $_POST['action']) && !empty($_POST['share_id']) && $_POST['action'] == 'remove') {
    $share_id = $_POST['share_id'];
    $get = ORM::for_table('file_sharing_access')->where('id', $share_id)->where('user_id', session()->get('user_id'))->find_one();

    if ($get) {
        $get->delete();
    }

    sendSuccessResponse(['msg' => 'Access remove successfully']);
}

if (isset($_POST['share_list'], $_POST['fid'], $_POST['file_access_level']) && !empty($_POST['fid'])) {

    if (!in_array($_POST['file_access_level'], ['anyone', 'restricted'])) {
        sendErrorResponse(['msg' => 'Invalid file access level']);
    }

    $share_list =  $_POST['share_list'];
    $file_level = $_POST['file_access_level'];
    $fid = $_POST['fid'];

    $share_list = array_map('trim',explode(',', $share_list));

    $type = get_file($fid) ? 1 : 0;
    if (!$type) {
        $type = get_folder($fid);

        if (!$type) {
            sendErrorResponse(['msg' => 'Invalid file or folder']);
        }

        $type = 0;
    }


    foreach ($share_list as $email) {

        if(session()->get('email') == $email) continue;

        $check_user = ORM::for_table('users')->where('email', $email)->find_one();
        if (!$check_user) continue; //for that time only in future we work on this

        $c = ORM::for_table('file_sharing_access')->create(
            array(
                'user_id' => session()->get('user_id'),
                'share_with' => $check_user->id,
                'file_id' => $fid,
                'file_type' => $type,
                'date_added' => time()
            )
        );

        $c->save();
    }

    if($file_level == 'restricted'){

        foreach(
                ORM::for_table('file_sharing_access')->where('user_id', session()->get('user_id'))->where('file_id',$fid)->where('share_with',0)->find_many()
                as
                $item
            )
            {

                $item->delete();

            }

    }

    if ($file_level == 'anyone') {
        $c = ORM::for_table('file_sharing_access')->create(
            array(
                'user_id' => session()->get('user_id'),
                'share_with' => 0,
                'file_id' => $fid,
                'file_type' => $type,
                'date_added' => time()
            )
        );

        $c->save();
    }

    sendSuccessResponse(['msg' => 'File shared successfully']);
}
