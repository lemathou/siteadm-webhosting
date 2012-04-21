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

// ACCESS

/**
 * @return string
 */
function folder()
{

return $this->account()->conf_folder()."/php/$this->name";

}
/**
 * @return string
 */
function log_folder()
{

return $this->account()->log_folder()."/php";

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
function vhost_folder()
{

return $this->folder()."/vhost";

}
/**
 * @return string
 */
function pool_folder()
{

return $this->folder()."/pool";

}
/**
 * @return string
 */
public function errorlogfile()
{

return $this->log_folder()."/phpapp-".$this->name."_error.log";

}
/**
 * @return string
 */
public function maillogfile()
{

return $this->log_folder()."/phpapp-".$this->name."_mail.log";

}
/**
 * @return string
 */
public function pidfile()
{

return $this->folder()."/".$this->name.".pid";

}
/**
 * @return string
 */
public function init_script()
{

return $this->folder()."/php5-fpm-$this->name.sh";

}
/**
 * @return string
 */
public function configfile()
{

return $this->folder()."/$this->name.conf";

}
/**
 * @return string
 */
public function inifile()
{

return $this->folder()."/$this->name.ini";

}

/**
 * Returns PID
 * @return int
 */
public function pid()
{

return (int)file_get_contents($this->pidfile());

}

/**
 * Retrieve managing account
 * @return account|common
 */
public function account()
{

if ($account=account($this->account_id))
	return $account;
else
	return new common();

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
	return true;
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

// ROOT SCRIPTS

function replace_map()
{

$account = $this->account();

$account_folder = $account->folder();
$app_folder = $this->folder();
$language_bin = $this->language_bin();

$map = array(
	"{PHP_INSTALL_PREFIX}" => $language_bin->prefix,
	"{PHP_EXT_DIR}" => "$app_folder/ext", // general
	"{PHP_NAME}" => $this->name,
	"{PHP_ROOT}" => $app_folder,
	"{PHP_VHOST_DIR}" => $this->vhost_folder(),
	"{PHP_POOL_DIR}" => $this->pool_folder(),
	"{PHP_PID}" => $this->pidfile(),
	"{PHP_CONF}" => $this->configfile(),
	"{PHP_INI}" => $this->inifile(),
	"{PHP_ERROR_LOG}" => $this->errorlogfile(),
	"{PHP_MAIL_LOG}" => $this->maillogfile(),
	"{PHP_BASEDIR}" => "$account_folder/public",
	"{PHP_TMP_DIR}" => "$account_folder/tmp",
	"{PHP_LOG_DIR}" => $this->log_folder(),
	"{PHP_COOKIE_DIR}" => "$account_folder/cookies",
	"{PHP_WEBMASTER_EMAIL}" => $this->webmaster_email,
	"{PHP_APC_SHM_SIZE}" => $this->apc_shm_size."M",
);

return array_merge($account->replace_map(), $map);

}

/**
 * @see db_object::script_structure()
 */
function script_structure()
{

$account = $this->account();

$account->mkdir($this->folder(), "750", "root");
$account->mkdir($this->ext_folder(), "750", "root");
$account->mkdir($this->pool_folder(), "750", "root");
$account->mkdir($this->vhost_folder(), "750", "root");

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
$account->copy_tpl("php/php-fpm.conf", $this->configfile(), $replace_map, "0644", "root");
$account->copy_tpl("php/php-fpm-init.conf", $this->init_script(), $replace_map, "0755", "root");
$account->copy_tpl("php/php-".$language_bin->version.".ini", $this->inifile(), $replace_map, "0644", "root");
exec("ln -s ".$this->init_script()." /etc/init.d/php5-fpm-".$account->system_name()."-$this->name.sh");

// VHOSTS
// @todo : faire mieux (par exemple $this->website_list())
$query_string = "SELECT t1.`id`, t2.`account_id`, t1.`name`, t2.`name` as domain_name
	FROM `website` as t1
	JOIN `domain` as t2 ON t2.`id`=t1.`domain_id`
	JOIN `phppool` as t3 ON t3.`id`=t1.`phppool_id`
	WHERE t3.`phpapp_id`='$this->id'";
$query = mysql_query($query_string);
$vhost = "";
while ($row=mysql_fetch_assoc($query))
{
	if (file_exists($filename=account($row["account_id"])->folder()."/php/vhost/$row[name].$row[domain_name].ini"))
	{
		$vhost .= file_get_contents($filename)."\n";
	}
	else
	{
		// FORCE UPDATE ?
	}
}
fwrite(fopen($account->conf_folder()."/php/vhost/hosts.ini", "w"), $vhost);

// Reload process
$this->script_reload();

}

/**
 * Reload Application
 */
public function script_reload()
{

sleep(2);
exec($this->init_script()." restart > /dev/null &");

}

}

?>