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

		$this->tpl->addFunction("selected", function ($params) {

			if (strpos($_GET['page'], $params['get']) !== false) {
				return 'selected';
			}
			return false;

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
	 * Метод для получения списка элементов (пока в разработке)
	 * @param  string  $name        Имя таблицы, из которой будем отбирать данные
	 * @param  array   $filter      Поля для фильтрации (поле => условие выборки)
	 * @param  integer $pageNum     Номер страницы
	 * @param  integer $perPage     Кол-во элементов, выводимых на страницу
	 * @param  string  $order       Направление сортировки
	 * @param  string  $orderFielsd поле, по которому будем сортировать
	 *
	 * @todo  доработать сорировку, пока сортировать по разным полям нельзя :()
	 *
	 * @return array                Массив с результатами и количеством элеметнов в таблице
	 */
	public function getList($name = 'license_keys', $filter = array(), $pageNum = 0, $perPage = 10, $order = 'ASC', $orderFielsd) {

		$name = $this->db_prefix . '_' . $name;
		$start = ($pageNum > 0) ? $perPage * $pageNum - $perPage : 0;

		$where = $this->getFilteredWheres($filter);
		$select = "SELECT * FROM ?n ?p LIMIT ?i, ?i";

		$arList['items'] = $this->db->getAll($select, $name, $where, $start, $perPage);
		$arList['count'] = $this->db->getOne('SELECT COUNT(*) as count FROM ?n ?p', $name, $where);

		return $arList;
	}

	/**
	 * Получение массива всех полей из нужной таблицы
	 *
	 * @param  string $name        имя таблицы (без префикса)
	 * @param  string $fields      поля таблицы
	 * @param  string $order       направления сортировки
	 * @param  string $orderFielsd по какому полю сортировать
	 * @return array               массив с элементами
	 */
	public function getAll($name = 'license_methods', $fields = '*', $order = 'ASC', $orderFielsd = 'id') {

		$name = $this->db_prefix . '_' . $name;
		$select = "SELECT ?p FROM ?n ORDER BY ?s ?p";

		$arAll = $this->db->getAll($select, $fields, $name, $orderFielsd, $order);

		return $arAll;
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
