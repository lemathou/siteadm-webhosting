<?php

/**
 * PHP process management
 * 
 * @package siteadm
 */
class phpapp_manager extends db_object_manager
{

static protected $name = "phpapp";

}

/**
 * PHP process
 * Pour l'instant une seule configuration : 1 seul pool par user avec maxi 5 workers, 1 démarré, 2 maxi qui glandent
 * Idéalement le mec paye pour un hébergement a droit a 5 workers, qu'il répartit en autant de pools qu'il veut
 * 
 * @package siteadm
 */
class phpapp extends db_object
{

static protected $_name = "phpapp";
static protected $_db_table = "phpapp";

static public $_f = array
(
	"name" => array("type"=>"string", "nonempty"=>true),
	"description" => array("type"=>"string"),
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"language_bin_id" => array("type"=>"object", "otype"=>"language_bin"),
	"webmaster_email" => array("type"=>"string"),
	"apc_shm_size" => array("type"=>"numeric"),
	"extension" => array(),
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

return "$this->name";

}

/**
 * @see db_object::url()
 */
function url()
{

return "php.php?app_id=$this->id";

}

/* FOLDERS */

/**
 * @return string
 */
function folder()
{

if ($account=$this->account())
	return $account->conf_folder()."/php/$this->name";

}
/**
 * @return string
 */
function log_folder()
{

if ($account=$this->account())
	return $account->log_folder()."/php";

}
/**
 * @return string
 */
function ext_folder()
{

return $this->folder()."/ext";

}
/**
 * @return string
 */
function ini_folder()
{

return $this->folder()."/ini";

}
/**
 * @return string
 */
function pool_folder()
{

return $this->folder()."/pool";

}

/* FILES */

/**
 * @return string
 */
public function errorlog_file()
{

return $this->log_folder()."/app-".$this->name."_error.log";

}
/**
 * @return string
 */
public function maillog_file()
{

return $this->log_folder()."/app-".$this->name."_mail.log";

}
/**
 * @return string
 */
public function pid_file()
{

return $this->folder()."/".$this->name.".pid";

}
/**
 * @return string
 */
public function script_file()
{

return $this->folder()."/php-fpm-$this->name.sh";

}
/**
 * @return string
 */
public function init_script_file()
{

if ($account=$this->account())
	return INIT_SCRIPT_DIR."/php-fpm-".$account->name."-".$this->name.".sh";

}
/**
 * @return string
 */
public function config_file()
{

return $this->folder()."/$this->name.conf";

}
/**
 * @return string
 */
public function ini_file()
{

return $this->folder()."/$this->name.ini";

}
/**
 * @return string
 */
public function vhost_ini_file()
{

return $this->ini_folder()."/hosts.ini";

}

/**
 * Returns PID
 * @return int
 */
public function pid()
{

return (int)file_get_contents($this->pid_file());

}

/* ACCESS */

/**
 * Retrieve managing account
 * @return account|common
 */
public function account()
{

if ($account=account($this->account_id))
	return $account;
else
	return account_common();

}

/**
 * Retrieve language
 * @return language_bin
 */
public function language_bin()
{

if ($this->language_bin_id)
	return language_bin($this->language_bin_id);

}

/**
 * @return []website
 */
public function website_list()
{

$query_string = "SELECT `website`.`id`
	FROM `website`
	JOIN `phppool` ON `phppool`.`id`=`website`.`phppool_id`
	WHERE `phppool`.`phpapp_id`='$this->id'";

$query = mysql_query($query_string);
$list = array();
while (list($website_id)=mysql_fetch_row($query))
{
	$list[] = website($website_id);
}
return $list;

}

public function phpext_list()
{

$list = array();
if (is_array($this->extension))
{
	$query_string = "SELECT * FROM language_php_ext WHERE id IN (".implode(", ", $this->extension).")";
	$query = mysql_query($query_string);
	while($row=mysql_fetch_assoc($query))
		$list[$row["id"]] = $row;
}
return $list;

}

// PERM

/**
 * @see db_object::insert_perm()
 */
static function insert_perm()
{

// Admin
if (login()->perm("admin"))
{
	return "admin";
}
// Manager
elseif (login()->perm("manager"))
{
	return "manager";
}
// User
elseif (login()->id)
{
	return "user";
}
else
{
	return false;
}

}

/**
 * @see db_object::update_perm()
 */
function update_perm()
{

// Admin
if (login()->perm("admin"))
{
	return "admin";
}
// Manager
elseif (($account=$this->account()) && $account->manager_id == login()->id)
{
	return "manager";
}
// User
elseif ($this->account_id == login()->id)
{
	return "user";
}
else
{
	return false;
}

}

// UPDATE

/**
 * @see db_object::insert()
 */
public function insert($infos)
{

if (!($perm=static::insert_perm()) || !is_array($infos))
	return false;

if ($perm != "admin" && ($perm != "manager" || !isset($infos["account_id"]) || !($account=account($infos["account_id"])) || $account->manager_id != login()->id))
{
	$infos["account_id"] = login()->id;
}

return db_object::insert($infos);

}

/**
 * @see db_object::update()
 */
public function update($infos)
{

if (!($perm=$this->update_perm()))
	return false;

if (isset($infos["account_id"]) && $perm != "admin" && ($perm != "manager" || !($account=account($infos["account_id"])) || $account->manager_id != login()->id))
{
	unset($infos["account_id"]);
}
if (isset($infos["name"]))
	unset($infos["name"]);

return db_object::update($infos);

}

// DB

/**
 * @see db_object::db_retrieve()
 */
function db_retrieve($id)
{

if (db_object::db_retrieve($id))
{
	// Extensions
	$this->extension = array();
	$query = mysql_query("SELECT ext_id FROM phpapp_ext_ref WHERE phpapp_id='$this->id'");
	while(list($ext_id)=mysql_fetch_row($query))
		$this->extension[] = $ext_id;
}

}

/**
 * @see db_object::db_update()
 */
function db_update($infos)
{

//var_dump($infos);

$return = false;
if (isset($infos["extension"]))
{
	mysql_query("DELETE FROM phpapp_ext_ref WHERE phppool_id='$this->id'");
	if (is_array($infos["extension"]))
	{
		$query_list = array();
		foreach($infos["extension"] as $ext_id)
			$query_list[] = "('$this->id', '$ext_id')";
		if (count($query_list))
		{
			$query_string = "INSERT INTO phpapp_ext_ref (phpapp_id, ext_id) VALUES ".implode(" , ",$query_list);
			mysql_query($query_string);
		}
	}
	$return = true;
}

return (db_object::db_update($infos) || $return);

}

/* SCRIPTS */

/**
 * Regen vhost file
 */
protected function regen_vhost()
{

$vhost = "";
foreach ($this->website_list() as $website)
{
	if (file_exists($filename=$website->php_ini_file()))
	{
		$vhost .= file_get_contents($filename)."\n";
	}
}
filesystem::write($this->vhost_ini_file(), $vhost);

}

/* REPLACE MAP */

function replace_map()
{

$account = $this->account();
$language_bin = $this->language_bin();

$map = array(
	"{PHP_APP_NAME}" => $this->name,
	"{PHP_INSTALL_PREFIX}" => $language_bin->prefix,
	"{PHP_EXT_DIR}" => $this->ext_folder(), // general
	"{PHP_ROOT}" => $this->folder(),
	"{PHP_INI_DIR}" => $this->ini_folder(),
	"{PHP_POOL_DIR}" => $this->pool_folder(),
	"{PHP_PID}" => $this->pid_file(),
	"{PHP_CONF}" => $this->config_file(),
	"{PHP_INI}" => $this->ini_file(),
	"{PHP_ERROR_LOG}" => $this->errorlog_file(),
	"{PHP_MAIL_LOG}" => $this->maillog_file(),
	"{PHP_BASEDIR}" => $account->public_folder(),
	"{PHP_TMP_DIR}" => $account->tmp_folder(),
	"{PHP_LOG_DIR}" => $this->log_folder(),
	"{PHP_COOKIE_DIR}" => $account->session_folder(),
	"{PHP_WEBMASTER_EMAIL}" => $this->webmaster_email,
	"{PHP_APC_SHM_SIZE}" => ($this->apc_shm_size ?$this->apc_shm_size."M" : ""),
);

replace_map_merge($map, $account->replace_map());

return $map;

}

/* TO EXECUTE AS ROOT */

/**
 * Script to execute as root
 * Process list
 */
public function root_process_list()
{

if ($this->id)
{
	$exec = "";
	exec("sudo ".SITEADM_SCRIPT_DIR."/db_object.psh ".get_called_class()." $this->id process_list", $exec);
	//echo implode("<br />\n", $exec);
	echo implode("", $exec);
}

}

/* ROOT SCRIPTS */

/**
 * @see db_object::script_structure()
 */
function script_structure()
{

$account = $this->account();

$account->mkdir($this->folder(), "755", "root");
$account->mkdir($this->ext_folder(), "755", "root");
$account->mkdir($this->ini_folder(), "755", "root");
$account->mkdir($this->pool_folder(), "755", "root");

}

/**
 * @see db_object::script_update()
 */
function script_update()
{

$account = $this->account();
$language_bin = $this->language_bin();

$replace_map = $this->replace_map();

// PHP-FPM
$account->copy_tpl("php/php-fpm.conf", $this->config_file(), $replace_map, "0644", "root");
$account->copy_tpl("php/php-fpm-init.conf", $this->script_file(), $replace_map, "0755", "root");
$account->copy_tpl("php/php-".$language_bin->version.".ini", $this->ini_file(), $replace_map, "0644", "root");
filesystem::link($this->script_file(), $this->init_script_file());

// PHP ext
foreach($language_bin->phpext_list() as $ext)
{
	filesystem::link($language_bin->extension_dir."/".$ext["name"].".so", $this->ext_folder()."/".$ext["name"].".so");
	//echo "LINK ".$language_bin->extension_dir."/".$ext["name"].".so => ".$this->ext_folder()."/".$ext["name"].".so\n";
}

// VHOSTS
$this->regen_vhost();

// Reload process
$this->script_reload();

}

/**
 * @see db_object::script_reload()
 */
public function script_reload()
{

exec("nohup ".$this->script_file()." restart > /dev/null &");

}

/**
 * Display process list
 */
public function script_process_list()
{

if ($pid=$this->pid())
{
	$exec = "";
	exec("ps -g ".$pid, $exec);
	echo implode("<br />\n", $exec);
}

}

}

?>