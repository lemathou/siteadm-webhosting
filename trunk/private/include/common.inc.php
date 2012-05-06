<?php

include SITEADM_PRIVATE_DIR."/include/autoload.inc.php";
include SITEADM_PRIVATE_DIR."/include/db.inc.php";
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

/*
 * INFOS UTILES
 */

$civilite_list = array
(
	"m"=>"Monsieur",
	"mme"=>"Madame",
	"mlle"=>"Mademoiselle"
);

/*
 * GESTION DE COMPTE
 */

$account_type_list = array
(
	"user"=>"Utilisateur",
	"manager"=>"Manager",
	"admin"=>"Administrateur"
);

/*
 * LISTE DES OFFRES
 */

$offre_list = array();
$query = mysql_query("SELECT * FROM offre");
while($row=mysql_fetch_assoc($query))
{
	$offre_list[$row["id"]] = $row;
}

/* Liste des comptes gérables */
// @todo : faire autrement mauvaise idée de tout charger ici pour rien...
if (login()->perm("admin"))
{
	$account_list = array();
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
	$account_list = array();
	$query = mysql_query("SELECT * FROM `account` WHERE `manager_id`='".login()->id."' OR `id`='".login()->id."' ORDER BY `name`");
	while($row=mysql_fetch_assoc($query))
	{
		$account_list[$row["id"]] = $row;
	}
}
else // User
{
	$account_list = array();
	$query = mysql_query("SELECT * FROM `account` WHERE id='".login()->id."'");
	while($row=mysql_fetch_assoc($query))
	{
		$account_list[$row["id"]] = $row;
	}
}

/*
 * Folders
 */

$config_folders = array("apache", "cron", "nginx", "cgi-bin", "php", "fetchmail");

/*
 * Template Files
 */

// Common replacements for templates
function replace_map()
{

return array
(
	"{CGI_SPAWN_EXEC}" => CGI_SPAWN_EXEC,
	"{SITEADM_SCRIPT_DIR}" => SITEADM_SCRIPT_DIR,
	"{INIT_SCRIPT_DIR}" => INIT_SCRIPT_DIR,
	"{ROOT_EMAIL}" => ROOT_EMAIL,
	"{POSTMASTER_EMAIL}" => POSTMASTER_EMAIL,
	"{WEBMASTER_EMAIL}" => WEBMASTER_EMAIL,
);

}

/**
 * Merge 2 maps, replacing in first map only if we have not null values in second map
 * @param array $map_1
 * @param array $map_2
 */
function replace_map_merge(&$map, $map_merge)
{

if (!is_array($map) || !is_array($map_merge))
	return;

foreach($map_merge as $key=>$value)
{
	if (!isset($map[$key]) || ($value !== null))
		$map[$key] = $value;
}

}

/**
 * Copy a template file to specified location
 * Used in account and common
 * @param string $file_from
 * @param string $file_to
 * @param [] $replace_map
 * @param string $mode
 * @param int $user_id
 */
function copy_tpl($file_from, $file_to, $replace_map=array(), $mode="0644", $usergroup=null)
{

echo "GENERATING TEMPLATE : $file_to ...\n";

// MAP
$replace_from = array();
$replace_to = array();
if (is_array($replace_map)) foreach($replace_map as $i=>$j)
{
	$replace_from[] = $i;
	$replace_to[] = $j;
}

$filecontents_from = file_get_contents(SITEADM_TEMPLATE_DIR."/$file_from");
$filecontents_to = str_replace($replace_from, $replace_to, $filecontents_from);

// Write
filesystem::write($file_to, $filecontents_to);

// CHOWN
if (!is_null($usergroup))
	filesystem::chown($file_to, $usergroup);

// CHMOD
filesystem::chmod($file_to, $mode);

}

// Divers

function password_create($length=8)
{

$specialchars = "-+*$%!?:";
$numbers = "0123456789";
$lettersl = "abcdefghijklmnopqrstuvwxyz";
$lettersu = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

$chars = "$numbers$lettersl$lettersu$specialchars";
$chars_nb = strlen($chars);

$ok = false;

while (!$ok)
{
	$passwd = "";
	for ($i=0; $i < $length; $i++)
		$passwd .= $chars{mt_rand(0,$chars_nb)};
	$okl = array("specialchars"=>0, "numbers"=>0, "lettersl"=>0, "lettersu"=>0);
	foreach($okl as $name=>$nb)
	{
		for ($i=0; $i < strlen(${$name}); $i++)
			if (strpos($passwd, ${$name}{$i}))
				$okl[$name]++;;
	}
	$ok = true;
	foreach($okl as $name=>$nb)
	{
		$ok = ($ok && $nb);
	}
}

return $passwd;

}

/**
 * Execute a script in the background
 * @param string $command
 * @param string $params
 */
function script_exec($command, $params="")
{

if (!$command || is_numeric(strpos($command, "/")))
	return;

if (file_exists(SITEADM_SCRIPT_DIR."/".$command))
	exec(SITEADM_SCRIPT_DIR."/".$command." ".$params." > /dev/null &");
elseif (file_exists(INIT_SCRIPT_DIR."/".$command))
	exec(SITEADM_SCRIPT_DIR."/".$command." ".$params." > /dev/null &");

}

?>