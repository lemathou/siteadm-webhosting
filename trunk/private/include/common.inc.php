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

function foldersize($folder)
{

$j = exec("sudo du -sc $folder");
$nb = substr($j, 0, strpos($j, "\t"));
$s = 0;
while ($nb > 1024)
{
	$s++;
	$nb = $nb/1024;
}
$nb = round($nb, 2);
if ($s == 0)
	return "$nb KO";
elseif ($s == 1)
	return "$nb MO";
elseif ($s == 2)
	return "$nb GO";
else
	return "$nb TO";

}

$config_folders = array("apache", "cron", "nginx", "cgi-bin", "php", "fetchmail");

function mkdir2($folder, $mode="700", $user=SITEADM_SYSTEM_USER, $group=SITEADM_SYSTEM_GROUP)
{

exec("mkdir -m $mode $folder");
if (is_numeric($user))
	$user=(SITEADM_ACCOUNT_UID_MIN+$user);
exec("chown $user.$group $folder");

}

/*
 * Template Files
 */

// Common replacements for templates
function replace_map()
{

return array
(
	"{CGI_SPAWN_EXEC}" => CGI_SPAWN_EXEC,
	"{EXEC_DIR}" => SITEADM_EXEC_DIR,
	"{INIT_SCRIPT_DIR}" => INIT_SCRIPT_DIR,
);

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

$fp_to = fopen($file_to, "w");
fwrite($fp_to, $filecontents_to);
fclose($fp_to);

// CHOWN
if (!is_null($usergroup))
	file_chown($file_to, $usergroup);
// CHMOD
file_chmod($file_to, $mode);

}

/*
 * Security
 */

/**
 * Change file owner
 * @param string $filename
 * @param string $usergroup
 * @param bool $recursive
 */
function file_chown($filename, $usergroup, $recursive=false)
{

$options = "";
if ($recursive)
	$options .= " -R";

exec("chown$options $usergroup \"$filename\""); 

}

/**
 * Change file mode
 * @param string $filename
 * @param string $mode
 */
function file_chmod($filename, $mode)
{

if (file_exists($filename))
	exec("chmod $mode \"$filename\"");

}

/*
 * APACHE
 */

function apache_reload()
{
	passthru("sudo ".APACHE_EXEC_RELOAD);
}
function apache_stop()
{
	passthru("sudo ".APACHE_EXEC_STOP);
}
function apache_start()
{
	passthru("sudo ".APACHE_EXEC_START);
}
function apache_restart()
{
	passthru("sudo ".APACHE_EXEC_RESTART);
}


/**
 * ALIAS
 **/
function website_alias_create($domain_id, $alias_name, $website_id=0, $website_redirect=0, $redirect_url="")
{

$query = mysql_query("INSERT INTO `website_alias` (`domain_id`, `alias_name`, `website_id`, `website_redirect`, `redirect_url`) VALUES ( '$domain_id', '$alias_name', '$website_id', '$website_redirect', '$redirect_url' )");
if ($error=mysql_error())
{
	echo $error;
	return false;
}
elseif (mysql_affected_rows($query))
	return true;

}

/*
 * BOITES EMAIL
 */

// POSTFIX
function postfix_reload()
{
	passthru("sudo ".POSTFIX_EXEC_RELOAD);
}
function postfix_stop()
{
	passthru("sudo ".POSTFIX_EXEC_STOP);
}
function postfix_start()
{
	passthru("sudo ".POSTFIX_EXEC_START);
}
function postfix_restart()
{
	passthru("sudo ".POSTFIX_EXEC_RESTART);
}
// DOVECOT
function devecot_reload()
{
	passthru("sudo ".DOVECOT_EXEC_RELOAD);
}
function devecot_stop()
{
	passthru("sudo ".DOVECOT_EXEC_STOP);
}
function devecot_start()
{
	passthru("sudo ".DOVECOT_EXEC_START);
}
function devecot_restart()
{
	passthru("sudo ".DOVECOT_EXEC_RESTART);
}

// MySQL

function mysql_del($id)
{

$query=mysql_query("SELECT * FROM `mysql` WHERE `id`='$id'");
if (mysql_num_rows($query))
{
	$mysql=mysql_fetch_assoc($query);
	mysql_query("DROP DATABASE `$mysql[name]`");
	mysql_query("DROP USER '$mysql[name]'@'localhost'");
	return true;
}
else
	return false;

}


function db_mysql_del($id)
{

$query=mysql_query("SELECT `name` FROM `mysql` WHERE `id`='$id'");
if (list($name)=mysql_fetch_row($query))
{
	exec("sudo ../scripts/mysql_del.psh $id");
	mysql_query("DELETE FROM `mysql` WHERE `id`='$id'");
}

}

function db_mysql_update($id, $password)
{

mysql_query("UPDATE `mysql` SET `password`='$password' WHERE `id`='$id'");
if (mysql_affected_rows($query))
{
	exec("sudo ../scripts/mysql_update.psh $id");
}

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

?>
