<?php

require_once "config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "ftp";

// ACTIONS

if (isset($_POST["_insert"]))
{
	$ftp = new ftp();
	$ftp->insert($_POST);
	if ($ftp->id)
		$_GET["id"] = $ftp->id;
}
if (isset($_POST["_update"]) && isset($_POST["id"]) && ($ftp=ftp()->get($_POST["id"])))
{
	$ftp->update($_POST);
}
if (isset($_POST["_delete"]) && isset($_POST["id"]) && ($ftp=ftp()->get($_POST["id"])))
{
	$ftp->delete();
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
if (isset($_GET["id"]) && ($ftp=ftp($_GET["id"])) && ($ftp->update_perm()))
	$_GET["account_id"] = $ftp->account_id;

if (login()->perm("manager"))
{
	include "template/inc/account_select.tpl.php";
}
else
{
	$account = login();
}

// Submenu
if (isset($account))
	echo "<p><a href=\"?account_id=$account->id&list\">Liste</a> | <a href=\"?account_id=$account->id&add\">Ajouter</a></p>";

if (isset($_GET["id"]) && ($ftp=ftp($_GET["id"])) && ($ftp->update_perm()))
{
	include "template/form/ftp.tpl.php";
}
elseif (isset($_GET["add"]) && ftp::insert_perm())
{
	if (!isset($account) || !$account)
		$account = login();
	$ftp = new ftp();
	$ftp->account_id = $account->id;
	include "template/form/ftp.tpl.php";
}
else
{
	include "template/page/ftp_list.tpl.php";
}

?>
</body>

</html>
