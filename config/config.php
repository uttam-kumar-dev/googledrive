<?php
session_start();
function loadEnv($file)
{
    if (!file_exists($file)) {
        throw new Exception('.env file not found');
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

loadEnv(__DIR__ . '/../.env');

date_default_timezone_set(getenv('TIMEZONE'));

if(getenv('MODE') == 'development'){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(E_ALL);
}


define('BASE_URL', getenv('BASE_URL'));
define('DOC_PATH', getenv('DOC_PATH'));
define('APP_STAGE', getenv('MODE'));


require_once DOC_PATH . 'config/orm.php';

ORM::configure('mysql:host=localhost;dbname=' . getenv('DB_NAME'));
ORM::configure('username', getenv('DB_USER'));
ORM::configure('password', getenv('DB_PASS'));
ORM::configure('return_result_sets', true);

if (getenv('MODE') == 'development') {
    ORM::configure('logging', true);
}

require_once DOC_PATH . 'class/SessionManager.php';
require_once DOC_PATH . 'class/CSRF.php';

$_sessions = new SessionManager();
$_csrf = new CSRF();

require_once DOC_PATH . 'utils/function.php';
