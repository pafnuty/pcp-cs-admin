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

if (!defined('ROOT_DIR')) {
	exit('Access denied');
}

/**
 * Основной класс админки
 */

require_once ROOT_DIR . 'admin/core/classes/Fenom.php';
\Fenom::registerAutoload(ROOT_DIR . 'admin/core/classes/');

class adminCore {

	public $db_config = array();
	public $config = array();
	public $tpl;
	public $db;
	const ROOT_DIR = ROOT_DIR;

	function __construct() {
		// Цепляем конфиг БД
		$this->db_config = $this->getConfig('db_config');

		// Цепляем конфиг админки
		$this->config = $this->getConfig();

		// Устанавливаем префиксы таблиц БД (для удобства дальнейшего использования)
		$this->db_prefix = $this->db_config['dbprefix'];
		$this->user_prefix = $this->db_config['userprefix'];

		// Определяем путь к шаблонам
		$tplPath = $this->config['templateFolder'];
		// Определяем путь к папке с кешем
		$cachePath = $this->config['cacheFolder'];

		// Шаблонизатор
		$this->tpl = Fenom::factory(
			ROOT_DIR . $tplPath,
			ROOT_DIR . $cachePath

		);
		// Опции шаблонизатора
		$this->tpl->setOptions($this->config['tplOptions']);

		// Добавляем в шаблонизатор функцию, которая будет отмечать текущий пункт в меню
		// Пример:
		// <a href="/?page=methods" class="{selected get="methods"}">Методы</a>
		// <a href="/?page=methods" class="{selected request="page" value="methods"}">Методы</a>

		$this->tpl->addFunction("selected", function ($params) {

			if (strpos($_GET['page'], $params['get']) !== false) {
				return 'selected';
			}
			if (isset($params['request']) && $_REQUEST[$params['request']] == $params['value']) {
				return 'selected';
			}

			return false;

		});

		// Компиляция LESS файлов
		// Используем
		// {set $lessFile}{$templateFolder}/less/style.less{/set}
		// <link rel="stylesheet" href="{less_compile inputFile=$lessFile}">
		$this->tpl->addFunction("less_compile", function ($params) {

			// Файл template_styles.less, лежащий в текущем шаблоне сайта
			$inputFile    = $_SERVER['DOCUMENT_ROOT'] . $params['inputFile']; 

			// Файл .css - который подключается к шаблону
			$outputFile   = (isset($params['outputFile'])) ? $_SERVER['DOCUMENT_ROOT'] . $params['outputFile'] : str_ireplace('less', 'css', $inputFile); 

			// true для отключения сжатия выходящего файла.
			$nocompress       = (isset($params['nocompress'])) ? $params['nocompress'] : false; 

			// false для показа ошибок компиляции вверху страницы (по умолчанию показываются js-алертом);
			$alertError	  = (isset($params['alertError'])) ? $params['alertError'] : false; 
				
			// Выполняем функцию компиляции
			try {
				$cacheFile = $inputFile.".cache";

				if (file_exists($cacheFile)) {
					$cache = unserialize(file_get_contents($cacheFile));
				} else {
					$cache = $inputFile;
				}

				// Подключаем класс для компиляции less 
				// require "lessphp.class.php";
				$less = new lessc;
				if ($nocompress) {
					// Если запрещено сжатие - форматируем по нормальному с табами вместо пробелов.
					$formatter = new lessc_formatter_classic;
			        $formatter->indentChar = "\t";
			        $less->setFormatter($formatter);
				} else {
					// Иначе сжимаем всё в одну строку.
					$less->setFormatter('compressed');
				}
				// Массив с данными разультата компиляции
				$newCache = $less->cachedCompile($cache);

				if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
					file_put_contents($cacheFile, serialize($newCache));
					file_put_contents($outputFile, $newCache['compiled']);
				}
			} catch (exception $e) {
				// Если что-то пошло не так - скажем об этом пользователю способом, указанным в настройках и запишем в лог.
				$logError = str_replace($_SERVER['DOCUMENT_ROOT'], '', $e->getMessage());
				$showError = ($alertError) ? '<script>alert("Less error: '.str_replace('"', ' ', $logError).'")</script>' : '<div style="text-align: center; background: #fff; color: red; padding: 5px;">Less error: '.$logError.'</div>';

				echo $showError;

			}
			return str_replace($_SERVER['DOCUMENT_ROOT'], '', $outputFile);

		});

		// Добавляем модификатор для получения информации о пользователе по его ID
		$this->tpl->addModifier(
			'getUserInfo', function ($id = 0) {
				$_userInfo = new tplModifier();
				$userInfo = $_userInfo->getUserInfo($id);
				return $userInfo;
			}
		);

