<?php

define("SCRIPT_DIR","/home/siteadm_admin/scripts");
define("INSTALL_DIR","/home/siteadm_admin/install");

function install_mysql($hostname, $password)
{

	exec("mysql -h".$hostname." -uroot -p".$password." < ".INSTALL_DIR."/sql/siteadm_tables.sql");
	exec("mysql -h".$hostname." -uroot -p".$password." < ".INSTALL_DIR."/sql/siteadm_views.sql");
	exec("mysql -h".$hostname." -uroot -p".$password." < ".INSTALL_DIR."/sql/siteadm_users.sql");
	exec("mysql -h".$hostname." -uroot -p".$password." < ".INSTALL_DIR."/sql/siteadm_data.sql");

}

function config_services()
{

	exec("sudo ".SCRIPT_DIR."/config.psh postfix");
	exec("sudo ".SCRIPT_DIR."/config.psh dovecot");
	exec("sudo ".SCRIPT_DIR."/config.psh common");
	exec("sudo ".SCRIPT_DIR."/db_object.psh account 1 insert");

}

?>