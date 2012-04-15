<?php

require_once "../config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "account";

// ACTION

if (isset($_POST["_account_add"]))
{
	$account = new account();
	$account->insert($_POST);
}
if (isset($_POST["_account_update"]) && isset($_POST["id"]) && ($account=account($id=$_POST["id"])))
{
	$account->update($_POST);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
</head>

<body>
<?php

require_once "template/inc/menu.tpl.php";

// Contexte sélection manager
if (login()->perm("admin"))
	include "template/inc/manager_select.tpl.php";

// Sous-menu
if (login()->perm("admin") && isset($manager))
	echo "<p><a href=\"?manager_id=$manager->id&list\">Liste</a> | <a href=\"?manager_id=$manager->id&add\">Ajouter</a></p> <hr />";
elseif (login()->perm("manager"))
	echo "<p><a href=\"?list\">Liste</a> | <a href=\"?add\">Ajouter</a></p> <hr />";

if (isset($_GET["add"]) && account::insert_perm())
	include "template/page/account_add.tpl.php";
elseif (isset($_GET["id"]) && ($account=account($id=$_GET["id"])) && $account->update_perm())
	include "template/page/account_id.tpl.php";
elseif (login()->perm("admin"))
	include "template/page/account_admin.tpl.php";
elseif (login()->perm("manager"))
	include "template/page/account_manager.tpl.php";
else
	include "template/page/account_user_id.tpl.php";

?>
</body>

</html>
