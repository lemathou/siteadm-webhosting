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

--
-- Admin User
--

DROP user 'siteadm_root'@'localhost';

CREATE USER 'siteadm_root'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON *.* TO 'siteadm_root'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON *.* TO 'siteadm_root'@'localhost';

--
-- User
--

DROP user 'siteadm'@'localhost';

CREATE USER 'siteadm'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT ALL PRIVILEGES ON `siteadm` . * TO 'siteadm'@'localhost';

--
-- Dovecot User
--

DROP user 'siteadm_dovecot'@'localhost';

CREATE USER 'siteadm_dovecot'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm_dovecot'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `siteadm`.`dovecot_email` TO 'siteadm_dovecot'@'localhost';

--
-- Proftpd User
--

DROP user 'siteadm_proftpd'@'localhost';

CREATE USER 'siteadm_proftpd'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm_proftpd'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `siteadm`.`proftpd_user` TO 'siteadm_proftpd'@'localhost';

--
-- Postfix User
--

DROP user 'siteadm_postfix'@'localhost';

CREATE USER 'siteadm_postfix'@'localhost' IDENTIFIED BY 'siteadm2275';
GRANT USAGE ON `siteadm`.* TO 'siteadm_postfix'@'localhost' IDENTIFIED BY 'siteadm2275' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT ON `siteadm`.`postfix_alias` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_domain` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_mbox` TO 'siteadm_postfix'@'localhost';
GRANT SELECT ON `siteadm`.`postfix_redirect` TO 'siteadm_postfix'@'localhost';
