-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 07 2014 г., 22:27
-- Версия сервера: 5.5.38-log
-- Версия PHP: 5.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `licence`
--

-- --------------------------------------------------------

--
-- Структура таблицы `pcp_license_keys`
--

CREATE TABLE IF NOT EXISTS `pcp_license_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор лицензии',
  `user_id` int(11) NOT NULL COMMENT 'Уникальный иднетификаторклиента в системе',
  `user_name` varchar(200) NOT NULL COMMENT 'Уникальное имя клиента в системе',
  `l_name` text NOT NULL COMMENT 'Имя лицензии',
  `l_started` varchar(11) NOT NULL COMMENT 'Дата и время добавления лицензии',
  `l_expires` varchar(11) NOT NULL COMMENT 'Дата и время окончания лицензии',
  `l_key` varchar(255) NOT NULL COMMENT 'Лицензионный ключ активации',
  `l_domain` varchar(245) NOT NULL COMMENT 'Доменное имя, для которого предназначена лицензия',
  `l_domain_wildcard` tinyint(1) NOT NULL,
  `l_ip` text NOT NULL COMMENT 'Айпи адрес пользователя, с которого была выполнена активация',
  `l_directory` text NOT NULL COMMENT 'Путь до скрипта где установлен он в системе у клиента',
  `l_server_hostname` text NOT NULL COMMENT 'Имя хоста,где установлен скрипт',
  `l_server_ip` text NOT NULL COMMENT 'Айпи адрес сервера,на которой установлена копия для данной лицензии',
  `l_status` tinyint(1) NOT NULL COMMENT 'Статус лицензии, 0 - не активирована, 1 - активирована, 2 - срок истек, 3 - лицензия переиздана',
  `l_method_id` int(11) NOT NULL COMMENT 'Идентификатор метода проверки лицензии',
  `l_last_check` int(11) NOT NULL COMMENT 'Дата последней проверки',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


--
-- Структура таблицы `pcp_license_logs`
--

CREATE TABLE IF NOT EXISTS `pcp_license_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор лога',
  `date` int(11) NOT NULL COMMENT 'Дата действия',
  `status` tinyint(1) NOT NULL COMMENT 'Статус действия, 0 - не активирована, 1 - активирована, 2 - срок истек, 3 - лицензия переиздана',
  `l_status` varchar(150) NOT NULL COMMENT 'Текстовый статус лицензии при проверке',
  `l_id` int(11) NOT NULL COMMENT 'Id лицензии если существует',
  `l_key` varchar(255) NOT NULL COMMENT 'Лицензионный ключ активации который был использован',
  `l_domain` varchar(245) NOT NULL COMMENT 'Доменное имя которое было использовано',
  `l_ip` text NOT NULL COMMENT 'Айпи адрес пользователя, с которого была выполнена операция',
  `l_directory` text NOT NULL COMMENT 'Путь до скрипта где установлен скрипт в системе у клиента',
  `l_server_hostname` text NOT NULL COMMENT 'Имя хоста, где установлен скрипт',
  `l_server_ip` text NOT NULL COMMENT 'Айпи адрес сервера,на которой установлена копия для данной лицензии',
  `l_method_id` int(11) NOT NULL COMMENT 'Идентификатор метода проверки лицензии',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

-- --------------------------------------------------------

--
-- Структура таблицы `pcp_license_methods`
--

CREATE TABLE IF NOT EXISTS `pcp_license_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор метода',
  `name` text NOT NULL COMMENT 'Название метода',
  `secret_key` text NOT NULL COMMENT 'Секретный ключ',
  `check_period` int(11) NOT NULL COMMENT 'Период проверки в днях',
  `enforce` text NOT NULL COMMENT 'Что проверять',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


--
-- Структура таблицы `pcp_users`
--

CREATE TABLE IF NOT EXISTS `pcp_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор пользователя',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT 'Почта пользователя',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT 'Пароль пользователя',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT 'Логин пользователя',
  `user_group` smallint(5) NOT NULL DEFAULT '4' COMMENT 'Группа пользователя',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


--
-- Структура таблицы `pcp_events_logs`
--

CREATE TABLE IF NOT EXISTS `pcp_events_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Название события',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `event_data` text NOT NULL COMMENT 'Данные о событии',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--
-- Структура таблицы `pcp_login_log`
--

CREATE TABLE IF NOT EXISTS `pcp_login_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL DEFAULT '',
  `count` smallint(6) NOT NULL DEFAULT '0',
  `date` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip` (`ip`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
