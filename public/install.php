<?php

session_start();
include "include/install.inc.php";

if (isset($_POST["_mysql_info"]) && isset($_POST["MYSQL_HOST"]) && isset($_POST["MYSQL_PASS"]))
{
	if (mysql_connect($_POST["MYSQL_HOST"], "root", $_POST["MYSQL_PASS"]))
	{
		$_SESSION["MYSQL_HOST"] = $_POST["MYSQL_HOST"];
		$_SESSION["MYSQL_PASS"] = $_POST["MYSQL_PASS"];
	}
	else
	{
		$message = "Connexion impossible au serveur de base de donnée...";
	}
}

if (empty($_POST["MYSQL_HOST"]))
	$_POST["MYSQL_HOST"] = "";
if (empty($_POST["MYSQL_PASS"]))
	$_POST["MYSQL_PASS"] = "";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
<style type="text/css">
.error{
	color: red;
}
</style>
</head>

<body>
<h1>Installation</h1>

<?php

if (isset($_GET['step1']) && isset($_SESSION['MYSQL_HOST'])) {

install_mysql($_SESSION['MYSQL_HOST'], $_SESSION['MYSQL_PASS']);

}

?>

<p>Si vous en êtes à cette étape, c'est que vous avez déjà lancé le script d'installation : <i>install/install.sh</i></p>
<p>En effet, l'installeur doit en premier lieu disposer de certains droits d'administration sur la machine.</p>

<p>A l'aide des informations ci-dessous, le script d'installation va créer des utilisateurs ainsi qu'une base de donnée.</p>
<p><b>Base de donnée :</b> <i>siteadm</i></p>
<p><b>Utilisateurs :</b> siteadm_admin, siteadm, siteadm_postfix, siteadm_dovecot, siteadm_proftpd</p>

<?php if (isset($message)) { ?>
<p class="error"><?php echo $message; ?></p>
<?php } ?>

<form method="post">
<table class="edit">
<tr>
	<th class="label">MySQL Hostname :</th>
	<td><input name="MYSQL_HOST" value="<?php echo $_POST["MYSQL_HOST"]; ?>" /></td>
</tr>
<tr>
	<th class="label">MySQL ROOT password :</th>
	<td><input name="MYSQL_PASS" value="<?php echo $_POST["MYSQL_HOST"]; ?>" type="password" /></td>
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
