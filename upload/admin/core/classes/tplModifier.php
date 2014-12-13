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

/**
 * Класс для модификаторов шаблонизатора
 */
class tplModifier extends adminCore {
	
	/**
	 * БД
	 * @var Object
	 */
	public $db;

	/*
	 * Конструктор класса
	 */
	public function __construct()
	{
		$this->db_config = $this->getConfig('db_config');
		
		$this->config = $this->getConfig();
		
		$this->user_prefix = $this->db_config['dbprefix'];

		$this->db = $this->getDb();
	}

	/**
	 * Получаем Информацию о пользователе по его ID
	 * @param  integer $id ID пользователя
	 * 
	 * @return array       Массив с информацией о пользователе
	 */
	public function getUserInfo($id = 0) {
		$userinfo = $this->db->getRow("SELECT * FROM ?n WHERE user_id = ?i", $this->user_prefix . '_users', $id);

		return $userinfo;
	}

}