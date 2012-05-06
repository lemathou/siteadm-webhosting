<?php

// Siteadm MySQL Account
define("MYSQL_HOST","localhost");
define("MYSQL_USER","siteadm");
define("MYSQL_PASS","siteadm2275");
define("MYSQL_DB","siteadm");
// Superutilisateur pour opérations de maintenance
define("MYSQL_ADMIN_USER","siteadm_root");
define("MYSQL_ADMIN_PASS","siteadm2275");

define("HOSTNAME", "mathieu-portable");

define("INIT_SCRIPT_DIR", "/etc/init.d");

// Root email
define("ROOT_EMAIL","root@".HOSTNAME);
// Postmaster email
define("POSTMASTER_EMAIL","postmaster@".HOSTNAME);
// Webmaster email
define("WEBMASTER_EMAIL","webmaster@".HOSTNAME);

// Admin Path
define("SITEADM_ADMIN_DIR", "/home/siteadm_admin");
define("SITEADM_TEMPLATE_DIR", SITEADM_ADMIN_DIR."/template");
define("SITEADM_PUBLIC_DIR", SITEADM_ADMIN_DIR."/public");
define("SITEADM_PRIVATE_DIR", SITEADM_ADMIN_DIR."/private");
define("SITEADM_SCRIPT_DIR", SITEADM_ADMIN_DIR."/scripts");

// Email dir
define("SITEADM_EMAIL_DIR","/home/siteadm_email");
// Website dir
define("SITEADM_WEBSITE_DIR","/home/siteadm_website");
// DOMAIN dir
define("SITEADM_DOMAIN_DIR","/home/siteadm_domain");
// USER_DIR
define("SITEADM_USER_DIR","/home/siteadm");

// Common/Shared email
define("SHARED_EMAIL","webmaster@iprospective.fr");

// System
define("SITEADM_SYSTEM_USER","siteadm");
define("SITEADM_SYSTEM_UID",502);
define("SITEADM_SYSTEM_GROUP","siteadm");
define("SITEADM_SYSTEM_GID",502);

// Account
define("ACCOUNT_SYSTEM_GROUP","siteadm_account");
define("ACCOUNT_SYSTEM_GID",503);
define("ACCOUNT_UID_MIN",2000);

// Webserver
define("WEBSERVER_USER","www-data");
define("WEBSERVER_GROUP","www-data");
// Apache
define("APACHE_VHOST_DIR","/etc/apache2/sites-siteadm");
define("APACHE_SSL_CERT","server");
define("APACHE_PUBLIC_WEBMASTER_EMAIL","webmaster@iprospective.fr");

// AWSTATS
define("AWSTATS_CONFIG_DIR","/etc/awstats");

// CGI
define("CGI_SPAWN_EXEC","/usr/bin/spawn-fcgi");

// PHP Installation
define("PHP_INSTALL_PREFIX","/opt/php5");
define("PHP_LIB_FOLDER","/etc/php5/conf.d");
define("PHP_EXT_FOLDER","/etc/php5/conf.d");
define("PHP_DEFAULT_SYSTEM_USER", "nobody");
define("PHP_UID_MIN", 3000);
define("PHP_DEFAULT_EXEC", "/usr/bin/php5-cgi");
// PHP Compilation
// PHP CGI & FPM
define("PHP_WORKER_NB_MAX",5);
define("PHP_WORKER_MAX_REQUESTS",500);
// PHP INI
define("PHP_ERROR_REPORTING","E_ALL & ~E_NOTICE & ~E_DEPRECATED");
define("PHP_ERROR_DISPLAY",false);
define("PHP_ERROR_FILESAVE",true);
define("PHP_ERROR_LOG","php_errors.log");
define("PHP_MAX_EXECUTION_TIME",30);
define("PHP_MAX_INPUT_TIME",60);
define("PHP_MEMORY_LIMIT",64);
define("PHP_POST_MAX_SIZE",8);
define("PHP_FILE_UPLOADS",true); // On, Off
define("PHP_UPLOAD_MAX_FILESIZE",4); // MO
define("PHP_MAX_FILE_UPLOAD",5);
define("PHP_ALLOW_URL_FOPEN",false); // On, Off
define("PHP_INCLUDE_PATH",".");
define("PHP_SHORT_OPEN_TAG",false); // Deprecated
define("PHP_APC_STAT",true); // On, Off
define("PHP_APC_LAZY",false); // On, Off

// MySQL
define("MYSQL_CONFIG","/etc/mysql");
define("MYSQL_CONFIGFILE","my.cnf");
define("MYSQL_DUMP","mysqldump");
define("MYSQL_MAX_QUERIES","1000");
define("MYSQL_MAX_USER_CONNECTIONS","10");
define("MYSQL_MAX_CONNECTIONS","1000");
define("MYSQL_MAX_UPDATES","1000");

// Postfix
define("POSTFIX_MYSQL_USER","siteadm_postfix");
define("POSTFIX_MYSQL_PASS","siteadm2275");

// Dovecot
define("DOVECOT_MYSQL_USER","siteadm_dovecot");
define("DOVECOT_MYSQL_PASS","siteadm2275");

// AMAVIS
define("AMAVIS_CONFIG","/etc/amavis-d/conf");
// ClamAV
define("CLAMV_CONFIG","/etc/clamav/conf");
// Spamassassin
define("SPAMASSASSIN_CONFIG","/etc/spamassassin/conf");

// ProFTPd
define("PROFTPD_MYSQL_USER","siteadm_proftpd");
define("PROFTPD_MYSQL_PASS","siteadm2275");
define("PROFTPD_CONFIG","/etc/proftpd.conf/vhost");

// Bind
define("BIND_CONFIG","/etc/bind9");
define("BIND_DOMAIN_DIR","/etc/bind9/pri");

// Quota
define("QUOTA_EXEC","quota");

// Logrotate
define("LOGROTATE_CONFIG","/etc/logrotate.d");

// CRON
define("CRON_CONFIG","/etc/cron.d");
define("CRON_CONFIG_HOURLY","/etc/cron.hourly");
define("CRON_CONFIG_DAILY","/etc/cron.daily");
define("CRON_CONFIG_WEEKLY","/etc/cron.weekly");
define("CRON_CONFIG_MONTHLY","/etc/cron.monthly");

?>