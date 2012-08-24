<?php

require_once "config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common_logged.inc.php";

if (isset($_GET["apache_reload"]))
{

apache_reload();

}

if (isset($_GET["postfix_reload"]))
{

postfix_reload();

}

if (isset($_GET["mysql_reload"]))
{

mysql_reload();

}

?>
