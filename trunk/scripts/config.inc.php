<?php

$replace_map = array(
		"{ACCOUNT_UID_MIN}" => ACCOUNT_UID_MIN,
		"{PHP_UID_MIN}" => PHP_UID_MIN,
		"{EMAIL_UID_MIN}" => EMAIL_UID_MIN,
		"{MYSQL_ADMIN_USER}" => MYSQL_ADMIN_USER,
		"{MYSQL_ADMIN_PASS}" => MYSQL_ADMIN_PASS,
		"{MYSQL_USER}" => MYSQL_USER,
		"{MYSQL_PASS}" => MYSQL_PASS,
		"{MYSQL_USER}" => MYSQL_USER,
		"{MYSQL_HOST}" => MYSQL_HOST,
		"{MYSQL_DB}" => MYSQL_DB,
		"{POSTFIX_MYSQL_USER}" => POSTFIX_MYSQL_USER,
		"{POSTFIX_MYSQL_PASS}" => POSTFIX_MYSQL_PASS,
		"{DOVECOT_MYSQL_USER}" => DOVECOT_MYSQL_USER,
		"{DOVECOT_MYSQL_PASS}" => DOVECOT_MYSQL_PASS,
		"{PROFTPD_MYSQL_USER}" => PROFTPD_MYSQL_USER,
		"{PROFTPD_MYSQL_PASS}" => PROFTPD_MYSQL_PASS,
		"{SMTP_RELAY_HOST}" => SMTP_RELAY_HOST,
		"{HOSTNAME}" => HOSTNAME,
		"{DOMAIN}" => DOMAIN,
);
replace_map_merge($replace_map, replace_map());

// MySQL First Install
if ($action == "mysql")
{

	$fp = opendir(SITEADM_TEMPLATE_DIR."/mysql");
	while($filename=readdir($fp)) if (substr($filename, 0, 1) != ".")
	{
		copy_tpl("mysql/$filename", "/home/siteadm_admin/install/sql/$filename", $replace_map, "0600", "root:root");
	}

}

// Common user
elseif ($action == "common")
{

	account_common()->script_insert();

}

// Postfix
elseif ($action == "postfix")
{

	exec("mkdir /etc/postfix/virtual");

	$fp = opendir(SITEADM_TEMPLATE_DIR."/postfix/virtual");
	while($filename=readdir($fp)) if (substr($filename, 0, 1) != ".")
	{
		copy_tpl("postfix/virtual/$filename", "/etc/postfix/virtual/$filename", $replace_map, "0600", "root:root");
	}
	copy_tpl("postfix/aliases", "/etc/postfix/aliases", $replace_map, "0644", "root:root");
	copy_tpl("postfix/mailname", "/etc/postfix/mailname", $replace_map, "0644", "root:root");
	copy_tpl("postfix/main.cf", "/etc/postfix/main.cf", $replace_map, "0644", "root:root");
	copy_tpl("postfix/master.cf", "/etc/postfix/master.cf", $replace_map, "0644", "root:root");

	exec("postfix reload");

}

// Dovecot
elseif ($action == "dovecot")
{

	copy_tpl("dovecot/dovecot-sql.conf", "/etc/dovecot/dovecot-sql.conf", $replace_map, "0600", "root:root");
	copy_tpl("dovecot/conf.d/10-auth.conf", "/etc/dovecot/conf.d/10-auth.conf", $replace_map, "0644", "root:root");
	copy_tpl("dovecot/conf.d/auth-sql.conf.ext", "/etc/dovecot/conf.d/auth-sql.conf.ext", $replace_map, "0644", "root:root");

	// @todo : scripts sieve

	exec("service dovecot restart");

}

// ProFTPd
elseif ($action == "proftpd")
{

	copy_tpl("proftpd/proftpd.conf", "/etc/proftpd/proftpd.conf", $replace_map, "0644", "root:root");
	copy_tpl("proftpd/sql.conf", "/etc/proftpd/sql.conf", $replace_map, "0600", "root:root");

	exec("service proftpd restart");

}

// ProFTPd
elseif ($action == "awstats")
{

	exec("cp /home/siteadm_admin/template/awstats/awstats.common /etc/awstats/awstats.common");

}

?>