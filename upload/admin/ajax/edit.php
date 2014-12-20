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

$time_start = microtime(true);

@error_reporting(E_ALL^E_WARNING^E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL^E_WARNING^E_NOTICE);

session_start();

// Подрубаем ядро админки
require_once ROOT_DIR . 'admin/core/core.php';

// Вызываем ядро админки
$admin = new adminCore();

// Вызываем класс авторизации
$auth = new Auth();

// То, что будет выведено на страницу
$output = false;

// Запишем в переменную для удобства использования
$adminPage = $_GET['page'];

// Это для передачи в switch, ведь на конце слеша может и не быть, а может и быть, как повезёт ))
$clearAdminPage = trim($adminPage, '/');

// В массив arResult складываем всё, что должно выводиться в контенте подключаемого шаблона
$tpl['arResult'] = array();

// IP пользователя
$tpl['arResult']['userIp'] = $auth->user_ip;

$tpl['curPage'] = $_SERVER['REQUEST_URI'];

// Определяем имя подключаемого шаблона страницы
if ($auth->user_logged) {
	// Если авторизован - подключаем нужный шабик
	$templateName = ($adminPage) ? 'pages/ajax/' . $clearAdminPage . '.tpl' : false;
	$tpl['logged'] = true;
} else {
	die('Error!');
}

// Если вдруг файл шаблона отсутствует - не беда - выведем ошибку.
if (!file_exists(ROOT_DIR . $admin->config['templateFolder'] . '/' . $templateName)) {
	$clearAdminPage = '404';
	$templateName = 'pages/ajax/404.tpl';
}
// Передаём имя подключаемого шаблона в шаблоизатор
$tpl['templateName'] = $templateName;

// Передаём путь к шаблону в шаблонизатор для подключения скриптов и стилей
$tpl['templateFolder'] = '/' . $admin->config['templateFolder'];

// Определяем необходимые данные для вывода в шаблон
switch ($clearAdminPage) {
	case 'editmethod':
		$tpl['title'] = 'Редактировать метод';
		$tpl['edit'] = false;
		$tpl['arResult'] = false;

		$methodId = $_REQUEST['id'];
		if ($methodId > 0) {
			$tpl['arResult'] = $admin->getElementById($methodId);

			if ($_REQUEST['edit'] == 'y') {

				$_REQUEST['enforce'] = (isset($_REQUEST['enforce'])) ? implode(',', $_REQUEST['enforce']) : false;

				$allowed = array('name','secret_key','check_period','enforce');
				$data  = $admin->db->filterArray($_REQUEST,$allowed);	

				$methodEdited = $admin->db->query("UPDATE ?n SET ?u WHERE id=?i", $admin->db_prefix . '_license_methods', $data, $methodId);

				if ($methodEdited) {
					$tpl['title'] = 'Готово!';
					$tpl['edit'] = true;
				}
			}
		}

		break;
}

// Компилим шаблон.
if ($_REQUEST['ajax']) {
	$output = $admin->tpl->fetch('main_ajax.tpl', $tpl);
} else {
	$output = $admin->tpl->fetch('main.tpl', $tpl);
}

// Выводим результат
echo $output;
