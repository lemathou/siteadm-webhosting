<?php

require_once "../config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "ftp";

// ACTIONS

if (isset($_POST["_ftp_add"]) && isset($_POST["name"]))
{
	$ftp = new ftp();
	$ftp->db_insert($_POST);
}
if (isset($_POST["_ftp_update"]) && isset($_POST["id"]) && ($ftp=ftp($_POST["id"])))
{
	$ftp->db_update($_POST);
}
if (isset($_POST["ftp_del"]) && isset($_POST["id"]) && ($ftp=ftp($_POST["id"])))
{
	$ftp->db_delete();
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
if (isset($account))
	echo "<p><a href=\"?account_id=$account->id&list\">Liste</a> | <a href=\"?account_id=$account->id&add\">Ajouter</a></p> <hr />";

if (isset($_GET["id"]) && ($ftp=ftp($_GET["id"])) && $ftp->account()->id == login()->id)
	include "template/form/ftp.tpl.php";
else
	include "template/page/ftp_list.tpl.php";

?>
</body>

</html>
