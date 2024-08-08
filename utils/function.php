<?php
function assets($resource = '')
{

    return BASE_URL . 'assets/' . $resource;
}

function session()
{

    global $_sessions;

    return $_sessions;
}

function is_logged_in($redirect = false, $ajax = false)
{

    if (!session()->has('user_id')) {

        if ($redirect) {
            header('location:../index.php');
            exit;
        }

        if ($ajax) {
            exit(json_encode(['status' => 'error', 'msg' => 'Please login to conitnue', 'data' => []]));
        }

        return false;
    }

    return true;
}

function any_empty()
{

    $args = func_get_args();

    foreach ($args as $a) {
        if (empty($a)) {
            return true;
        }
    }

    return false;
}

function csrf()
{
    global $_csrf;
    return $_csrf;
}

function uuidv4()
{
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function clean($string)
{

    if (empty($string)) return '';

    return htmlspecialchars(trim($string));
}

function handle_errors(Exception $e)
{

    $line_no = $e->getLine();
    $file_name = $e->getFile();
    $message = $e->getMessage();

    if (APP_STAGE == 'development') {

        echo '<ul class="">';
        echo '<li><span class="text-danger">Line NO : </span> ' . $line_no . '</li>';
        echo '<li><span class="text-danger">File Name : </span> ' . $file_name . '</li>';
        echo '<li><span class="text-danger">Error : </span> ' . $message . '</li>';
        echo '</li>';

        exit();
    }

    $contents = 'Time : ' . date('H:i:s a') . PHP_EOL;
    $contents .= 'Line No : ' . $line_no . PHP_EOL;
    $contents .= 'File Name : ' . $file_name . PHP_EOL;
    $contents .= 'Error : ' . $message . PHP_EOL;

    $file = 'logs_' . date('Y-m-d') . '.txt';
    file_put_contents('../file_system/system/' . $file, $contents . PHP_EOL, FILE_APPEND);
}

function redirect($page_name)
{

    header('location:../pages/' . $page_name . '.php');
    exit;
}


function foldertree($fd, $pagename = ''){
    $check = ORM::for_table('folders')->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->where('uuid', $fd)->find_one();
    $breadcrumbs = '';
    if (!$check) {
        
        return $breadcrumbs .= ' > 404';
    }

    $folder_path = $check->path;

    if ($folder_path == '/' . $check->id) {
        return $breadcrumbs .= ' > ' . $check->title;
    }

    $folders = array_values(array_filter(explode('/', $folder_path)));

    $get_folders = ORM::for_table('folders')->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->where_id_in($folders)->order_by_asc('id')->find_many();

    if ($get_folders->count() > 0) {
        $folder_count = $get_folders->count();
        foreach ($get_folders as $k =>  $f) {

            if ($folder_count == $k + 1) {
                $breadcrumbs .= ' > ' . $f->title;
                break;
            }
            $breadcrumbs .= ' > <a href="' . BASE_URL . 'pages/home.php?page='.$pagename.'&fd=' . $f->uuid . '">' . $f->title . '</a>';
        }
    }

    return $breadcrumbs;
}

function get_page_link($page_name){
    return '<a href="'.BASE_URL.'pages/home.php?page='.$_GET['page'].'">'.$page_name.'</a>';
}

function breadcrumbs()
{

    $fd = '';
    if (isset($_GET['fd']) && !empty($_GET['fd'])) {
        $fd = clean($_GET['fd']);
    }

    $breadcrumbs = 'Welcome to drive';

    if (isset($_GET['page']) && !empty($_GET['page'])) {

        $page_name = file_exists('../pages/' . $_GET['page'] . '.php') ? str_replace('-', ' ', ucwords($_GET['page'])) : '404';

        if($fd){
        return '<a href="' . BASE_URL . 'pages/home.php"> Home </a> > ' . get_page_link($page_name).foldertree($fd, 'my-drive');
        }

        return '<a href="' . BASE_URL . 'pages/home.php"> Home </a> > ' . $page_name;
    } else if ($fd) {

        $breadcrumbs = '<a href="' . BASE_URL . 'pages/home.php">Home</a>';

        $breadcrumbs.= foldertree($fd);
       
    }

    return $breadcrumbs;
}

function getSizeAll($return_string = false)
{

    $all_size = ORM::for_table('folders')->where('user_id', session()->get('user_id'))->where('is_deleted', 0)->sum('size');
    $file_size = ORM::for_table('files')->where('user_id', session()->get('user_id'))->where('is_deleted', 0)->sum('size');

    $all_size += $file_size;

    $kb = $all_size / (1024); //kb
    $mb = $all_size / (1024 * 1024); //mb
    $gb = $all_size / (1024 * 1024 * 1024); //gb

    if (!$return_string) return $gb;

    if ($gb >= 1) {
        return round($gb, 1) . ' GB';
    } else if ($mb >= 1) {
        return round($mb, 1) . ' MB';
    } else if ($kb >= 1) {
        return round($kb, 1) . ' KB';
    }
}

function add_s(int $num, $label)
{

    if ($num > 1) return $label . 's';

    return $label;
}

function timeAgo(int $timestamp)
{
    $current_time = time();
    $time_difference = $current_time - $timestamp;

    $label = '';
    if ($time_difference >= (60 * 60 * 24 * 365)) {
        $time = round($time_difference / (60 * 60 * 24 * 365), 1);
        $label = $time . add_s($time, ' year') . ' ago';
    } else if ($time_difference >= (60 * 60 * 24 * 30)) {
        $time = floor($time_difference / (60 * 60 * 24 * 30));
        $label =  $time . add_s($time, ' month') . ' ago';
    } else if ($time_difference >= (60 * 60 * 24 * 7)) {
        $time = floor($time_difference / (60 * 60 * 24 * 7));
        $label = $time . add_s($time, ' week') . ' ago';
    } else if ($time_difference >= (60 * 60 * 24)) {
        $time = floor($time_difference / (60 * 60 * 24));
        $label = $time . add_s($time, ' day') . ' ago';
    } else if ($time_difference >= (60 * 60)) {
        $time = floor($time_difference / (60 * 60));
        $label = $time . add_s($time, ' hour') . ' ago';
    } else if ($time_difference >= 60) {
        $time = floor($time_difference / 60);
        $label = $time . add_s($time, ' minute') . ' ago';
    } else if ($time_difference > 10) {
        $label = $time_difference . ' seconds ago';
    } else {
        $label = 'Just now';
    }


    return $label;
}

function getIcon($ext = null){

    if(!$ext) return 'bx bxs-folder';

    $file_icons = [
        'pdf' => 'bx bxs-file-pdf',
        'msword' => 'bx bxs-file-doc',
        'xlsx' => 'bx bxs-file-excel',
        'docs' => 'bx bxs-file-doc',
        'zip' => 'bx bx-briefcase-alt-2',
        'jpeg' => 'bx bxs-file-image',
        'png' => 'bx bxs-file-image',
        'gif' => 'bx bxs-file-image',
        'txt' => 'bx bxs-file',
        'css' => 'bx bxs-file-code',
        'html' => 'bx bxs-file-code',
        'js' => 'bx bxs-file-code',
        'sql' => 'bx bxs-coin-stack',
        'php' => 'bx bxl-php bx-tada bx-rotate-90',
    ];

    return $file_icons[$ext]??'bx bx-question-mark';

}
function get_file_icon($mime_type)
{

    $file_icons = [
        'application/pdf' => 'bx bxs-file-pdf',
        'application/msword' => 'bx bxs-file-doc',
        'application/vnd.ms-excel' => 'bx bxs-file-excel',
        'application/vnd.ms-powerpoint' => 'bx bxs-file-ppt',
        'application/zip' => 'bx bxs-file-archive',
        'image/jpeg' => 'bx bxs-file-image',
        'image/png' => 'bx bxs-file-image',
        'image/gif' => 'bx bxs-file-image',
        'text/plain' => 'bx bxs-file-alt',
        'application/json' => 'bx bxs-file-code',
        'application/xml' => 'bx bxs-file-code',
        'text/html' => 'bx bxs-file-code',
        'application/javascript' => 'bx bxs-file-code',
        'text/css' => 'bx bxs-file-code',
        'application/x-php' => 'bx bxs-file-code',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'bx bxs-file-doc',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'bx bxs-file-ppt',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'bx bxs-file-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheetapplication/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'bx bxs-file-excel',
        'audio/mpeg' => 'bx bxs-music',
        'audio/wav' => 'bx bxs-music',
        'video/mp4' => 'bx bxs-play',
        'video/quicktime' => 'bx bxs-play',
        'application/ogg' => 'bx bxs-play',
    ];


    $unknown_icon = 'bx bx-unknown';

    return isset($file_icons[$mime_type]) ? $file_icons[$mime_type] : $unknown_icon;
}

function getSize(int $size)
{

    $kb = $size / (1024); //kb
    $mb = $size / (1024 * 1024); //mb
    $gb = $size / (1024 * 1024 * 1024); //gb

    if ($gb >= 1) {
        return round($gb, 1) . ' GB';
    } else if ($mb >= 1) {
        return round($mb, 1) . ' MB';
    } else if ($kb >= 1) {
        return round($kb, 1) . ' KB';
    }
}

function init_page()
{

    $_404 = '../pages/404.php';

    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $page = $_GET['page'] . '.php';
        if (file_exists('../pages/' . $page)) {
            include '../pages/' . $page;
            return;
        } else {
            include $_404;
            return;
        }
    }

    if (isset($_GET['fd']) && !empty($_GET['fd'])) {
        require_once '../pages/folders.php';
        return;
    }


    require_once '../pages/home-content.php';
}

