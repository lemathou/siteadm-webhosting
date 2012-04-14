<?php

require_once "include/common.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "email";

// ACTIONS

//var_dump($_POST);

// EMAIL
if (isset($_POST["_email_add"]))
{
	$object = new email();
	$object->insert($_POST);
}
if (isset($_POST["_email_update"]) && isset($_POST["id"]) && ($object=email($_POST["id"])))
{
	$object->update($_POST);
}
if (isset($_POST["_email_del"]) && isset($_POST["id"]) && ($object=email($_POST["id"])))
{
	$object->delete();
}
if (isset($_POST["_email_action"]) && isset($_POST["_list_id"]) && is_array($_POST["_list_id"]))
{
	foreach($_POST["_list_id"] as $id)
		if (($object=email($id)))
		{
			switch ($_POST["_email_action"])
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

// ALIAS
if (isset($_POST["_email_alias_add"]))
{
	$object = new email_alias();
	$object->insert($_POST);
}
if (isset($_POST["_email_alias_update"]) && isset($_POST["id"]) && ($object=email_alias($_POST["id"])))
{
	$object->update($_POST);
}
if (isset($_POST["_email_alias_del"]) && isset($_POST["id"]) && ($object=email_alias($_POST["id"])))
{
	$object->delete();
}
if (isset($_POST["_email_alias_action"]) && isset($_POST["_list_id"]) && is_array($_POST["_list_id"]))
{
	foreach($_POST["_list_id"] as $id)
		if (($object=email_alias($id)))
		{
			switch ($_POST["_email_alias_action"])
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
</head>

<body>
<?php

require_once "template/inc/menu.tpl.php";

$email = null;
$email_alias = null;
$account = null;
$domain = null;

// Contexte de sélection
if (isset($_GET["id"]) && ($email=email($_GET["id"])) && $email->update_perm())
	$_GET["domain_id"] = $email->domain_id;
elseif (isset($_GET["alias_id"]) && ($email_alias=email_alias($_GET["alias_id"])) && $email_alias->update_perm())
	$_GET["domain_id"] = $email_alias->domain_id;
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
		echo "<p><a href=\"?account_id=$account->id&domain_id=$domain->id\">Liste</a> | <a href=\"?account_id=$account->id&domain_id=$domain->id&add\">Ajouter une boite email</a> | <a href=\"?account_id=$account->id&domain_id=$domain->id&alias_add\">Ajouter un alias email</a></p> <hr />\n";
	else
		echo "<p><a href=\"?domain_id=$domain->id&\">Liste</a> | <a href=\"?domain_id=$domain->id&add\">Ajouter une boite email</a> | <a href=\"?domain_id=$domain->id&alias_add\">Ajouter un alias email</a></p> <hr />\n";
}

// Contenu de page
if (isset($_GET["add"]) && $domain && email::insert_perm())
{
	include "template/page/email_add.tpl.php";
}
elseif (isset($_GET["alias_add"]) && $domain && email_alias::insert_perm())
{
	include "template/page/email_alias_add.tpl.php";
}
elseif (isset($_GET["id"]) && ($email=email($_GET["id"])) && $email->update_perm())
{
	include "template/page/email_id.tpl.php";
}
elseif (isset($_GET["alias_id"]) && ($email_alias=email_alias($_GET["alias_id"])) && $email_alias->update_perm())
{
	include "template/page/email_alias_id.tpl.php";
}
elseif (isset($_GET["domain_id"]) && ($domain=domain($_GET["domain_id"])) && $domain->update_perm())
{
	include "template/page/email_domain.tpl.php";
}

?>

<?php // Admin options
if (login()->perm("admin")) { ?>
<hr />
<form>
<p><input type="button" value="Recharger Postfix" onclick="postfix_reload()" /> <input type="button" value="Recharger Dovecot" onclick="dovecot_reload()" /></p>
</form>
<?php } ?>
</body>

</html>