		// БД
		$this->db = $this->getDb();
	}

	/**
	 * Получение конфига
	 * @param  string $config Имя файла с конфигом
	 */
	public function getConfig($config = 'config') {
		return include ROOT_DIR . '/admin/config/' . $config . '.php';
	}

	/**
	 * Вызов класса для работы с БД
	 * @return object
	 */
	public function getDb() {
		return new SafeMySQL(array(
			'dbhost' => $this->db_config['dbhost'],
			'user' => $this->db_config['dbuser'],
			'pass' => $this->db_config['dbpass'],
			'db' => $this->db_config['dbname'],
			'charset' => $this->db_config['dbcharset'],
		));
	}

	/**
	 * Метод для получения списка элементов
	 * @param  string  $name        Имя таблицы, из которой будем отбирать данные
	 * @param  array   $filter      Поля для фильтрации (поле => условие выборки)
	 * @param  integer $pageNum     Номер страницы
	 * @param  integer $perPage     Кол-во элементов, выводимых на страницу
	 * @param  string  $order       Направление сортировки
	 * @param  string  $orderField поле, по которому будем сортировать
	 * @param  array   $search      Поля для поиска и текст, который нужно искать: array('fields'=>array('field1','field2'), 'text'=>'искомый текст')
	 *
	 * @todo  доработать сорировку, пока сортировать по разным полям нельзя :()
	 *
	 * @return array                Массив с результатами и количеством элеметнов в таблице
	 */
	public function getList($name = 'license_keys', $filter = array(), $pageNum = 0, $perPage = 10, $order = 'ASC', $orderField = 'id', $search = array()) {
		// Имя таблицы в БД
		$name = $this->db_prefix . '_' . $name;

		// С какой записи начинаем
		$start = ($pageNum > 0) ? $perPage * $pageNum - $perPage : 0;

		// Обрабатываем фильтр отбора
		$where = $this->getFilteredWheres($filter);

		// Если был произведён поиск
		if (isset($search['text'])) {
			// Обрабатываем фразу
			$searchText = $this->db->parse('?s', '%' . $search['text'] . '%');
			$arSearchInsert = array();

			// Подготавливаем поля для передачи в запрос
			foreach ($search['fields'] as $field) {
				$arSearchInsert[] = $this->db->parse('?n', $field) . ' LIKE ' . $searchText;
			}

			// В зависимости от наличия фильтра подставим нужный текст в запрос
			$isFilterCondition = (count($filter) > 0) ? ' AND ' : ' WHERE ';
			// Добавим условие запроса
			$where .= $isFilterCondition . implode(' OR ', $arSearchInsert);
		}

		// Если указана сортировка
		if ($orderField) {
			$where .= ' ORDER BY ' . $orderField . ' ' . $order;
		}
		// Формируем маску запроса
		$select = "SELECT * FROM ?n ?p LIMIT ?i, ?i";

		// Выполняем запрос на получение элементов
		$arList['items'] = $this->db->getAll($select, $name, $where, $start, $perPage);
		// Выполняем запрос на получения счётчика всех элементов
		$arList['count'] = $this->db->getOne('SELECT COUNT(*) as count FROM ?n ?p', $name, $where);

		// Возвращаем массив с данными
		return $arList;
	}

	/**
	 * Получение массива всех полей из нужной таблицы
	 *
	 * @param  string $name        имя таблицы (без префикса)
	 * @param  string $fields      поля таблицы
	 * @param  string $order       направления сортировки
	 * @param  string $orderField по какому полю сортировать
	 * @return array               массив с элементами
	 */
	public function getAll($name = 'license_methods', $fields = '*', $order = 'ASC', $orderField = 'id') {

		$name = $this->db_prefix . '_' . $name;
		$select = "SELECT ?p FROM ?n ORDER BY ?s ?p";

		$arAll = $this->db->getAll($select, $fields, $name, $orderField, $order);

		return $arAll;
	}

	public function getElementById($id = 0, $table = 'license_methods', $fields = '*') {
		$table = $this->db_prefix . '_' . $table;
		$select = "SELECT ?p FROM ?n WHERE id = ?i";
		
		$element = $this->db->getRow($select, $fields, $table, $id);

		return $element;
	}

	/**
	 * Создание условий фильтрации в запросе
	 * @param  array  $filter массив вида ключ => значение
	 * @return string         строка для подстановки в запрос
	 */
	public function getFilteredWheres($filter = array()) {
		$wheres = array();
		$where = '';

		if ($filter && count($filter)) {
			foreach ($filter as $key => $value) {
				$wheres[] = $key . ' = \''.$value.'\'';
			}
			$where = ' WHERE ' . implode(' AND ', $wheres);
		
			return $where;
		}

		return '';
	}

	/**
	 * Добавление события в лог
	 * @param string $event_name Имя события
	 * @param array  $event_data Массив с даными о событии
	 * @param string $table      Имя таблицы для вставки
	 */
	public function addToLog($event_name, $event_data = array(), $table = 'events_logs') {
		$name = $this->db_prefix . '_' . $table;
		$data['name'] = $event_name;
		$data['event_data'] = json_encode($event_data);

		$this->db->query('INSERT INTO ?n SET ?u', $name, $data);
	}



}