function get_folder($fid, $check_owner=true)
{
    $obj =  ORM::for_table('folders')->where(array(
        'is_deleted' => 0,
        'uuid' => $fid,
    ));

    if($check_owner){
        $obj->where('user_id', session()->get('user_id'));
    }
    
    return $obj->find_one();
}

function increment_file_count($folder_uuid)
{
    // if ($folder_uuid == 0) return; //nothing to increment

    $get_folder_obj = get_folder($folder_uuid);

    if ($get_folder_obj->parent_id == 0) {
        $get_folder_obj->files += 1;
        $get_folder_obj->save();
        return;
    }

    $all_folders = explode('/', $get_folder_obj->path);

    foreach($all_folders as $v){

        if(empty($v)) continue;

        $folder = ORM::for_table('folders')->where('id', $v)->find_one();

        $folder->files+=1;

        $folder->save();
    }
    
}

function get_file($fid, $check_owner = true)
{
    $obj =  ORM::for_table('files')->where(array(
        'is_deleted' => 0,
        'uuid' => $fid,
    ));

    if($check_owner){
        $obj->where('user_id', session()->get('user_id'));
    }
    
    return $obj->find_one();
}

function sendErrorResponse(array $data){

    exit(json_encode(array_merge(['status'=>'error'], $data)));

}

function sendSuccessResponse(array $data){

    exit(json_encode(array_merge(['status'=>'success'], $data)));

}

function generatelink($id){

    return BASE_URL.'d/'.$id;
}