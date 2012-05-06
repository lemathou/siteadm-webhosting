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
