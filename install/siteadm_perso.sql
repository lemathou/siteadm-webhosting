-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 07 Mai 2012 à 18:27
-- Version du serveur: 5.5.22
-- Version de PHP: 5.3.10-1ubuntu3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `siteadm`
--

USE `siteadm`;

--
-- Contenu de la table `offre`
--

INSERT INTO `offre` (`id`, `name`, `description`, `tarif`, `disk_quota_soft`, `disk_quota_hard`, `worker_max`, `worker_ram_max`, `mysql_db_max`, `domain_nb_max`, `website_nb_max`) VALUES
(1, 'Gratuit temporaire', '', 0.00, 10, 11, NULL, NULL, NULL, NULL, NULL),
(2, 'Gratuit permanent', '', 0.00, 10, 11, NULL, NULL, NULL, NULL, NULL),
(3, 'Hébergement 1 GO', 'Pack Perso', 5.00, 1, 2, NULL, NULL, NULL, NULL, NULL),
(4, 'Hébergement 5 GO', 'Pack PRO', 10.00, 5, 6, NULL, NULL, NULL, NULL, NULL),
(5, 'Hébergement 10 GO', 'Pack multi-domaines', 15.00, 10, 11, NULL, NULL, NULL, NULL, NULL),
(6, 'Hébergement 20 GO', 'Pack Large', 25.00, 20, 22, NULL, NULL, NULL, NULL, NULL),
(7, 'Hébergement 50 GO', 'Pack XXL', 40.00, 50, 55, NULL, NULL, NULL, NULL, NULL),
(8, 'Hébergement 100 GO', 'Pack Pinguin Killer', 75.00, 100, 110, NULL, NULL, NULL, NULL, NULL);

--
-- Contenu de la table `webapp`
--

INSERT INTO `webapp` (`id`, `_update`, `name`, `version`, `description`, `folder_alias`, `php_include_folder`, `php_open_basedir`, `php_short_open_tag`) VALUES
(1, NULL, 'FAD Framework', '', '', '{"/_js/":"/home/siteadm_include/framework/_js/","/_css/":"/home/siteadm_include/framework/_css/"}', '', '/home/siteadm_include/framework', 1),
(2, NULL, 'SPIP', '', '', NULL, '', '', NULL),
(3, NULL, 'Joomla', '', '', NULL, '', '', NULL),
(4, NULL, 'Dotclear', '', '', NULL, '', '', NULL),
(5, NULL, 'Wordpress', '', '', NULL, '', '', NULL),
(6, NULL, 'Drupal', '', '', NULL, '', '', NULL),
(7, NULL, 'phpBB', '', '', NULL, '', '', NULL),
(8, NULL, 'FAD CMS', '', '', NULL, '', '', NULL),
(9, NULL, 'dotproject', '', '', NULL, '', '', NULL),
(10, NULL, 'MediaWiki', '', '', NULL, '', '', NULL),
(11, NULL, 'Zend Framework', '', '', NULL, '', '', NULL),
(12, NULL, 'Symphony', '', '', NULL, '', '', NULL),
(13, NULL, 'WebStudioSI', '', '', NULL, '', '/home/webstudiosi_dev/php_include/:/home/webstudiosi_dev/public/admin-sp/', 1),
(14, NULL, 'eGroupware', '', '', NULL, '', '', NULL);

