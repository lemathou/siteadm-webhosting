<?php

require_once "include/common.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "php";

// ACTIONS

// PHP App
if (isset($_POST["_phpapp_add"]))
{
	$phpapp = new phpapp();
	$phpapp->insert($_POST);
}
if (isset($_POST["_phpapp_update"]) && isset($_POST["id"]) && ($phpapp=phpapp($_POST["id"])))
{
	$phpapp->update($_POST);
}
// PHP Pool
if (isset($_POST["_phppool_add"]))
{
	$phppool = new phppool();
	$phppool->insert($_POST);
}
if (isset($_POST["_phppool_update"]) && isset($_POST["id"]) && ($phppool=phppool($_POST["id"])))
{
	$phppool->update($_POST);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
</head>

<body>
<?php

include "template/inc/menu.tpl.php";

// Contexte de compte
if (login()->perm("admin"))
{
	include "template/inc/account_select.tpl.php";
	if (isset($account))
	{
		$query_phpapp_where = "WHERE t1.account_id=$account->id";
		$query_phppool_where = "WHERE t1.account_id=$account->id";
	}
	else
	{
		$query_phpapp_where = "";
		$query_phppool_where = "";
	}
}
elseif (login()->perm("manager"))
{
	include "template/inc/account_select.tpl.php";
	if (isset($account))
	{
		$query_phpapp_where = "WHERE t1.account_id=$account->id";
		$query_phppool_where = "WHERE t1.account_id=$account->id";
	}
	else
	{
		$query_phpapp_where = "WHERE t1.account_id=0 OR t4.id = ".login()->id." OR t4.manager_id = ".login()->id;
		$query_phppool_where = "WHERE t4.id = ".login()->id." OR t4.manager_id = ".login()->id;
	}
}
else
{
	$account = login();
	$query_phpapp_where = "WHERE t1.account_id=$account->id";
	$query_phppool_where = "WHERE t1.account_id=$account->id";
}

// Context
if (isset($account))
	echo "<p><a href=\"?account_id=$account->id&list\">Liste</a> | <a href=\"?account_id=$account->id&app_add\">Ajouter un processus parent</a> | <a href=\"?account_id=$account->id&pool_add\">Ajouter un pool</a></p>";
else
	echo "<p><a href=\"?list\">Liste</a> | <a href=\"?app_add\">Ajouter un processus parent</a></p>";
?>
<hr />
<?php

if (isset($_GET["app_add"]) && phpapp::insert_perm())
{
	$phpapp = new phpapp();
	include "template/page/php_app_add.tpl.php";
}
elseif (isset($_GET["pool_add"]) && phppool::insert_perm())
{
	$phpool = new phppool();
	include "template/page/php_pool_add.tpl.php";
}
elseif (isset($_GET["pool_id"]) && ($phppool=phppool($_GET["pool_id"])) && $phppool->update_perm())
{
	include "template/page/php_pool_id.tpl.php";
}
elseif (isset($_GET["app_id"]) && ($phpapp=phpapp($_GET["app_id"])) && $phpapp->update_perm())
{
	include "template/page/php_app_id.tpl.php";
}
else
{
	include "template/page/php_app_list.tpl.php";
	include "template/page/php_pool_list.tpl.php";
}

?>
</body>

</html>
