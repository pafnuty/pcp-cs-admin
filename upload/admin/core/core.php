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

require_once(ROOT_DIR . 'admin/core/classes/Fenom.php');
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
		
		$this->tpl->addFunction("selected", function($params) {		
			if (strpos($_GET['page'], $params['get']) !== false) {
				return 'selected';
			}
			return false; 
		});

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
	 * @param  integer $pageNum     Номер страницы
	 * @param  integer $perPage     Кол-во элементов, выводимых на страницу
	 * @param  string  $order       Направление сортировки
	 * @param  string  $orderFielsd поле, по которому будем сортировать
	 *
	 * @todo  доработать сорировку, пока сортировать по разным полям нельзя :()
	 * 
	 * @return array                Массив с результатами и количеством элеметнов в таблице
	 */
	public function getList($name = 'license_keys', $pageNum = 0, $perPage = 10, $order = 'ASC', $orderFielsd ) {

		$name = $this->db_prefix . '_' . $name;
		$wheres = array();

		$start = ($pageNum > 0) ?  $perPage*$pageNum - $perPage : 0;
		
		$where = (count($wheres)) ? ' WHERE ' . implode(' AND ', $wheres) : '';

		$select = "SELECT * FROM ?n ?p LIMIT ?i, ?i";
	
		$ret['items'] = $this->db->getAll($select, $name, $where, $start, $perPage);
		$ret['count'] = $this->db->getOne('SELECT COUNT(*) as count FROM ?n ?p', $name, $where);
		return $ret;
	}

}

