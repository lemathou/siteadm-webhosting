<?php

require_once "config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common_logged.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "website";

// ACTIONS

//var_dump($_POST);

// Website
if (isset($_POST["_website_add"]))
{
	$object = new website();
	$object->insert($_POST);
	if ($object->id)
		$_GET["id"] = $object->id;
}
if (isset($_POST["_website_update"]) && isset($_POST["id"]) && ($object=website($_POST["id"])))
{
	$object->update($_POST);
}
if (isset($_POST["_website_delete"]) && isset($_POST["id"]) && ($object=website($_POST["id"])))
{
	$object->delete();
}
if (isset($_POST["_website_action"]) && isset($_POST["_list_id"]) && is_array($_POST["_list_id"]))
{
	foreach($_POST["_list_id"] as $id)
		if (($object=website($id)))
		{
			switch ($_POST["_website_action"])
			{
			case "delete":
				$object->delete();
				break;
			case "activate":
				$object->update(array("actif"=>"1"));
				break;
			case "disable":
				$object->update(array("actif"=>"0"));
				break;
			}
		}
}

// Website Alias
if (isset($_POST["_website_alias_add"]))
{
	$object = new website_alias();
	$object->insert($_POST);
	if ($object->id)
		$_GET["alias_id"] = $object->id;
}
if (isset($_POST["_website_alias_update"]) && isset($_POST["id"]) && ($object=website_alias($_POST["id"])))
{
	$object->update($_POST);
}
if (isset($_POST["_website_alias_delete"]) && isset($_POST["id"]) && ($object=website_alias($_POST["id"])))
{
	$object->delete();
}
if (isset($_POST["_website_alias_action"]) && isset($_POST["_list_id"]) && is_array($_POST["_list_id"]))
{
	foreach($_POST["_list_id"] as $id)
		if (($object=website_alias($id)))
		{
			switch ($_POST["_website_alias_action"])
			{
			case "delete":
				$object->delete();
				break;
			case "activate":
				$object->update(array("actif"=>"1"));
				break;
			case "disable":
				$object->update(array("actif"=>"0"));
				break;
			}
		}
}

if (isset($_GET["_phplib_create"]) && login()->perm("admin"))
{
	// TODO : Le gérer dans la configuration globale
	//phplib_create();
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

$website = null;
$website_alias = null;
$account = null;
$domain = null;

// Contexte de sélection
if (isset($_GET["id"]) && ($website=website($_GET["id"])) && $website->update_perm())
	$_GET["domain_id"] = $website->domain_id;
elseif (isset($_GET["alias_id"]) && ($website_alias=website_alias($_GET["alias_id"])) && $website_alias->update_perm())
	$_GET["domain_id"] = $website_alias->domain_id;
if (login()->perm("admin"))
	include "template/inc/domain_select_admin.tpl.php";
elseif (login()->perm("manager"))
	include "template/inc/domain_select_manager.tpl.php";
else
	include "template/inc/domain_select_user.tpl.php";

// Contexte de sous-menu
if ($domain)
{
	if ($account)
		echo "<p><a href=\"?account_id=$account->id&domain_id=$domain->id\">Liste</a> | <a href=\"?account_id=$account->id&domain_id=$domain->id&add\">Ajouter un site web</a> | <a href=\"?account_id=$account->id&domain_id=$domain->id&alias_add\">Ajouter un alias web</a></p> <hr />\n";
	else
		echo "<p><a href=\"?domain_id=$domain->id&\">Liste</a> | <a href=\"?domain_id=$domain->id&add\">Ajouter un site web</a> | <a href=\"?domain_id=$domain->id&alias_add\">Ajouter un alias web</a></p> <hr />\n";
}

// Contenu de page
if (isset($_GET["id"]) && ($website=website($_GET["id"])) && $website->update_perm())
{
	include "template/page/website_id.tpl.php";
}
elseif (isset($_GET["add"]) && isset($_GET["domain_id"]) && ($domain=domain($_GET["domain_id"])) && ($domain->update_perm()) && website::insert_perm())
{
	$website = new website();
	include "template/page/website_add.tpl.php";
}
elseif (isset($_GET["alias_id"]) && ($website_alias=website_alias($_GET["alias_id"])) && $website_alias->update_perm())
{
	include "template/page/website_alias_id.tpl.php";
}
elseif (isset($_GET["alias_add"]) && ((website_alias::insert_perm() == "admin") || (isset($_GET["domain_id"]) && ($domain=domain($_GET["domain_id"])) && ($domain->update_perm()) && website_alias::insert_perm())))
{
	$website_alias = new website_alias();
	include "template/page/website_alias_add.tpl.php";
}
elseif (isset($_GET["domain_id"]) && ($domain=domain($_GET["domain_id"])) && $domain->update_perm())
{
	include "template/page/website_domain.tpl.php";
}

if (login()->perm("admin")) { ?>
<hr />
<form method="post">
<p>
	<input type="button" value="Recharger Apache" onclick="apache_reload()" />
	<input type="button" value="Relancer Apache" onclick="apache_restart()" />
	<input type="button" value="Démarrer Apache" onclick="apache_start()" />
	<input type="button" value="Arrêter Apache" onclick="apache_stop()" />
</p>
</form>
<?php } ?>
</body>

</html>
