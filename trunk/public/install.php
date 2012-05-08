<?php

session_start();

if (isset($POST["_mysql_info"]))
{
	if (isset($POST["MYSQL_HOST"]) && isset($POST["MYSQL_PASS"]))
	{
		
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
</head>

<body>
<h1>Installation</h1>

<p>Si vous en êtes à cette étape, c'est que vous avez déjà lancé le script d'installation : <i>install/install.sh</i></p>
<p>En effet, l'installeur doit en premier lieu disposer de certains droite d'administration sur la machine.</p>

<p>A l'aide des informations ci-dessous, le script d'installation va créer des utilisateurs ainsi qu'une base de donnée.</p>
<p><b>Base de donnée :</b> <i>siteadm</i></p>
<p><b>Utilisateurs :</b> siteadm_admin, siteadm, siteadm_postfix, siteadm_dovecot, siteadm_proftpd</p>

<form method="post">
<table class="edit">
<tr>
	<th class="label">Hostname :</th>
	<td><input name="MYSQL_HOST" /></td>
</tr>
<tr>
	<th class="label">MySQL ROOT password :</th>
	<td><input name="MYSQL_PASS" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" name="_mysql_info" value="Etape suivante" /></td>
</tr>
</table>
</form>
<?php



?>
</body>

</html>