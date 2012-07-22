#! /bin/sh

prefix="/usr";
exec_prefix=${prefix};

php_fpm_BIN="${exec_prefix}/sbin/php-fpm";
php_fpm_FOLDER="/home/siteadm_admin/conf/php";
php_fpm_CONF="$php_fpm_FOLDER/php-fpm-siteadm.conf";
php_fpm_PID="$php_fpm_FOLDER/php-siteadm.pid";
php_fpm_INI="$php_fpm_FOLDER/php-siteadm.ini";
PHP_INI_SCAN_DIR="$php_fpm_FOLDER/ext";

php_opts="--fpm-config $php_fpm_CONF -c $php_fpm_INI"

export PHP_INI_SCAN_DIR;

. /home/siteadm_admin/scripts/php-fpm-init.sh
