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

define('ROOT_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);

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

// Текущая страница
$tpl['curPage'] = $_SERVER['REQUEST_URI'];

// Адрес сайта
$tpl['homeUrl'] = $admin->config['home_url'];

if(isset($_REQUEST['action']) and $_REQUEST['action'] == "logout") {
    $auth->logout();
    header('Location: ' . $admin->config['home_url']);
    die();
}
if(isset($_POST['name']) && isset($_POST['password'])) {
    $auth->login($_POST['name'], $_POST['password'], $_POST['remember_me'], false);
}



// Определяем имя подключаемого шаблона страницы
if ($auth->user_logged) {
	// Если авторизован - подключаем нужный шабик
	$templateName = ($adminPage) ? 'pages/' . $clearAdminPage . '.tpl' : 'pages/index.tpl';
	$tpl['logged'] = true;
} else {
	// Если нет - подключаем форму авторизации и переопределяем переменную для switch
	$clearAdminPage = 'auth';
	$templateName = 'pages/auth.tpl';
	$tpl['logged'] = false;
}

// Если вдруг файл шаблона отсутствует - не беда - выведем ошибку.
if (!file_exists(ROOT_DIR . $admin->config['templateFolder'] . '/' . $templateName)) {
	$clearAdminPage = '404';
	$templateName = 'pages/404.tpl';
}
// Передаём имя подключаемого шаблона в шаблоизатор
$tpl['templateName'] = $templateName;

// Передаём путь к шаблону в шаблонизатор для подключения скриптов и стилей
$tpl['templateFolder'] = '/'. $admin->config['templateFolder'];

// в этом массиве будут храниться различные списки в будущем.
$list = array();

// Конфиг постранички
$curPageNum = (int)$_GET['p'];
$pagerConfig = array(
	// 'total_items'    => 150, // определяем кол-во там, где это требуется и если есть - дёргаем сам класс (см ниже)
	'items_per_page' => $admin->config['perPage'],
	'style'          => $admin->config['navStyle'],
	'current_page'   => $curPageNum,
);

// Записываем переменную фильтра
$requestFilter = $_REQUEST['filter'];


// Определяем необходимые данные для вывода в шаблон
switch ($clearAdminPage) {
	case 'methods':
		$allowed = array('id','name','secret_key','check_period','enforce');
		$filter = $admin->db->filterArray($requestFilter, $allowed);

		$getList = $admin->getList('license_methods', $filter, $curPageNum, $admin->config['perPage'], 'ASC');
		$list = $getList['items'];

		$pagerConfig['total_items'] = $getList['count'];
		
		$tpl['title'] = 'Список методов';
		$tpl['h1'] = 'Список методов';	

		break;

	case 'logs':
		$getList = $admin->getList('events_logs', array('name' => 'key_check'), $curPageNum, $admin->config['perPage'], 'ASC', 'date');
		$list = $getList['items'];

		$pagerConfig['total_items'] = $getList['count'];
		
		$tpl['title'] = 'Логи проверки лицензий';
		$tpl['h1'] = 'Логи проверки лицензий';	

		break;

	case 'add':

		$tpl['title'] = 'Создать новую запись';
		$tpl['h1'] = 'Создать новую запись';

		break;

	case 'auth':
		$tpl['title'] = 'Авторизация';
		$tpl['h1'] = 'Авторизация';

		if(isset($_POST['name']) || isset($_POST['password'])) {
        	$tpl['arResult']['error'] = true;
        	$tpl['arResult']['error_text'] = 'Введены неверные данные';
		}

		break;

	case '404':
		$tpl['title'] = 'Страница не найдена';
		$tpl['h1'] = 'Страница не найдена';

		break;
	
	default:

		$allowed = array('id','user_id','user_name','l_expires','l_domain','status','l_method_id', 'l_key');
		$filter = $admin->db->filterArray($requestFilter, $allowed);

		$getList = $admin->getList('license_keys', $filter, $curPageNum, $admin->config['perPage'], 'ASC');
		$list = $getList['items'];

		$pagerConfig['total_items'] = $getList['count'];
		
		$tpl['title'] = 'Список лицензий';
		$tpl['h1'] = 'Список лицензий';		

		break;
}

$tpl['arResult']['list'] = 	$list;



// Сформированный блок с постраничкой
if ($pagerConfig['total_items']) {
	$pagination  = new Pager($pagerConfig);	
	$tpl['arResult']['pages'] = $pagination->render();
}

// Компилим шаблон.
if ($_REQUEST['ajax']) {
	$output = $admin->tpl->fetch('main_ajax.tpl', $tpl);
} else {
	$output = $admin->tpl->fetch('main.tpl', $tpl);
}

// Выводим результат
echo $output;
