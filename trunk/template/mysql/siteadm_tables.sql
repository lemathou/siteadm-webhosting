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

CREATE DATABASE IF NOT EXISTS {MYSQL_DB};

USE `{MYSQL_DB}`;

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `manager_id` int(10) unsigned DEFAULT NULL,
  `type` enum('user','manager','admin') NOT NULL DEFAULT 'user',
  `offre_id` int(10) unsigned DEFAULT NULL,
  `offre_expire` date DEFAULT NULL,
  `folder` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(16) NOT NULL,
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
  KEY `language_bin_id` (`language_bin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `account_log`
--

CREATE TABLE IF NOT EXISTS `account_log` (
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_id` int(10) unsigned DEFAULT NULL,
  `operation` enum('connect_ok','connect_error') DEFAULT NULL,
  `ip` varchar(16) NOT NULL,
  `details` text NOT NULL,
  KEY `datetime` (`datetime`),
  KEY `account_id` (`account_id`),
  KEY `ip` (`ip`),
  KEY `operation` (`operation`)
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
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
`account_id` decimal(10,0)
,`account_name` varchar(32)
,`actif` int(1)
,`email_name` varchar(64)
,`domain_name` varchar(128)
,`email` varchar(193)
,`password` varchar(32)
,`uid` decimal(11,0)
,`gid` decimal(11,0)
,`home` varchar(246)
,`mail` varchar(254)
);
-- --------------------------------------------------------

--
-- Structure de la table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actif` enum('0','1') NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `domain_id` int(10) unsigned NOT NULL,
  `password` varchar(64) NOT NULL,
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
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(64) NOT NULL,
  `domain_id` int(10) unsigned DEFAULT NULL,
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
  `type` enum('private','public') NOT NULL DEFAULT 'public',
  `folder` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_id` (`account_id`,`username`)
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
  `extension_dir` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `language_bin_php_ext_ref`
--

