<?php

include SITEADM_PRIVATE_DIR."/include/login.inc.php";

/*
 * Menu
*/

if (login()->perm("admin"))
{
	$menu_list = array(
			"offer" => "Offres",
			"webapp" => "Web apps",
			"language" => "Langages",
			"account" => "Compte(s)",
			"domain" => "Domaines",
			"php" => "PHP",
			"website" => "Sites web",
			"email" => "Messagerie",
			"mysql" => "Bases de donnée",
			"backup" => "Backups",
			"cron" => "Tâches CRON",
			"folder" => "Dossiers",
			"ftp" => "FTP",
	);
}
else
{
	$menu_list = array(
			"account" => "Compte(s)",
			"domain" => "Domaines",
			"php" => "PHP",
			"website" => "Sites web",
			"email" => "Messagerie",
			"mysql" => "Bases de donnée",
			"backup" => "Backups",
			"cron" => "Tâches CRON",
			"folder" => "Dossiers",
			"ftp" => "FTP",
	);
}

$menu = "";

/* Liste des comptes gérables */
// @todo : faire autrement mauvaise idée de tout charger ici pour rien...
$account_list = array();
if (login()->perm("admin"))
{
	$manager_list = array();
	$query = mysql_query("SELECT t1.*, t2.`name` as `manager_name` FROM `account` as t1 LEFT JOIN `account` as t2 ON t1.`manager_id`=t2.`id` ORDER BY t1.`name`");
	while($row=mysql_fetch_assoc($query))
	{
		$account_list[$row["id"]] = $row;
		if ($row["type"]=="manager")
			$manager_list[$row["id"]] = $row["name"];
	}
}
elseif (login()->perm("manager"))
{
	$query = mysql_query("SELECT * FROM `account` WHERE `manager_id`='".login()->id."' OR `id`='".login()->id."' ORDER BY `name`");
	while($row=mysql_fetch_assoc($query))
	{
		$account_list[$row["id"]] = $row;
	}
}
else // User
{
	$query = mysql_query("SELECT * FROM `account` WHERE id='".login()->id."'");
	while($row=mysql_fetch_assoc($query))
	{
		$account_list[$row["id"]] = $row;
	}
}

?>