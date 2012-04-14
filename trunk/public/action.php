<?php

require_once "include/common.inc.php";

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

