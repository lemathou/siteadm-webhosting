<?php

require_once "config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "mysql";

// ACTIONS

if (isset($_POST["_db_add"]))
{
	$db = new db();
	$db->insert($_POST);
	if ($db->id)
		$_GET["id"] = $db->id;
}
if (isset($_POST["_db_update"]) && isset($_POST["id"]) && ($db=db($_POST["id"])))
{
	$db->update($_POST);
}
if (isset($_POST["_action"]) && isset($_POST["_list_id"]) && is_array($_POST["_list_id"]))
{
	foreach($_POST["_list_id"] as $id)
		if (($object=db($id)))
		{
			if ($_POST["_action"] == "delete")
				$db->delete();
		}
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

// Context
if (login()->perm("manager"))
{
	include "template/inc/account_select.tpl.php";
}
else
{
	$account = login();
}

// Submenu
if (isset($account) || login()->perm("admin"))
	echo "<p><a href=\"?account_id=$account->id&list\">Liste</a> | <a href=\"?account_id=$account->id&add\">Ajouter</a></p> <hr />";
	
if (isset($_GET["id"]) && ($db=db($_GET["id"])) && $db->update_perm())
{
	include "template/page/db_id.tpl.php";
}
elseif (isset($_GET["add"]) && db::insert_perm())
{
	include "template/page/db_add.tpl.php";
}
elseif (isset($_GET["account_id"]) && ($account=account($_GET["account_id"])) && $account->update_perm())
{
	include "template/page/db_account.tpl.php";
}
elseif (login()->perm("admin"))
{
	include "template/page/db_admin.tpl.php";
}

// Admin
if (login()->perm("admin")) {
?>
<hr />
<form>
<p>
	<input type="button" value="Redémarrer MySQL" onclick="mysql_restart()" />
	<input type="button" value="Recharger MySQL" onclick="mysql_reload()" />
	<input type="button" value="Recharger les privilèges MySQL" onclick="mysql_privileges_reload()" />
</p>
</form>
<?php } ?>
</body>

</html>