CREATE TABLE IF NOT EXISTS `language_bin_php_ext_ref` (
  `language_bin_id` int(10) unsigned NOT NULL,
  `ext_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`language_bin_id`,`ext_id`),
  KEY `ext_id` (`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `phpapp_ext_ref`
--

CREATE TABLE IF NOT EXISTS `phpapp_ext_ref` (
  `phpapp_id` int(10) unsigned NOT NULL,
  `ext_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`phpapp_id`,`ext_id`),
  KEY `ext_id` (`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `phppool`
--

CREATE TABLE IF NOT EXISTS `phppool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int(10) unsigned DEFAULT NULL,
  `system_user` varchar(32) DEFAULT NULL,
  `system_group` varchar(32) DEFAULT NULL,
  `phpapp_id` int(10) unsigned DEFAULT NULL,
  `language_bin_id` int(10) unsigned DEFAULT NULL,
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
  `file_uploads` tinyint(1) NOT NULL DEFAULT '1',
  `upload_max_filesize` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `max_file_upload` tinyint(2) unsigned NOT NULL DEFAULT '5',
  `error_reporting` varchar(64) NOT NULL DEFAULT 'E_ALL & ~E_DEPRECATED',
  `include_path` text,
  `open_basedir` text,
  `short_open_tag` tinyint(1) NOT NULL DEFAULT '0',
  `apc_stat` tinyint(1) NOT NULL,
  `apc_lazy` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain_id_2` (`account_id`,`name`),
  KEY `language_bin_id` (`language_bin_id`),
  KEY `phpapp_id` (`phpapp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
`account_id` decimal(10,0)
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
`account_id` decimal(10,0)
,`account_name` varchar(32)
,`name` varchar(128)
,`actif` int(1)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `postfix_mbox`
--
CREATE TABLE IF NOT EXISTS `postfix_mbox` (
`account_id` decimal(10,0)
,`account_name` varchar(32)
,`uid` decimal(11,0)
,`gid` decimal(11,0)
,`email` varchar(193)
,`maildir` varchar(232)
,`actif` int(1)
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `postfix_redirect`
--
CREATE TABLE IF NOT EXISTS `postfix_redirect` (
`account_id` decimal(10,0)
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
`account_id` decimal(10,0)
,`actif` int(1)
,`id` int(10) unsigned
,`username` varchar(97)
,`password` varbinary(13)
,`uid` decimal(11,0)
,`gid` decimal(11,0)
,`folder` varchar(183)
,`shell` varchar(9)
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
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
  `php_engine` tinyint(1) DEFAULT '1',
  `php_expose` enum('On','Off') DEFAULT 'Off',
  `php_short_open_tag` tinyint(1) DEFAULT NULL,
  `php_max_execution_time` tinyint(3) unsigned DEFAULT '30',
  `php_max_input_time` tinyint(3) unsigned DEFAULT '60',
  `php_memory_limit` tinyint(3) unsigned DEFAULT '32',
  `php_post_max_size` tinyint(3) unsigned DEFAULT '8',
  `php_file_uploads` enum('On','Off') DEFAULT 'On',
  `php_upload_max_filesize` tinyint(3) unsigned DEFAULT '2',
  `php_max_file_upload` tinyint(2) unsigned DEFAULT '5',
  `php_error_reporting` varchar(64) DEFAULT 'E_ALL & ~E_DEPRECATED',
  `php_enable_dl` enum('On','Off') DEFAULT 'Off',
  `php_include_path` varchar(256) DEFAULT NULL,
  `php_open_basedir` varchar(64) DEFAULT NULL,
  `php_apc_stat` tinyint(1) DEFAULT '0',
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
  `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `alias_name` varchar(64) NOT NULL,
  `domain_id` int(10) unsigned DEFAULT NULL,
  `website_id` int(10) unsigned DEFAULT NULL,
  `website_redirect` tinyint(1) NOT NULL DEFAULT '0',
  `redirect_url` varchar(128) DEFAULT NULL,
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dovecot_email` AS select if(isnull(`account`.`id`),0,`account`.`id`) AS `account_id`,if(isnull(`account`.`name`),'common',`account`.`name`) AS `account_name`,((isnull(`account`.`actif`) or `account`.`actif`) and `email`.`actif` and `domain`.`email_actif`) AS `actif`,`email`.`name` AS `email_name`,`domain`.`name` AS `domain_name`,concat(`email`.`name`,'@',`domain`.`name`) AS `email`,md5(`email`.`password`) AS `password`,(if(isnull(`account`.`id`),0,`account`.`id`) + 4000) AS `uid`,(if(isnull(`account`.`id`),0,`account`.`id`) + 2000) AS `gid`,concat('/home/siteadm/',if(isnull(`account`.`folder`),'common',`account`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `home`,concat('maildir:/home/siteadm/',if(isnull(`account`.`folder`),'common',`account`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `mail` from ((`email` join `domain` on((`domain`.`id` = `email`.`domain_id`))) left join `account` on((`account`.`id` = `domain`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_alias`
--
DROP TABLE IF EXISTS `postfix_alias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_alias` AS select if(isnull(`t5`.`id`),0,`t5`.`id`) AS `account_id`,if(isnull(`t5`.`name`),'common',`t5`.`name`) AS `account_name`,concat(`t1`.`name`,'@',`t3`.`name`) AS `destination`,if(isnull(`t4`.`name`),`t2`.`name`,concat(`t2`.`name`,'@',`t4`.`name`)) AS `origine`,((isnull(`t5`.`actif`) or `t5`.`actif`) and `t2`.`actif` and (isnull(`t4`.`email_actif`) or `t4`.`email_actif`)) AS `actif` from ((((`email` `t1` join `email_alias` `t2` on((`t1`.`id` = `t2`.`email_id`))) join `domain` `t3` on((`t1`.`domain_id` = `t3`.`id`))) left join `domain` `t4` on((`t2`.`domain_id` = `t4`.`id`))) left join `account` `t5` on((`t5`.`id` = `t3`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_domain`
--
DROP TABLE IF EXISTS `postfix_domain`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_domain` AS select if(isnull(`t2`.`id`),0,`t2`.`id`) AS `account_id`,if(isnull(`t2`.`name`),'common',`t2`.`name`) AS `account_name`,`t1`.`name` AS `name`,((isnull(`t2`.`actif`) or `t2`.`actif`) and `t1`.`email_actif`) AS `actif` from (`domain` `t1` left join `account` `t2` on((`t2`.`id` = `t1`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_mbox`
--
DROP TABLE IF EXISTS `postfix_mbox`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_mbox` AS select if(isnull(`account`.`id`),0,`account`.`id`) AS `account_id`,if(isnull(`account`.`name`),'common',`account`.`name`) AS `account_name`,(4000 + if(isnull(`account`.`id`),0,`account`.`id`)) AS `uid`,(2000 + if(isnull(`account`.`id`),0,`account`.`id`)) AS `gid`,concat(`email`.`name`,'@',`domain`.`name`) AS `email`,concat(if(isnull(`account`.`folder`),'common',`account`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `maildir`,((`email`.`actif` = '1') and `domain`.`email_actif` and (isnull(`account`.`actif`) or `account`.`actif`)) AS `actif` from ((`email` join `domain` on((`domain`.`id` = `email`.`domain_id`))) left join `account` on((`account`.`id` = `domain`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_redirect`
--
DROP TABLE IF EXISTS `postfix_redirect`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_redirect` AS select if(isnull(`t5`.`id`),0,`t5`.`id`) AS `account_id`,if(isnull(`t5`.`name`),'common',`t5`.`name`) AS `account_name`,`t2`.`redirect_email` AS `destination`,concat(`t2`.`name`,'@',`t3`.`name`) AS `origine`,(`t2`.`actif` and `t3`.`email_actif` and (isnull(`t5`.`actif`) or `t5`.`actif`)) AS `actif` from ((`email_alias` `t2` join `domain` `t3` on((`t3`.`id` = `t2`.`domain_id`))) left join `account` `t5` on((`t5`.`id` = `t3`.`account_id`))) where ((`t2`.`redirect_email` <> '') and (`t2`.`redirect_email` is not null));

-- --------------------------------------------------------

--
-- Structure de la vue `proftpd_user`
--
DROP TABLE IF EXISTS `proftpd_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `proftpd_user` AS select if(isnull(`account`.`id`),0,`account`.`id`) AS `account_id`,((`account`.`actif` or isnull(`account`.`actif`)) and `ftp_user`.`actif`) AS `actif`,`ftp_user`.`id` AS `id`,concat(if(isnull(`account`.`name`),'common',`account`.`name`),'_',`ftp_user`.`username`) AS `username`,encrypt(`ftp_user`.`password`) AS `password`,(2000 + if(isnull(`account`.`id`),0,`account`.`id`)) AS `uid`,(2000 + if(isnull(`account`.`id`),0,`account`.`id`)) AS `gid`,concat('/home/siteadm/',if(isnull(`account`.`id`),'common',`account`.`folder`),'/',`ftp_user`.`type`,'/',`ftp_user`.`folder`) AS `folder`,'/bin/bash' AS `shell` from (`ftp_user` left join `account` on((`account`.`id` = `ftp_user`.`account_id`)));

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
  ADD CONSTRAINT `account_language_bin_ref_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `account_language_bin_ref_ibfk_2` FOREIGN KEY (`language_bin_id`) REFERENCES `language_bin` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `ftp_user_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

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
-- Contraintes pour la table `language_bin_php_ext_ref`
--
ALTER TABLE `language_bin_php_ext_ref`
  ADD CONSTRAINT `language_bin_php_ext_ref_ibfk_3` FOREIGN KEY (`language_bin_id`) REFERENCES `language_bin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `language_bin_php_ext_ref_ibfk_4` FOREIGN KEY (`ext_id`) REFERENCES `language_php_ext` (`id`);

--
-- Contraintes pour la table `language_compile_options`
--
ALTER TABLE `language_compile_options`
  ADD CONSTRAINT `language_compile_options_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE CASCADE;

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
-- Contraintes pour la table `phpapp_ext_ref`
--
ALTER TABLE `phpapp_ext_ref`
  ADD CONSTRAINT `phpapp_ext_ref_ibfk_1` FOREIGN KEY (`phpapp_id`) REFERENCES `phpapp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phpapp_ext_ref_ibfk_2` FOREIGN KEY (`ext_id`) REFERENCES `language_php_ext` (`id`);

--
-- Contraintes pour la table `phppool`
--
ALTER TABLE `phppool`
  ADD CONSTRAINT `phppool_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `phppool_ibfk_3` FOREIGN KEY (`language_bin_id`) REFERENCES `language_bin` (`id`),
  ADD CONSTRAINT `phppool_ibfk_4` FOREIGN KEY (`phpapp_id`) REFERENCES `phpapp` (`id`);

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

