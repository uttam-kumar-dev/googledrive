<?php

require_once '../config/config.php';

is_logged_in(false, true);

/**
 * This file is used to manage the share files ,
*/

if (isset($_POST['fid'],$_POST['type_of']) && !any_empty($_POST['fid'],$_POST['type_of'])) {

    $fid = $_POST['fid'];
    $type = $_POST['type_of'];

    if(!in_array($type, ['file', 'folder'])){
        sendErrorResponse(['msg' => 'Invalid file type']);
    }

    if($type == 'file'){
        $f = get_file($fid);
    }else{
        $f = get_folder($fid);
    }

    $check_share = ORM::for_table('file_sharing_access')->where('user_id', session()->get('user_id'))->where('file_id', $f->uuid)->where_not_equal('share_with',0)->find_many();

    $check_anyone = ORM::for_table('file_sharing_access')->where('user_id', session()->get('user_id'))->where('file_id', $f->uuid)->where('share_with',0)->find_many()->count();

    $file_access = '<form method="post" id="file_upload_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <h5 class="modal-title mb-3" id="modal_files_title">Share Files</h5>

                    <input type="text" name="share_with" class="form-control" placeholder="Enter comma separated emails"
                        style="height:50px;">

                    <p class="fw-bold my-3">People with access</p>

                    <table class="table align-middle table-nowrap table-hover mb-0">
                        <tbody>
                            <tr>
                                <td style="width: 50px;">
                                    <div class="user_first_letter d-flex justify-content-center align-items-center"
                                        style="background-color: blue; color: white;">'.strtoupper(session()->get('name')[0]).'</div>
                                </td>

                                <td class="w-100">
                                    <p class="mb-0">'.session()->get('name').'</p>
                                    <span style="font-size:14px;">'.session()->get('email').'</span>
                                </td>

                                <td>
                                    <span class="disabled-text">Owner</span>
                                </td>
                            </tr>';
    

    if($check_share->count() > 0){
        foreach($check_share as $file){
            $user = ORM::for_table('users')->where('id', $file->share_with)->find_one();
            $file_access.='<tr data-shareid="'.$file->id.'">
                                <td style="width: 50px;">
                                    <div class="user_first_letter d-flex justify-content-center align-items-center"
                                        style="background-color: blue; color: white;">'.strtoupper($user->name[0]).'</div>
                                </td>

                                <td class="w-100">
                                    <p class="mb-0">'.$user->name.'</p>
                                    <span style="font-size:14px;">'.$user->email.'</span>
                                </td>

                                <td>
                                    <span class="disabled-text link-primary remove_file_access" role="button">Remove</span>
                                </td>
                            </tr>';
        }
    }


    $file_access.= '</tbody>
                    </table>

                    <p class="fw-bold mt-4">General access</p>

                    <div class="d-flex align-items-center">
                        <div class="user_first_letter d-flex justify-content-center align-items-center me-2"
                            style="background: navajowhite;"><i class=\'bx bx-lock\'></i></div>
                        <div>
                            <select class="form-control border-0 shadow-none" id="file_access_dropdown">
                                <option value="restricted" '.($check_anyone == 0 ? 'selected' : '').'>Restricted </option>
                                <option value="anyone" '.($check_anyone > 0 ? 'selected' : '').'>Anyone with this link</option>
                            </select>
                            <span id="restricted" class="file_access_helper_text" style="font-size: 11px;display: '.($check_anyone > 0 ? 'none' : 'block').';margin-top: -.4rem;margin-left: .7rem;" class="mt-n1">Only people with access can open with the link</span>
                            <span id="anyone" class="file_access_helper_text" style="font-size: 11px;display: '.($check_anyone > 0 ? 'block' : 'none').';margin-top: -.4rem;margin-left: .7rem;" class="mt-n1">Anyone on the internet with the link can view</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" data-bs-dismiss="modal" onclick="copylink(\''.generatelink($fid).'\')" class="btn btn-outline-primary rounded-5"> <i class=\'bx bx-link-alt\'></i> Copy link</button>
                    <button type="submit" id="share_file_btn" data-bs-dismiss="modal" class="btn btn-primary rounded-5">Share</button>
                </div>
            </form>';

    
    sendSuccessResponse(['msg' => $file_access]);

}

if(isset($_POST['share_id'], $_POST['action']) && !empty($_POST['share_id']) && $_POST['action'] == 'remove'){
    $share_id = $_POST['share_id'];
    $get = ORM::for_table('file_sharing_access')->where('id',$share_id)->where('user_id', session()->get('user_id'))->find_one();

    if($get){
        $get->delete();
    }

    sendSuccessResponse(['msg' => 'Access remove successfully']);
}