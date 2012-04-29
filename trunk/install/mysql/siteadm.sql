CREATE DATABASE IF NOT EXISTS siteadm;

CREATE USER 'siteadm'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `siteadm` . * TO 'siteadm'@'localhost';

CREATE USER 'siteadm_dovecot'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm_dovecot'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `siteadm`.`dovecot_email` TO 'siteadm_dovecot'@'localhost';

CREATE USER 'siteadm_proftpd'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm_proftpd'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `siteadm`.`proftpd_user` TO 'siteadm_proftpd'@'localhost';

CREATE USER 'siteadm_postfix'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm_postfix'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `siteadm`.`postfix_alias` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_domain` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_mbox` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_redirect` TO 'siteadm_postfix'@'localhost';

USE `siteadm`;

-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 20 Avril 2012 à 21:19
-- Version du serveur: 5.1.61
-- Version de PHP: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `siteadm`
--

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `manager_id` int(10) unsigned DEFAULT NULL,
  `type` enum('user','manager','admin') NOT NULL DEFAULT 'user',
  `offre_id` int(10) unsigned DEFAULT NULL,
  `offre_expire` date DEFAULT NULL,
  `folder` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `password_md5` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `civilite` enum('m','mme','mlle') DEFAULT NULL,
  `prenom` varchar(64) NOT NULL,
  `nom` varchar(64) NOT NULL,
  `societe` varchar(128) DEFAULT NULL,
  `disk_quota_soft` int(10) unsigned DEFAULT NULL,
  `disk_quota_hard` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `manager_id` (`manager_id`),
  KEY `offre_id` (`offre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `account_language_bin_ref`
--

CREATE TABLE IF NOT EXISTS `account_language_bin_ref` (
  `account_id` int(10) unsigned NOT NULL,
  `language_bin_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`account_id`,`language_bin_id`),
  KEY `langage_bin_id` (`language_bin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `account_log`
--

CREATE TABLE IF NOT EXISTS `account_log` (
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `account_id` int(10) unsigned DEFAULT NULL,
  `operation` text NOT NULL,
  `ip` varchar(16) NOT NULL,
  KEY `datetime` (`datetime`),
  KEY `account_id` (`account_id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `account_php_ext_ref`
--

CREATE TABLE IF NOT EXISTS `account_php_ext_ref` (
  `account_id` int(10) unsigned NOT NULL,
  `phpext_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`account_id`,`phpext_id`),
  KEY `phpext_id` (`phpext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `db`
--

CREATE TABLE IF NOT EXISTS `db` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `account_id` int(10) unsigned DEFAULT NULL,
  `dbname` varchar(32) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(16) NOT NULL,
  `quota` enum('10','100','1000','10000') NOT NULL DEFAULT '100',
  `max_user_connections` int(10) unsigned NOT NULL DEFAULT '10',
  `max_queries` int(10) unsigned NOT NULL DEFAULT '1000',
  `max_connections` int(10) unsigned NOT NULL DEFAULT '1000',
  `max_updates` int(10) unsigned NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`dbname`),
  KEY `account` (`account_id`,`dbname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `domain`
--

CREATE TABLE IF NOT EXISTS `domain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `account_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `email_actif` tinyint(1) NOT NULL DEFAULT '1',
  `creation_date` date DEFAULT NULL,
  `renew_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `domain_bind`
--

CREATE TABLE IF NOT EXISTS `domain_bind` (
  `domain_id` int(10) unsigned NOT NULL,
  `type` varchar(64) NOT NULL,
  KEY `domain_id` (`domain_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `dovecot_email`
--
CREATE TABLE IF NOT EXISTS `dovecot_email` (
`account_id` int(10) unsigned
,`account_name` varchar(32)
,`actif` int(1)
,`email_name` varchar(64)
,`domain_name` varchar(128)
,`email` varchar(193)
,`password` varbinary(32)
,`uid` bigint(12) unsigned
,`gid` bigint(12) unsigned
,`home` varchar(246)
,`mail` varchar(254)
);
-- --------------------------------------------------------

--
-- Structure de la table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `actif` enum('0','1') NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `password` varchar(64) NOT NULL,
  `password_md5` varchar(64) NOT NULL,
  `quota` enum('10','100','1000','10000') NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_id` (`domain_id`,`name`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `email_alias`
--

CREATE TABLE IF NOT EXISTS `email_alias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(64) NOT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `email_id` int(10) unsigned DEFAULT NULL,
  `redirect_email` varchar(128) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`,`name`),
  KEY `account_id` (`account_id`),
  KEY `email_id` (`email_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `email_sync`
--

CREATE TABLE IF NOT EXISTS `email_sync` (
  `email_id` int(10) unsigned NOT NULL,
  `hostname` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(16) NOT NULL,
  KEY `email_id` (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ftp_group`
--

CREATE TABLE IF NOT EXISTS `ftp_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupname` varchar(16) NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `members` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupname` (`groupname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `ftp_user`
--

CREATE TABLE IF NOT EXISTS `ftp_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `folder` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `install_bin`
--

CREATE TABLE IF NOT EXISTS `install_bin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `install_packages`
--

CREATE TABLE IF NOT EXISTS `install_packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `script` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `content_type` varchar(64) NOT NULL,
  `extension_list` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `language_bin`
--

CREATE TABLE IF NOT EXISTS `language_bin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` tinyint(3) unsigned NOT NULL,
  `app_compatible` tinyint(1) NOT NULL DEFAULT '0',
  `version` varchar(16) NOT NULL,
  `CGI_type` varchar(16) NOT NULL,
  `options` varchar(64) NOT NULL,
  `prefix` varchar(64) NOT NULL,
  `exec_bin` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `language_compile_options`
--

CREATE TABLE IF NOT EXISTS `language_compile_options` (
  `language_id` tinyint(3) unsigned NOT NULL,
  `cat` enum('misc','php','sapi') NOT NULL DEFAULT 'sapi',
  `name` varchar(64) NOT NULL,
  `type` enum('with','enable') NOT NULL,
  `value` varchar(32) DEFAULT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY (`language_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `language_php_bin_ext_ref`
--

CREATE TABLE IF NOT EXISTS `language_php_bin_ext_ref` (
  `language_bin_id` int(10) unsigned NOT NULL,
  `ext_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`language_bin_id`,`ext_id`),
  KEY `ext_id` (`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `language_php_ext`
--

CREATE TABLE IF NOT EXISTS `language_php_ext` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `type` enum('extension','pear','pecl') NOT NULL DEFAULT 'extension',
  `package` varchar(64) NOT NULL,
  `description` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `description` (`description`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `language_php_functions`
--

CREATE TABLE IF NOT EXISTS `language_php_functions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `security` enum('proc','basedir','filesystem','info','perf') NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `security` (`security`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mysql`
--

CREATE TABLE IF NOT EXISTS `mysql` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `account_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `password` varchar(16) NOT NULL,
  `max_user_connections` int(10) unsigned DEFAULT '10',
  `max_queries` int(10) unsigned DEFAULT '1000',
  `max_connections` int(10) unsigned DEFAULT '1000',
  `max_updates` int(10) unsigned DEFAULT '1000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

CREATE TABLE IF NOT EXISTS `offre` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `tarif` decimal(10,2) unsigned DEFAULT NULL,
  `disk_quota_soft` int(10) unsigned DEFAULT NULL,
  `disk_quota_hard` int(10) unsigned DEFAULT NULL,
  `worker_max` tinyint(3) unsigned DEFAULT NULL,
  `worker_ram_max` int(10) unsigned DEFAULT NULL,
  `mysql_db_max` tinyint(3) unsigned DEFAULT NULL,
  `domain_nb_max` tinyint(3) unsigned DEFAULT NULL,
  `website_nb_max` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `phpapp`
--

CREATE TABLE IF NOT EXISTS `phpapp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `language_bin_id` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  `webmaster_email` varchar(128) DEFAULT NULL,
  `apc_shm_size` int(3) unsigned NOT NULL DEFAULT '16',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_id_2` (`account_id`,`name`),
  KEY `language_bin_id` (`language_bin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `phppool`
--

CREATE TABLE IF NOT EXISTS `phppool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned DEFAULT NULL,
  `system_user` varchar(32) DEFAULT NULL,
  `system_group` varchar(32) DEFAULT NULL,
  `phpapp_id` int(10) unsigned DEFAULT NULL,
  `language_bin_id` int(10) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `webmaster_email` varchar(128) DEFAULT NULL,
  `worker_nb_max` tinyint(4) NOT NULL DEFAULT '1',
  `worker_max_requests` int(10) unsigned NOT NULL DEFAULT '5000',
  `error_display` tinyint(1) NOT NULL DEFAULT '0',
  `error_filesave` tinyint(1) NOT NULL DEFAULT '1',
  `max_execution_time` tinyint(3) unsigned NOT NULL DEFAULT '30',
  `max_input_time` tinyint(3) unsigned NOT NULL DEFAULT '60',
  `memory_limit` tinyint(3) unsigned NOT NULL DEFAULT '64',
  `post_max_size` tinyint(3) unsigned NOT NULL DEFAULT '8',
  `file_uploads` enum('On','Off') NOT NULL DEFAULT 'On',
  `upload_max_filesize` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `max_file_upload` tinyint(2) unsigned NOT NULL DEFAULT '5',
  `error_reporting` varchar(64) NOT NULL DEFAULT 'E_ALL & ~E_DEPRECATED',
  `include_path` varchar(256) NOT NULL DEFAULT '.',
  `short_open_tag` tinyint(1) NOT NULL DEFAULT '0',
  `apc_stat` tinyint(1) NOT NULL,
  `apc_lazy` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_id_2` (`account_id`,`name`),
  KEY `language_bin_id` (`language_bin_id`),
  KEY `phpapp_id` (`phpapp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `phppool_disable_functions_ref`
--

CREATE TABLE IF NOT EXISTS `phppool_disable_functions_ref` (
  `phppool_id` int(10) unsigned NOT NULL,
  `function_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`phppool_id`,`function_id`),
  KEY `function_id` (`function_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `phppool_ext_ref`
--

CREATE TABLE IF NOT EXISTS `phppool_ext_ref` (
  `phppool_id` int(10) unsigned NOT NULL,
  `ext_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`phppool_id`,`ext_id`),
  KEY `ext_id` (`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `postfix_alias`
--
CREATE TABLE IF NOT EXISTS `postfix_alias` (
`account_id` int(10) unsigned
,`account_name` varchar(32)
,`destination` varchar(193)
,`origine` varchar(193)
,`actif` int(1)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `postfix_domain`
--
CREATE TABLE IF NOT EXISTS `postfix_domain` (
`account_id` int(10) unsigned
,`account_name` varchar(32)
,`name` varchar(128)
,`actif` int(1)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `postfix_mbox`
--
CREATE TABLE IF NOT EXISTS `postfix_mbox` (
`account_id` int(10) unsigned
,`account_name` varchar(32)
,`uid` bigint(12) unsigned
,`gid` bigint(12) unsigned
,`email` varchar(193)
,`maildir` varchar(232)
,`actif` int(1)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `postfix_redirect`
--
CREATE TABLE IF NOT EXISTS `postfix_redirect` (
`account_id` int(10) unsigned
,`account_name` varchar(32)
,`destination` varchar(128)
,`origine` varchar(193)
,`actif` int(1)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `proftpd_user`
--
CREATE TABLE IF NOT EXISTS `proftpd_user` (
`account_id` int(10) unsigned
,`actif` int(1)
,`id` int(10) unsigned
,`username` varchar(64)
,`password` varbinary(13)
,`uid` bigint(12) unsigned
,`gid` bigint(12) unsigned
,`folder` varchar(181)
,`/bin/bash` varchar(9)
);
-- --------------------------------------------------------

--
-- Structure de la table `webapp`
--

CREATE TABLE IF NOT EXISTS `webapp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(64) NOT NULL,
  `version` varchar(16) NOT NULL,
  `description` text NOT NULL,
  `folder_alias` text,
  `php_include_folder` varchar(128) DEFAULT NULL,
  `php_open_basedir` varchar(128) DEFAULT NULL,
  `php_short_open_tag` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`version`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `website`
--

CREATE TABLE IF NOT EXISTS `website` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned DEFAULT NULL,
  `folder` varchar(64) NOT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `name` varchar(128) NOT NULL,
  `webapp_id` int(10) unsigned DEFAULT NULL,
  `phppool_id` int(10) unsigned DEFAULT NULL,
  `webmaster_email` varchar(128) NOT NULL,
  `index_files` varchar(64) NOT NULL DEFAULT 'index.php index.html',
  `charset_default` enum('utf-8','iso-8859-1') DEFAULT 'utf-8',
  `folder_auth` tinyint(1) NOT NULL DEFAULT '0',
  `ssl` tinyint(1) NOT NULL DEFAULT '1',
  `ssl_force_redirect` tinyint(1) NOT NULL DEFAULT '0',
  `php_engine` tinyint(1) NOT NULL DEFAULT '1',
  `php_expose` enum('On','Off') NOT NULL DEFAULT 'Off',
  `php_max_execution_time` tinyint(3) unsigned NOT NULL DEFAULT '30',
  `php_max_input_time` tinyint(3) unsigned NOT NULL DEFAULT '60',
  `php_memory_limit` tinyint(3) unsigned NOT NULL DEFAULT '32',
  `php_post_max_size` tinyint(3) unsigned NOT NULL DEFAULT '8',
  `php_file_uploads` enum('On','Off') NOT NULL DEFAULT 'On',
  `php_upload_max_filesize` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `php_max_file_upload` tinyint(2) unsigned NOT NULL DEFAULT '5',
  `php_error_reporting` varchar(64) NOT NULL DEFAULT 'E_ALL & ~E_DEPRECATED',
  `php_enable_dl` enum('On','Off') NOT NULL DEFAULT 'Off',
  `php_include_path` varchar(256) DEFAULT NULL,
  `php_open_basedir` varchar(64) DEFAULT NULL,
  `php_apc_stat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_id_2` (`domain_id`,`name`),
  KEY `account_id` (`account_id`),
  KEY `webapp_id` (`webapp_id`),
  KEY `phppool_id` (`phppool_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `website_alias`
--

CREATE TABLE IF NOT EXISTS `website_alias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `alias_name` varchar(64) NOT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `website_id` int(10) unsigned DEFAULT NULL,
  `website_redirect` tinyint(1) NOT NULL DEFAULT '0',
  `redirect_url` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_id` (`domain_id`,`alias_name`),
  KEY `website_id` (`website_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `website_phpext_ref`
--

CREATE TABLE IF NOT EXISTS `website_phpext_ref` (
  `website_id` int(10) unsigned NOT NULL,
  `phpext_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`website_id`,`phpext_id`),
  KEY `phpext_id` (`phpext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `website_php_disable_functions_ref`
--

CREATE TABLE IF NOT EXISTS `website_php_disable_functions_ref` (
  `website_id` int(10) unsigned NOT NULL,
  `function_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`website_id`,`function_id`),
  KEY `function_id` (`function_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la vue `dovecot_email`
--
DROP TABLE IF EXISTS `dovecot_email`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dovecot_email` AS select if (`account`.`id` is null, 0, `account`.`id`) AS `account_id`,if (`account`.`name` is null, 'common', `account`.`name`) AS `account_name`,((`account`.`actif` is null OR `account`.`actif`) and `email`.`actif` and `domain`.`email_actif`) AS `actif`,`email`.`name` AS `email_name`,`domain`.`name` AS `domain_name`,concat(`email`.`name`,'@',`domain`.`name`) AS `email`,md5(`email`.`password`) AS `password`,(if (`account`.`id` is null, 0, `account`.`id`) + 2000) AS `uid`,(if (`account`.`id` is null, 0, `account`.`id`) + 2000) AS `gid`,concat('/home/siteadm/',if (`account`.`folder` is null, 'common', `account`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `home`,concat('maildir:/home/siteadm/',if (`account`.`folder` is null, 'common', `account`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `mail` from `email` join `domain` on `domain`.`id` = `email`.`domain_id` left join `account` on `account`.`id` = `domain`.`account_id`;

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_alias`
--
DROP TABLE IF EXISTS `postfix_alias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_alias` AS select if (`t5`.`id` is null, 0, `t5`.`id`) AS `account_id`, if(`t5`.`name` is null, 'common', `t5`.`name`) AS `account_name`,concat(`t1`.`name`,'@',`t3`.`name`) AS `destination`,concat(`t2`.`name`,'@',`t4`.`name`) AS `origine`,((`t5`.`actif` is null OR `t5`.`actif`) and `t2`.`actif` and `t4`.`email_actif`) AS `actif` from ((((`email` `t1` join `email_alias` `t2` on((`t1`.`id` = `t2`.`email_id`))) join `domain` `t3` on((`t1`.`domain_id` = `t3`.`id`))) join `domain` `t4` on((`t2`.`domain_id` = `t4`.`id`))) left join `account` `t5` on((`t5`.`id` = `t3`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_domain`
--
DROP TABLE IF EXISTS `postfix_domain`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_domain` AS select if (`t2`.`id` is null, 0, `t2`.`id`) AS `account_id`,if (`t2`.`name` is null, 'common',`t2`.`name`) AS `account_name`,`t1`.`name` AS `name`,((`t2`.`actif` is null OR `t2`.`actif`) and `t1`.`email_actif`) AS `actif` from (`domain` `t1` left join `account` `t2` on((`t2`.`id` = `t1`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_mbox`
--
DROP TABLE IF EXISTS `postfix_mbox`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_mbox` AS select if (`account`.`id` is null, 0, `account`.`id`) AS `account_id`, if (`account`.`name` is null, 'common', `account`.`name`) AS `account_name`,(2000 + if (`account`.`id` is null, 0, `account`.`id`)) AS `uid`,(2000 + if (`account`.`id` is null, 0, `account`.`id`)) AS `gid`,concat(`email`.`name`,'@',`domain`.`name`) AS `email`,concat(if(`account`.`folder` is null, 'common', `account`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `maildir`,((`email`.`actif` = '1') and `domain`.`email_actif` and (`account`.`actif` is null OR `account`.`actif`)) AS `actif` from ((`email` join `domain` on((`domain`.`id` = `email`.`domain_id`))) left join `account` on((`account`.`id` = `domain`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_redirect`
--
DROP TABLE IF EXISTS `postfix_redirect`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_redirect` AS select if (`t5`.`id` is null, 0, `t5`.`id`) AS `account_id`,if (`t5`.`name` is null, 'common', `t5`.`name`) AS `account_name`,`t2`.`redirect_email` AS `destination`,concat(`t2`.`name`,'@',`t3`.`name`) AS `origine`,(`t2`.`actif` and `t3`.`email_actif` and (`t5`.`actif` is null OR `t5`.`actif`)) AS `actif` from ((`email_alias` `t2` join `domain` `t3` on((`t3`.`id` = `t2`.`domain_id`))) left join `account` `t5` on((`t5`.`id` = `t3`.`account_id`))) where ((`t2`.`redirect_email` <> '') and (`t2`.`redirect_email` is not null));

-- --------------------------------------------------------

--
-- Structure de la vue `proftpd_user`
--
DROP TABLE IF EXISTS `proftpd_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `proftpd_user` AS select `account`.`id` AS `account_id`,(`account`.`actif` and `ftp_user`.`actif`) AS `actif`,`ftp_user`.`id` AS `id`,`ftp_user`.`username` AS `username`,encrypt(`ftp_user`.`password`) AS `password`,(2000 + `account`.`id`) AS `uid`,(2000 + `account`.`id`) AS `gid`,concat('/home/siteadm/',`account`.`folder`,'/public',`ftp_user`.`folder`) AS `folder`,'/bin/bash' AS `/bin/bash` from (`account` join `ftp_user` on((`account`.`id` = `ftp_user`.`account_id`)));

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `account` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`offre_id`) REFERENCES `offre` (`id`);

--
-- Contraintes pour la table `account_language_bin_ref`
--
ALTER TABLE `account_language_bin_ref`
  ADD CONSTRAINT `account_language_bin_ref_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `account_log`
--
ALTER TABLE `account_log`
  ADD CONSTRAINT `account_log_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

--
-- Contraintes pour la table `account_php_ext_ref`
--
ALTER TABLE `account_php_ext_ref`
  ADD CONSTRAINT `account_php_ext_ref_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_php_ext_ref_ibfk_2` FOREIGN KEY (`phpext_id`) REFERENCES `language_php_ext` (`id`);

--
-- Contraintes pour la table `db`
--
ALTER TABLE `db`
  ADD CONSTRAINT `db_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `domain`
--
ALTER TABLE `domain`
  ADD CONSTRAINT `domain_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `domain_bind`
--
ALTER TABLE `domain_bind`
  ADD CONSTRAINT `domain_bind_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `email`
--
ALTER TABLE `email`
  ADD CONSTRAINT `email_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `email_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `email_alias`
--
ALTER TABLE `email_alias`
  ADD CONSTRAINT `email_alias_ibfk_1` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `email_alias_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `email_alias_ibfk_3` FOREIGN KEY (`email_id`) REFERENCES `email` (`id`);

--
-- Contraintes pour la table `email_sync`
--
ALTER TABLE `email_sync`
  ADD CONSTRAINT `email_sync_ibfk_1` FOREIGN KEY (`email_id`) REFERENCES `email` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ftp_user`
--
ALTER TABLE `ftp_user`
  ADD CONSTRAINT `ftp_user_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `install_bin`
--
ALTER TABLE `install_bin`
  ADD CONSTRAINT `install_bin_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `install_packages` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `language_bin`
--
ALTER TABLE `language_bin`
  ADD CONSTRAINT `language_bin_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `language_compile_options`
--
ALTER TABLE `language_compile_options`
  ADD CONSTRAINT `language_compile_options_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `language_php_bin_ext_ref`
--
ALTER TABLE `language_php_bin_ext_ref`
  ADD CONSTRAINT `language_php_bin_ext_ref_ibfk_2` FOREIGN KEY (`ext_id`) REFERENCES `language_php_ext` (`id`),
  ADD CONSTRAINT `language_php_bin_ext_ref_ibfk_3` FOREIGN KEY (`language_bin_id`) REFERENCES `language_bin` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mysql`
--
ALTER TABLE `mysql`
  ADD CONSTRAINT `mysql_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `phpapp`
--
ALTER TABLE `phpapp`
  ADD CONSTRAINT `phpapp_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phpapp_ibfk_2` FOREIGN KEY (`language_bin_id`) REFERENCES `language_bin` (`id`);

--
-- Contraintes pour la table `phppool`
--
ALTER TABLE `phppool`
  ADD CONSTRAINT `phppool_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phppool_ibfk_2` FOREIGN KEY (`phpapp_id`) REFERENCES `phpapp` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phppool_ibfk_3` FOREIGN KEY (`language_bin_id`) REFERENCES `language_bin` (`id`);

--
-- Contraintes pour la table `phppool_disable_functions_ref`
--
ALTER TABLE `phppool_disable_functions_ref`
  ADD CONSTRAINT `phppool_disable_functions_ref_ibfk_1` FOREIGN KEY (`phppool_id`) REFERENCES `phppool` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppool_disable_functions_ref_ibfk_2` FOREIGN KEY (`function_id`) REFERENCES `language_php_functions` (`id`);

--
-- Contraintes pour la table `phppool_ext_ref`
--
ALTER TABLE `phppool_ext_ref`
  ADD CONSTRAINT `phppool_ext_ref_ibfk_1` FOREIGN KEY (`phppool_id`) REFERENCES `phppool` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phppool_ext_ref_ibfk_2` FOREIGN KEY (`ext_id`) REFERENCES `language_php_ext` (`id`);

--
-- Contraintes pour la table `website`
--
ALTER TABLE `website`
  ADD CONSTRAINT `website_ibfk_10` FOREIGN KEY (`phppool_id`) REFERENCES `phppool` (`id`),
  ADD CONSTRAINT `website_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `website_ibfk_8` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`id`),
  ADD CONSTRAINT `website_ibfk_9` FOREIGN KEY (`webapp_id`) REFERENCES `webapp` (`id`);

--
-- Contraintes pour la table `website_alias`
--
ALTER TABLE `website_alias`
  ADD CONSTRAINT `website_alias_ibfk_3` FOREIGN KEY (`domain_id`) REFERENCES `domain` (`id`),
  ADD CONSTRAINT `website_alias_ibfk_4` FOREIGN KEY (`website_id`) REFERENCES `website` (`id`);

--
-- Contraintes pour la table `website_phpext_ref`
--
ALTER TABLE `website_phpext_ref`
  ADD CONSTRAINT `website_phpext_ref_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `website` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `website_phpext_ref_ibfk_2` FOREIGN KEY (`phpext_id`) REFERENCES `language_php_ext` (`id`);

--
-- Contraintes pour la table `website_php_disable_functions_ref`
--
ALTER TABLE `website_php_disable_functions_ref`
  ADD CONSTRAINT `website_php_disable_functions_ref_ibfk_1` FOREIGN KEY (`website_id`) REFERENCES `website` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `website_php_disable_functions_ref_ibfk_2` FOREIGN KEY (`function_id`) REFERENCES `language_php_functions` (`id`);


-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 20 Avril 2012 à 21:18
-- Version du serveur: 5.1.61
-- Version de PHP: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `siteadm`
--

--
-- Contenu de la table `language`
--

INSERT INTO `language` (`id`, `name`, `content_type`, `extension_list`) VALUES
(1, 'PHP', 'application/x-httpd-php', 'php php3 php4 php5'),
(2, 'Python', '', ''),
(3, 'Perl', '', ''),
(4, 'Ruby', '', '');

--
-- Contenu de la table `language_bin`
--

INSERT INTO `language_bin` (`id`, `language_id`, `app_compatible`, `version`, `CGI_type`, `options`, `prefix`, `exec_bin`) VALUES
(1, 1, 0, '4.3.9', 'Spawn FCGI', 'Webstudiosi specific', '/opt/php-4.3.9-webstudiosi', '/opt/php-4.3.9-webstudiosi/bin/php'),
(2, 1, 0, '4.4.9', 'Spawn FCGI', '', '/opt/php-4.4.9', '/opt/php-4.4.9/bin/php'),
(3, 1, 0, '5.2.17', 'Spawn FCGI', '', '/opt/php-5.2.17', '/opt/php-5.2.17/bin/php'),
(4, 1, 1, '5.3.3', 'FPM', '', '/opt/php-5.3.3', '/opt/php-5.3.3/bin/php'),
(5, 1, 1, '5.3.6', 'FPM', 'Full options', '/opt/php-5.3.6-full', '/opt/php-5.3.6-full/bin/php'),
(6, 3, 0, '5.14.0', '', '', '', ''),
(7, 2, 0, '3.2.1final', '', '', '', ''),
(8, 2, 0, '2.6.7rc1', '', '', '', ''),
(9, 4, 0, '1.9.1', '', '', '', '');

--
-- Contenu de la table `language_compile_options`
--

INSERT INTO `language_compile_options` (`language_id`, `cat`, `name`, `type`, `value`, `desc`) VALUES
(1, 'sapi', '--disable-cgi', 'enable', NULL, 'Disable building CGI version of PHP. Available with PHP 4.3.0.\r\nAs of PHP 5.3.0 this argument enables FastCGI which previously had to be enabled using --enable-fastcgi.'),
(1, 'sapi', '--disable-cli', 'enable', NULL, 'Available with PHP 4.3.0. Disable building the CLI version of PHP (this forces --without-pear). More information is available in the section about Using PHP from the command line.'),
(1, 'misc', '--disable-libtool-lock', 'enable', NULL, 'Avoid locking (might break parallel builds).'),
(1, 'sapi', '--disable-path-info-check', 'enable', NULL, 'If this is disabled, paths such as /info.php/test?a=b will fail to work. Available since PHP 4.3.0. For more information see the » Apache Manual.'),
(1, 'misc', '--disable-rpath', 'enable', NULL, 'Disable passing additional runtime library search paths.'),
(1, 'php', '--disable-short-tags', 'enable', NULL, 'Disable the short-form <? start tag by default.'),
(1, 'misc', '--disable-url-fopen-wrapper', 'enable', NULL, 'Disable the URL-aware fopen wrapper that allows accessing files via HTTP or FTP. (not available since PHP 5.2.5)'),
(1, 'misc', '--enable-debug', 'enable', NULL, 'Compile with debugging symbols.'),
(1, 'sapi', '--enable-discard-path', 'enable', NULL, 'If this is enabled, the PHP CGI binary can safely be placed outside of the web tree and people will not be able to circumvent .htaccess security.\r\nAs of PHP 5.3.0 this argument is disabled by default and no longer exists. To enable this feature the cgi.discard_path ini directive must be set to 1.'),
(1, 'sapi', '--enable-embed[=TYPE]', 'enable', NULL, 'Enable building of the embedded SAPI library. TYPE is either shared or static, which defaults to shared. Available with PHP 4.3.0.'),
(1, 'misc', '--enable-fast-install[=PKGS]', 'enable', NULL, 'Optimize for fast installation [default=yes].'),
(1, 'sapi', '--enable-fastcgi', 'enable', NULL, 'If this is enabled, the CGI module will be built with support for FastCGI also. Available since PHP 4.3.0\r\nAs of PHP 5.3.0 this argument no longer exists and is enabled by --enable-cgi instead.'),
(1, 'sapi', '--enable-force-cgi-redirect', 'enable', NULL, 'Enable the security check for internal server redirects. You should use this if you are running the CGI version with Apache.\r\nAs of PHP 5.3.0 this argument is enabled by default and no longer exists. To disable this, the cgi.force_redirect ini directive should be set to 0.'),
(1, 'misc', '--enable-libgcc', 'enable', NULL, 'Enable explicitly linking against libgcc.'),
(1, 'php', '--enable-magic-quotes', 'enable', NULL, 'Enable magic quotes by default.'),
(1, 'php', '--enable-maintainer-mode', 'enable', NULL, 'Enable make rules and dependencies not useful (and sometimes confusing) to the casual installer.'),
(1, 'misc', '--enable-memory-limit', 'enable', NULL, 'Compile with memory limit support. (not available since PHP 5.2.1 - always enabled)'),
(1, 'misc', '--enable-php-streams', 'enable', NULL, 'Include experimental PHP streams. Do not use unless you are testing the code!'),
(1, 'sapi', '--enable-roxen-zts', 'enable', NULL, 'Build the Roxen module using Zend Thread Safety.'),
(1, 'php', '--enable-safe-mode', 'enable', NULL, 'Enable safe mode by default.'),
(1, 'misc', '--enable-shared[=PKGS]', 'enable', NULL, 'Build shared libraries [default=yes].'),
(1, 'misc', '--enable-sigchild', 'enable', NULL, 'Enable PHP''s own SIGCHLD handler.'),
(1, 'misc', '--enable-static[=PKGS]', 'enable', NULL, 'Build static libraries [default=yes].'),
(1, 'misc', '--enable-trans-sid', 'enable', NULL, 'Enable transparent session id propagation. Only valid for PHP 4.1.2 or less. From PHP 4.2.0, trans-sid feature is always compiled.'),
(1, 'misc', '--enable-versioning', 'with', NULL, 'Export only required symbols. See INSTALL for more information.'),
(1, 'php', '--enable-zend-multibyte', 'enable', NULL, 'Enables multibyte code in the language parser and scanner to be executed. When PHP is compiled with this option, it also enables the encoding directive in the declare construct.'),
(1, 'sapi', '--with-aolserver=DIR', 'with', NULL, 'Specify path to the installed AOLserver.'),
(1, 'sapi', '--with-apache[=DIR]', 'with', NULL, 'Build a static Apache module. DIR is the top-level Apache build directory, defaults to /usr/local/apache.'),
(1, 'sapi', '--with-apxs2[=FILE]', 'with', NULL, 'Build shared Apache 2.0 module. FILE is the optional pathname to the Apache apxs tool; defaults to apxs.'),
(1, 'sapi', '--with-apxs[=FILE]', 'with', NULL, 'Build shared Apache module. FILE is the optional pathname to the Apache apxs tool; defaults to apxs. Make sure you specify the version of apxs that is actually installed on your system and NOT the one that is in the apache source tarball.'),
(1, 'sapi', '--with-caudium=DIR', 'with', NULL, 'Build PHP as a Pike module for use with Caudium. DIR is the Caudium server dir, with the default value /usr/local/caudium/server.'),
(1, 'php', '--with-config-file-path=PATH', 'with', NULL, 'Sets the path in which to look for php.ini, defaults to PREFIX/lib.'),
(1, 'php', '--with-exec-dir[=DIR]', 'with', NULL, 'Only allow executables in DIR when in safe mode defaults to /usr/local/php/bin.'),
(1, 'sapi', '--with-fastcgi', 'with', NULL, 'Build PHP as FastCGI application. No longer available as of PHP 4.3.0, instead you should use --enable-fastcgi .'),
(1, 'sapi', '--with-fhttpd[=DIR]', 'with', NULL, 'Build fhttpd module. DIR is the fhttpd sources directory, defaults to /usr/local/src/fhttpd. No longer available as of PHP 4.3.0.'),
(1, 'misc', '--with-gnu-ld', 'with', NULL, 'Assume the C compiler uses GNU ld [default=no].'),
(1, 'sapi', '--with-isapi=DIR', 'with', NULL, 'Build PHP as an ISAPI module for use with Zeus.'),
(1, 'misc', '--with-layout=TYPE', 'with', NULL, 'Sets how installed files will be laid out. Type is one of PHP (default) or GNU.'),
(1, 'php', '--with-libdir', 'with', NULL, 'Specifies the directory where the libraries to build PHP exists on a Unix system. For 64bit systems, its needed to specify this argument to the lib64 directory like: --with-libdir=lib64.'),
(1, 'sapi', '--with-mod_charset', 'with', NULL, 'Enable transfer tables for mod_charset (Russian Apache).'),
(1, 'sapi', '--with-nsapi=DIR', 'with', NULL, 'Specify path to the installed Netscape/iPlanet/SunONE Webserver.'),
(1, 'misc', '--with-pear=DIR', 'with', NULL, 'Install PEAR in DIR (default PREFIX/lib/php).'),
(1, 'sapi', '--with-phttpd=DIR', 'with', NULL, 'No information yet.'),
(1, 'sapi', '--with-pi3web=DIR', 'with', NULL, 'Build PHP as a module for use with Pi3Web.'),
(1, 'misc', '--with-pic', 'with', NULL, 'Try to use only PIC/non-PIC objects [default=use both].'),
(1, 'sapi', '--with-roxen=DIR', 'with', NULL, 'Build PHP as a Pike module. DIR is the base Roxen directory, normally /usr/local/roxen/server.'),
(1, 'sapi', '--with-servlet[=DIR]', 'with', NULL, 'Include servlet support. DIR is the base install directory for the JSDK. This SAPI requires the java extension must be built as a shared dl.'),
(1, 'sapi', '--with-thttpd=SRCDIR', 'with', NULL, 'Build PHP as thttpd module.'),
(1, 'misc', '--with-tsrm-pthreads', 'with', NULL, 'Use POSIX threads (default).'),
(1, 'sapi', '--with-tux=MODULEDIR', 'with', NULL, 'Build PHP as a TUX module (Linux only).'),
(1, 'sapi', '--with-webjames=SRCDIR', 'with', NULL, 'Build PHP as a WebJames module (RISC OS only)'),
(1, 'misc', '--with-zlib-dir[=DIR]', 'with', NULL, 'Define the location of zlib install directory.'),
(1, 'misc', '--without-pear', 'with', NULL, 'Do not install PEAR.');

--
-- Contenu de la table `language_php_ext`
--

INSERT INTO `language_php_ext` (`id`, `name`, `type`, `package`, `description`) VALUES
(1, 'eaccelerator', 'extension', 'eaccelerator', 'Eaccelerator extension module'),
(21, '', 'extension', 'php5-geoip', 'GeoIP module for php5'),
(22, '', 'extension', 'php5-imagick', 'ImageMagick module for php5'),
(23, 'imap', 'extension', 'php5-imap', 'IMAP module for php5'),
(24, '', 'extension', 'php5-interbase', 'interbase/firebird module for php5'),
(25, '', 'extension', 'php5-mapscript', 'php5-cgi module for MapServer'),
(26, 'mcrypt', 'extension', 'php5-mcrypt', 'MCrypt module for php5'),
(27, '', 'extension', 'php5-memcache', 'memcache extension module for PHP5'),
(28, 'memcached', 'extension', 'php5-memcached', 'memcached extension module for PHP5'),
(29, '', 'extension', 'php5-ming', 'Ming module for php5'),
(30, '', 'extension', 'php5-suhosin', 'advanced protection module for php5'),
(31, '', 'extension', 'php5-uuid', 'OSSP uuid module for php5'),
(32, 'curl', 'extension', 'php5-curl', 'CURL module for php5'),
(33, 'gd', 'extension', 'php5-gd', 'GD module for php5'),
(34, '', 'extension', 'php5-gmp', 'GMP module for php5'),
(35, 'ldap', 'extension', 'php5-ldap', 'LDAP module for php5'),
(36, 'mysql', 'extension', 'php5-mysql', 'MySQL module for php5'),
(37, '', 'extension', 'php5-odbc', 'ODBC module for php5'),
(38, '', 'extension', 'php5-pgsql', 'PostgreSQL module for php5'),
(39, '', 'extension', 'php5-pspell', 'pspell module for php5'),
(40, '', 'extension', 'php5-recode', 'recode module for php5'),
(41, '', 'extension', 'php5-snmp', 'SNMP module for php5'),
(42, 'sqlite', 'extension', 'php5-sqlite', 'SQLite module for php5'),
(43, '', 'extension', 'php5-sybase', 'Sybase / MS SQL Server module for php5'),
(44, '', 'extension', 'php5-tidy', 'tidy module for php5'),
(45, '', 'extension', 'php5-xmlrpc', 'XML-RPC module for php5'),
(46, '', 'extension', 'php5-xsl', 'XSL module for php5'),
(47, '', 'extension', 'php5-enchant', 'Enchant module for php5'),
(48, '', 'extension', 'php5-intl', 'internationalisation module for php5'),
(49, '', 'extension', 'php5-ps', 'ps module for PHP 5'),
(50, '', 'pecl', 'php5-radius', 'PECL radius module for PHP 5'),
(51, '', 'pecl', 'php5-remctl', 'PECL module for Kerberos-authenticated command execution'),
(52, '', 'extension', 'php5-xdebug', 'Xdebug Module for PHP 5'),
(53, 'MurmurPHP', 'extension', '', 'Murmure extension'),
(54, 'IcePHP', 'extension', '', 'Ice extension');

--
-- Contenu de la table `language_php_functions`
--

INSERT INTO `language_php_functions` (`id`, `name`, `security`, `description`) VALUES
(1, 'apache_get_modules', 'info', ''),
(2, 'apache_get_version', 'info', ''),
(3, 'apache_getenv', 'info', ''),
(4, 'apache_note', 'proc', ''),
(5, 'apache_setenv', 'info', ''),
(6, 'disk_free_space', 'proc', ''),
(7, 'diskfreespace', 'proc', ''),
(8, 'highlight_file', 'proc', ''),
(9, 'ini_alter', 'info', ''),
(10, 'ini_restore', 'info', ''),
(11, 'openlog', 'proc', ''),
(12, 'passthru', 'proc', ''),
(13, 'phpinfo', 'info', ''),
(14, 'proc_nice', 'proc', ''),
(15, 'shell_exec', 'proc', ''),
(16, 'show_source', 'proc', ''),
(17, 'symlink', 'proc', ''),
(18, 'system', 'proc', ''),
(19, 'exec', 'proc', ''),
(20, 'proc_open', 'proc', ''),
(21, 'parse_ini_file', 'info', ''),
(22, 'popen', 'proc', ''),
(23, 'curl_exec', 'proc', ''),
(24, 'curl_multi_exec', 'proc', ''),
(25, 'fsockopen', 'proc', ''),
(26, 'set_time_limit', 'info', ''),
(27, 'proc_close', 'proc', ''),
(28, 'proc_get_status', 'proc', ''),
(29, 'proc_terminate', 'proc', ''),
(30, 'stat', 'basedir', ''),
(31, 'lstat', 'basedir', ''),
(32, 'fileatime', 'basedir', ''),
(33, 'filectime', 'basedir', ''),
(34, 'filegroup', 'basedir', ''),
(35, 'fileinode', 'basedir', ''),
(36, 'filemtime', 'basedir', ''),
(37, 'fileowner', 'basedir', ''),
(38, 'fileperms', 'basedir', ''),
(39, 'filesize', 'basedir', ''),
(40, 'filetype', 'basedir', '');
