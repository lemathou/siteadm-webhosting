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

--
-- Admin User
--

CREATE USER '{MYSQL_ADMIN_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{MYSQL_ADMIN_PASS}';
GRANT USAGE ON *.* TO '{MYSQL_ADMIN_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{MYSQL_ADMIN_PASS}' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON *.* TO '{MYSQL_ADMIN_USER}'@'{MYSQL_HOST}';

--
-- User
--

CREATE USER '{MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{MYSQL_PASS}';
GRANT USAGE ON `{MYSQL_DB}`.* TO '{MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{MYSQL_PASS}' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `{MYSQL_DB}` . * TO '{MYSQL_USER}'@'{MYSQL_HOST}';

--
-- Dovecot User
--

CREATE USER '{DOVECOT_MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{DOVECOT_MYSQL_PASS}';
GRANT USAGE ON `{MYSQL_DB}`.* TO '{DOVECOT_MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{DOVECOT_MYSQL_PASS}' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `{MYSQL_DB}`.`dovecot_email` TO '{DOVECOT_MYSQL_USER}'@'{MYSQL_HOST}';

--
-- Proftpd User
--

CREATE USER '{PROFTPD_MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{PROFTPD_MYSQL_PASS}';
GRANT USAGE ON `{MYSQL_DB}`.* TO '{PROFTPD_MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{PROFTPD_MYSQL_PASS}' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `{MYSQL_DB}`.`proftpd_user` TO '{PROFTPD_MYSQL_USER}'@'{MYSQL_HOST}';

--
-- Postfix User
--

CREATE USER '{POSTFIX_MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{POSTFIX_MYSQL_PASS}';
GRANT USAGE ON `{MYSQL_DB}`.* TO '{POSTFIX_MYSQL_USER}'@'{MYSQL_HOST}' IDENTIFIED BY '{POSTFIX_MYSQL_PASS}' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `{MYSQL_DB}`.`postfix_alias` TO '{POSTFIX_MYSQL_USER}'@'{MYSQL_HOST}';
GRANT SELECT ON `{MYSQL_DB}`.`postfix_domain` TO '{POSTFIX_MYSQL_USER}'@'{MYSQL_HOST}';
GRANT SELECT ON `{MYSQL_DB}`.`postfix_mbox` TO '{POSTFIX_MYSQL_USER}'@'{MYSQL_HOST}';
GRANT SELECT ON `{MYSQL_DB}`.`postfix_redirect` TO '{POSTFIX_MYSQL_USER}'@'{MYSQL_HOST}';

USE `{MYSQL_DB}`;

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

