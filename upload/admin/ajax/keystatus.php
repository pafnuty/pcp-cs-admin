<?
/*
=============================================================================
Независимая админка для php-cs
=============================================================================
Автор:   Павел Белоусов 
URL:     https://github.com/pafnuty/pcp-cs-admin
email:   pafnuty10@gmail.com
=============================================================================
*/ 

define('ROOT_DIR', substr(dirname(__FILE__), 0, -11) . DIRECTORY_SEPARATOR);

define('API_DIR', ROOT_DIR . '/api');

@error_reporting(E_ALL^E_WARNING^E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL^E_WARNING^E_NOTICE);

session_start();

// Подрубаем ядро админки и всё необходимое
require_once ROOT_DIR . 'admin/core/core.php';
include_once API_DIR . '/core/server.class.php';
include_once API_DIR . '/core/mysqli.class.php';
include_once API_DIR . '/config.php';

// Вызываем класс авторизации
$auth = new Auth();

// То, что будет выведено на страницу
$output = 'false';

$status = (int) $_REQUEST['status'];
$license_key = $_REQUEST['key'];
if ($auth->user_logged) {

	$server = new Mofsy\License\Server\Core\Protect($config);

	$changeStatus = $server->licenseKeyStatusUpdateByKey($license_key, $status);
	if ($changeStatus) {
		$output = 'ok';
	}
} else {
	die('Error');
}

// Выводим результат
echo $output;
