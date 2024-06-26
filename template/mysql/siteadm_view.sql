-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 20 Avril 2012 à 21:19
-- Version du serveur: 5.1.61
-- Version de PHP: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

USE `{MYSQL_DB}`;

--
-- Structure de la vue `dovecot_email`
--
DROP TABLE IF EXISTS `dovecot_email`;
DROP VIEW IF EXISTS `dovecot_email`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dovecot_email` AS select if (`account_email`.`id` is null, if (`account`.`id` is null,0, `account`.`id`), `account_email`.`id`) AS `account_id`,if (`account_email`.`name` is null, if (`account`.`name` is null, 'common', `account`.`name`), `account_email`.`name`) AS `account_name`,((`account`.`actif` is null OR `account`.`actif`) and `email`.`actif`) AS `actif`,`email`.`name` AS `email_name`,`domain`.`name` AS `domain_name`,concat(`email`.`name`,'@',`domain`.`name`) AS `email`,`email`.`password` as `password`,md5(`email`.`password`) AS `password_md5`,ENCRYPT(`email`.`password`) AS `password_crypt`,(if (`account_email`.`id` is null, if (`account`.`id` is null, 0, `account`.`id`), `account_email`.`id`) + {EMAIL_UID_MIN}) AS `uid`,(if (`account_email`.`id` is null, if (`account`.`id` is null, 0, `account`.`id`), `account_email`.`id`) + {ACCOUNT_UID_MIN}) AS `gid`,concat('/home/siteadm/',if (`account_email`.`folder` is null, if (`account`.`folder` is null,'common', `account`.`folder`), `account_email`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `home`,concat('maildir:/home/siteadm/',if (`account_email`.`folder` is null, if (`account`.`folder` IS NULL, 'common', `account`.`folder`), `account_email`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `mail`, concat('*:storage=', `email`.`quota`, 'MB') as quota_rule from `email` join `domain` on `domain`.`id` = `email`.`domain_id` left join `account` on `account`.`id` = `domain`.`account_id` LEFT JOIN `account` AS `account_email` ON `account_email`.`id`=`email`.`account_id`;

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_alias`
--
DROP TABLE IF EXISTS `postfix_alias`;
DROP VIEW IF EXISTS `postfix_alias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_alias` AS select if (`t5`.`id` is null, 0, `t5`.`id`) AS `account_id`, if(`t5`.`name` is null, 'common', `t5`.`name`) AS `account_name`,concat(`t1`.`name`,'@',`t3`.`name`) AS `destination`,if (`t4`.`name` is null, `t2`.`name`, concat(`t2`.`name`,'@',`t4`.`name`)) AS `origine`,((`t5`.`actif` is null OR `t5`.`actif`) and `t2`.`actif` and (`t4`.`email_actif` is null OR `t4`.`email_actif`)) AS `actif` from ((((`email` `t1` join `email_alias` `t2` on((`t1`.`id` = `t2`.`email_id`))) join `domain` `t3` on((`t1`.`domain_id` = `t3`.`id`))) left join `domain` `t4` on((`t2`.`domain_id` = `t4`.`id`))) left join `account` `t5` on((`t5`.`id` = `t3`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_domain`
--
DROP TABLE IF EXISTS `postfix_domain`;
DROP VIEW IF EXISTS `postfix_domain`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_domain` AS select if (`t2`.`id` is null, 0, `t2`.`id`) AS `account_id`,if (`t2`.`name` is null, 'common',`t2`.`name`) AS `account_name`,`t1`.`name` AS `name`,((`t2`.`actif` is null OR `t2`.`actif`) and `t1`.`email_actif`) AS `actif` from (`domain` `t1` left join `account` `t2` on((`t2`.`id` = `t1`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_mbox`
--
DROP TABLE IF EXISTS `postfix_mbox`;
DROP VIEW IF EXISTS `postfix_mbox`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_mbox` AS select if (`account`.`id` is null, 0, `account`.`id`) AS `account_id`, if (`account`.`name` is null, 'common', `account`.`name`) AS `account_name`,({EMAIL_UID_MIN} + if (`account`.`id` is null, 0, `account`.`id`)) AS `uid`,({ACCOUNT_UID_MIN} + if (`account`.`id` is null, 0, `account`.`id`)) AS `gid`,concat(`email`.`name`,'@',`domain`.`name`) AS `email`,concat(if(`account`.`folder` is null, 'common', `account`.`folder`),'/mail/',`email`.`name`,'@',`domain`.`name`,'/') AS `maildir`,((`email`.`actif` = '1') and `domain`.`email_actif` and (`account`.`actif` is null OR `account`.`actif`)) AS `actif` from ((`email` join `domain` on((`domain`.`id` = `email`.`domain_id`))) left join `account` on((`account`.`id` = `domain`.`account_id`)));

-- --------------------------------------------------------

--
-- Structure de la vue `postfix_redirect`
--
DROP TABLE IF EXISTS `postfix_redirect`;
DROP VIEW IF EXISTS `postfix_redirect`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `postfix_redirect` AS select if (`t5`.`id` is null, 0, `t5`.`id`) AS `account_id`,if (`t5`.`name` is null, 'common', `t5`.`name`) AS `account_name`,`t2`.`redirect_email` AS `destination`,concat(`t2`.`name`,'@',`t3`.`name`) AS `origine`,(`t2`.`actif` and `t3`.`email_actif` and (`t5`.`actif` is null OR `t5`.`actif`)) AS `actif` from ((`email_alias` `t2` join `domain` `t3` on((`t3`.`id` = `t2`.`domain_id`))) left join `account` `t5` on((`t5`.`id` = `t3`.`account_id`))) where ((`t2`.`redirect_email` <> '') and (`t2`.`redirect_email` is not null));

-- --------------------------------------------------------

--
-- Structure de la vue `proftpd_user`
--
DROP TABLE IF EXISTS `proftpd_user`;
DROP VIEW IF EXISTS `proftpd_user`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `proftpd_user` AS select if (`account`.`id` IS NULL, 0, `account`.`id`) AS `account_id`,((`account`.`actif` OR `account`.`actif` IS NULL) and `ftp_user`.`actif`) AS `actif`,`ftp_user`.`id` AS `id`,CONCAT(if(`account`.`name` is null, 'common', `account`.`name`), '_', `ftp_user`.`username`) AS `username`,encrypt(`ftp_user`.`password`) AS `password`,({ACCOUNT_UID_MIN} + if(`account`.`id` IS NULL, 0, `account`.`id`)) AS `uid`,({ACCOUNT_UID_MIN} + if(`account`.`id` IS NULL, 0, `account`.`id`)) AS `gid`,concat('/home/siteadm/',if(`account`.`id` IS NULL, 'common', `account`.`folder`),'/',`ftp_user`.`type`,'/',`ftp_user`.`folder`) AS `folder`,'/bin/bash' AS `shell` from (`ftp_user` left join `account`  on((`account`.`id` = `ftp_user`.`account_id`)));
