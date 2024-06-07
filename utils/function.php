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


function breadcrumbs()
{

    $fd = '';
    if (isset($_GET['fd']) && !empty($_GET['fd'])) {
        $fd = clean($_GET['fd']);
    }

    $breadcrumbs = 'Welcome to drive';

    if (isset($_GET['page']) && !empty($_GET['page'])) {

        $page_name = file_exists('../pages/'.$_GET['page'].'.php') ? ucwords($_GET['page']) : '404';

        return '<a href="' . BASE_URL . 'pages/home.php"> Home </a> > ' . $page_name;

    } else if ($fd) {

        $check = ORM::for_table('folders')->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->where('uuid', $fd)->find_one();
        if (!$check) {
            $breadcrumbs = '<a href="' . BASE_URL . 'pages/home.php">Home</a>';
            return $breadcrumbs .= ' > 404';
        }

        $folder_path = $check->path;

        if ($folder_path == '/' . $check->id) {
            $breadcrumbs = '<a href="' . BASE_URL . 'pages/home.php">Home</a>';
            return $breadcrumbs .= ' > ' . $check->title;
        }

        $folders = array_values(array_filter(explode('/', $folder_path)));

        $get_folders = ORM::for_table('folders')->where('is_deleted', 0)->where('user_id', session()->get('user_id'))->where_id_in($folders)->order_by_asc('id')->find_many();

        if ($get_folders->count() > 0) {
            $breadcrumbs = '<a href="' . BASE_URL . 'pages/home.php">Home</a>';
            $folder_count = $get_folders->count();
            foreach ($get_folders as $k =>  $f) {

                if ($folder_count == $k + 1) {
                    $breadcrumbs .= ' > ' . $f->title;
                    break;
                }
                $breadcrumbs .= ' > <a href="' . BASE_URL . 'pages/home.php?fd=' . $f->uuid . '">' . $f->title . '</a>';
            }
        }

        return $breadcrumbs;
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

function timeAgo(int $timestamp)
{
    $current_time = time();
    $time_difference = $current_time - $timestamp;

    $label = '';
    if ($time_difference >= (60 * 60 * 24 * 365)) {
        $label = round($time_difference / (60 * 60 * 24 * 365), 1) . ' years ago';
    } else if ($time_difference >= (60 * 60 * 24 * 30)) {
        $label = floor($time_difference / (60 * 60 * 24 * 30)) . ' month ago';
    } else if ($time_difference >= (60 * 60 * 24 * 7)) {
        $label = floor($time_difference / (60 * 60 * 24 * 7)) . ' week ago';
    } else if ($time_difference >= (60 * 60 * 24)) {
        $label = floor($time_difference / (60 * 60 * 24)) . ' day ago';
    } else if ($time_difference >= (60 * 60)) {
        $label = floor($time_difference / (60 * 60)) . ' hour ago';
    } else if ($time_difference >= 60) {
        $label = floor($time_difference / 60) . ' minute ago';
    } else if ($time_difference > 10) {
        $label = $time_difference . ' seconds ago';
    } else {
        $label = 'Just now';
    }


    return $label;
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
