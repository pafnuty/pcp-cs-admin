<?php
/**
 * PHP code protect
 *
 * @link          https://github.com/Mofsy/pcp-cs
 * @link          https://github.com/pafnuty/pcp-cs-admin
 * @author        Oleg Budrin <ru.mofsy@yandex.ru>
 * @author        Pavel Belousov <pafnuty10@gmail.com>
 * @copyright     Copyright (c) 2013-2015, Oleg Budrin (Mofsy)
 * 
 */

// namespace Mofsy\License\Server\Core;

class Auth extends AdminCore
{
	/**
	 * Маркер авторизации пользователя
	 *
	 * @var boolean
	 */
	public $user_logged = false;

	/**
	 * Текущий идентификатор пользователя
	 *
	 * @var integer
	 */
	public $user_id = 0;

	/**
	 * Текущий логин пользователя
	 */
	public $user_name = '';

	/**
	 * Группа пользователя, которой разрешена авторизация 
	 */
	public $user_group = 1;

	/**
	 * Текущий айпи адрес пользователя
	 *
	 * @var string
	 */
	public $user_ip = '';

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
		
		$this->user_ip = $this->getIp();

		$this->db_prefix = $this->db_config['dbprefix'];
		$this->user_prefix = $this->db_config['userprefix'];

		$this->db = $this->getDb();
		
		$this->autoLogin();
	}

	/*
	 * автоматический вход
	 */
	public function autoLogin()
	{
		if( isset( $_SESSION['pcp_user_id'] ) AND  intval( $_SESSION['pcp_user_id'] ) > 0 AND $_SESSION['pcp_password'] )
		{
			$this->login($_SESSION['pcp_user_name'], $_SESSION['pcp_password'], false, true);
		}
		elseif( isset( $_COOKIE['pcp_user_id'] ) AND intval( $_COOKIE['pcp_user_id'] ) > 0 AND $_COOKIE['pcp_password'])
		{
			$this->login($_COOKIE['pcp_user_name'], $_COOKIE['pcp_password'], true, true);
		}
		return false;
	}

	/*
	 * Вход
	 */
	public function login($user, $password, $remember = false, $auto = false)
	{
	  
		if(!$auto) {
			$password = md5($password);
		}
		$time = time();

		$log = $this->db->getRow("SELECT * FROM ?n WHERE ip = ?s",  $this->user_prefix . '_login_log', $this->user_ip);

		if ($log['count'] >= $this->config['loginTry'] && ($log['date'] + $this->config['blockingTime']*60) >= $time) {
			die('error');
			return false;
		}
		
		$user = $this->db->getRow("SELECT * FROM ?n WHERE name = ?s",  $this->user_prefix . '_users', $user);

	   	$data = array('ip' => $this->user_ip, 'count' => 1, 'date' => $time);		
		$logSql  = "INSERT INTO ?n SET ?u ON DUPLICATE KEY UPDATE `count` = count+1, `date` = ?s";
		$this->db->query($logSql, $this->user_prefix . '_login_log', $data, $time);


		if( $user['user_id'] AND $user['password'] AND $user['password'] == md5( $password ) AND $user['user_group'] == $this->user_group ) {

			if(!$auto)
			{
				session_regenerate_id();

				if ($remember) {
					$this->setCookie( "pcp_user_id", $user['user_id'], 365 );
					$this->setCookie( "pcp_user_name", $user['name'], 365 );
					$this->setCookie( "pcp_password", $password, 365 );
				} else {
					$this->setCookie( "pcp_user_id", "", 0 );
					$this->setCookie( "pcp_password", "", 0 );
					$this->setCookie( "pcp_user_name", "", 0 );
				}

				$_SESSION['pcp_user_id'] = $user['user_id'];
				$_SESSION['pcp_user_name'] = $user['name'];
				$_SESSION['pcp_password'] = $password;
			}

			$this->user_logged = true;
			$this->user_name = $user['name'];

			$this->db->query("DELETE FROM ?n WHERE ip=?s", $this->user_prefix . '_login_log', $this->user_ip);

			return true;
		}
		return false;
	}

	/*
	 * Выход
	 */
	public function logout()
	{
		$this->setCookie( "pcp_user_id", "", 0 );
		$this->setCookie( "pcp_user_name", "", 0 );
		$this->setCookie( "pcp_password", "", 0 );
		$this->setCookie( session_name(), "", 0 );
		@session_destroy();
		@session_unset();
	}

	/**
	 * Айпи адрес
	 *
	 * @return string
	 */
	public function getIp()
	{
		$ip = '';

		if ($_SERVER['HTTP_CLIENT_IP'])
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ip = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ip = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ip = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ip = $_SERVER['REMOTE_ADDR'];

		return $ip;
	}

	/*
	 * Cookie
	 */
	function setCookie($name, $value, $expires) {

		if( $expires )
		{
			$expires = time() + ($expires * 86400);
		}
		else
		{
			$expires = FALSE;
		}

		setcookie( $name, $value, $expires, "/", $_SERVER['HTTP_HOST'], NULL, TRUE );
	}

}