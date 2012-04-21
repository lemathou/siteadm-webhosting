<?php

require_once "config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "domain";

// ACTION

if (isset($_POST["_domain_add"]))
{
	$domain = new domain();
	$domain->insert($_POST);
}
if (isset($_POST["_domain_update"]) && isset($_POST["id"]) && ($domain=domain($_POST["id"])))
{
	$domain->update($_POST);
}
if (isset($_POST["_domain_action"]) && isset($_POST["_list_id"]) && is_array($_POST["_list_id"]))
{
	foreach($_POST["_list_id"] as $id)
		if (($object=domain($id)))
		{
			switch ($_POST["_domain_action"])
			{
			case "delete":
				$object->delete();
				break;
			case "email_activate":
				$object->update(array("email_actif"=>"1"));
				break;
			case "email_disable":
				$object->update(array("email_actif"=>"0"));
				break;
			}
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

require_once "template/inc/menu.tpl.php";

// Infos liées au compte sur le nombre de noms de domaine pris en charge

// Contexte de compte
if (login()->perm("admin"))
{
	include "template/inc/account_select.tpl.php";
	if (isset($account))
		$query_domain_where = "WHERE t1.account_id='$account->id'";
	else
		$query_domain_where = "";
}
elseif (login()->perm("manager"))
{
	include "template/inc/account_select.tpl.php";
	if (isset($account))
		$query_domain_where = "WHERE t1.account_id='$account->id'";
	else
		$query_domain_where = "WHERE t2.manager_id = ".login()->id." OR t2.id = ".login()->id;
}
else
{
	$account = login();
	$query_domain_where = "WHERE t1.account_id='$account->id'";
}

// Sous-menu
if (isset($account))
	echo "<p><a href=\"?account_id=$account->id&list\">Liste</a> | <a href=\"?account_id=$account->id&add\">Ajouter</a></p> <hr />\n";
else
	echo "<p><a href=\"?list\">Liste</a> | <a href=\"?add\">Ajouter</a></p> <hr />\n";

// tindin
if (isset($_GET["add"]) && domain::insert_perm())
{
	include "template/page/domain_add.tpl.php";
}
elseif (isset($_GET["id"]) && ($domain=domain($id=$_GET["id"])) && $domain->update_perm())
{
	include "template/page/domain_id.tpl.php";
}
else
{
	include "template/page/domain_list.tpl.php";
}

?>
</body>

</html>
