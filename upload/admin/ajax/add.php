<?
define('ROOT_DIR', substr(dirname(__FILE__), 0, -11) . DIRECTORY_SEPARATOR);

define('API_DIR', ROOT_DIR . '/api');


$time_start = microtime(true);

@error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);

session_start();

// Подрубаем ядро админки
require_once (ROOT_DIR . 'admin/core/core.php');

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
	// Если нет - подключаем форму авторизации и переопределяем переменную для switch
	// $clearAdminPage = 'auth';
	// $templateName = 'pages/auth.tpl';
	// $tpl['logged'] = false;
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
$tpl['templateFolder'] = '/'. $admin->config['templateFolder'];

// Определяем необходимые данные для вывода в шаблон
switch ($clearAdminPage) {
	case 'addmethod':
		// $getList = $admin->getList('license_methods', $curPageNum, $admin->config['perPage'], 'ASC');
		
		$tpl['title'] = 'Добавить новый метод';
		$tpl['add'] = false;
		$tpl['addResult'] = false;

		if ($_REQUEST['add'] == 'y') {
			include_once(API_DIR. '/core/server.class.php');
			include_once(API_DIR. '/core/mysqli.class.php');
			include_once(API_DIR. '/config.php');
			
			$server = new Mofsy\License\Server\Core\Protect($config);

			$name = (isset($_REQUEST['name'])) ? trim($_REQUEST['name']) : false;
			$secret_key = (isset($_REQUEST['secret_key'])) ? trim($_REQUEST['secret_key']) : false;
			$check_period = (isset($_REQUEST['check_period'])) ? (int)$_REQUEST['check_period'] : 0;
			$enforce = (isset($_REQUEST['enforce'])) ? implode(',', $_REQUEST['enforce']) : false;

			$newmethodCreate = $server->licenseKeyMethodCreate($name, $secret_key, $check_period, $enforce);

			if ($newmethodCreate) {
				$tpl['title'] = 'Готово!';
				$tpl['add'] = true;
				$tpl['addResult'] = $newmethodCreate;
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
