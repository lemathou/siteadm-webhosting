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
 * Retrieve managing account
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
 */
public function language_bin()
{

if ($this->language_bin_id)
	return language_bin($this->language_bin_id);

}

public function logfile()
{

return $this->account()->folder()."/log/php-".$this->name."_error.log";

}

public function pidfile()
{

return $this->account()->folder()."/php/".$this->name.".pid";

}

public function pid()
{

return file_get_contents($this->pidfile());

}

function folder()
{

return $this->account()->folder()."/php";

}

function pool_folder()
{

return $this->folder()."/$this->name";

}

// PERM

static public function insert_perm()
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
public function update_perm()
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
$pool_folder = $this->pool_folder();
$language_bin = $this->language_bin();

$map = array
(
	"{PHP_INSTALL_PREFIX}" => $language_bin->prefix,
	"{PHP_EXT_DIR}" => "$app_folder/ext", // general
	"{PHP_NAME}" => $this->name,
	"{PHP_ROOT}" => $app_folder,
	"{PHP_VHOST_DIR}" => "$app_folder/vhost",
	"{PHP_POOL_DIR}" => $pool_folder,
	"{PHP_PID}" => "$app_folder/$this->name.pid",
	"{PHP_CONF}" =>"$app_folder/$this->name.conf",
	"{PHP_INI}" => "$app_folder/$this->name.ini",
	"{PHP_ERROR_LOG}" => "$account_folder/log/php-".$this->name."_error.log",
	"{PHP_MAIL_LOG}" => "$account_folder/log/php-".$this->name."_mail.log",
	"{PHP_BASEDIR}" => "$account_folder/public",
	"{PHP_TMP_DIR}" => "$account_folder/tmp",
	"{PHP_LOG_DIR}" => "$account_folder/log",
	"{PHP_COOKIE_DIR}" => "$account_folder/cookies",
	"{PHP_WEBMASTER_EMAIL}" => "$this->webmaster_email",
	"{PHP_APC_SHM_SIZE}" => $this->apc_shm_size."M",
);

return array_merge($account->replace_map(), $map);

}

function script_structure()
{

$account = $this->account();

$account->mkdir("php", "750", "root");
$account->mkdir("php/$this->name", "755", "root");
$account->mkdir("php/vhost", "755", "root");
$account->mkdir("php/pool", "755", "root");

}

/**
 * @see db_object::script_insert()
 */
function script_insert()
{

$this->script_structure();
$this->script_update();

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
$account->copy_tpl("php/php-fpm.conf", "php/$this->name.conf", $replace_map, "0644", "root");
$account->copy_tpl("php/php-fpm-init.conf", "php/fpm-$this->name.sh", $replace_map, "0755", "root");
exec("ln -s ".$account->folder()."/php/fpm-$this->name.sh /etc/init.d/".$account->system_name()."-fpm-$this->name.sh");
$account->copy_tpl("php/php-".$language_bin->version.".ini", "php/$this->name.ini", $replace_map, "0644", "root");

// VHOSTS
// @todo : faire mieux (par exemple $this->website_list())
$query = mysql_query("SELECT t1.`id`, t2.`account_id`, t1.`name`, t2.`name` as domain_name FROM `website` as t1, `domain` as t2, `phppool` as t3 WHERE t1.`domain_id`=t2.`id` AND t1.`phppool_id`=t3.`id` AND t3.`phpapp_id`='$this->id'");
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
fwrite(fopen($account->folder()."/php/vhost/hosts.ini", "w"), $vhost);

// Reload process
$this->script_reload();

}

public function script_reload()
{

sleep(2);
exec($this->folder()."/fpm-$this->name.sh restart > /dev/null &");

}

}

?>