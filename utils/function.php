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

    $breadcrumbs = '';

    if ($fd) {

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
                    $breadcrumbs .= ' > '.$f->title;
                    break;
                }
                $breadcrumbs .= ' > <a href="' . BASE_URL . 'pages/home.php?fd=' . $f->uuid . '">' . $f->title . '</a>';
            }
        }

        return $breadcrumbs;
    }

    return $breadcrumbs;
}
