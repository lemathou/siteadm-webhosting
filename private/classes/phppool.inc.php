<?php

/**
 * PHP Pool associated to a PHP account management
 * 
 * @package siteadm
 */
class phppool_manager extends db_object_manager
{

static protected $name = "phppool";

}

/**
 * PHP Pool associated to a PHP account
 * 
 * @package siteadm
 */
class phppool extends db_object
{

static protected $_name = "phppool";
static protected $_db_table = "phppool";

// Extensions
public $extension = array();
// Functions
public $disable_functions = array();

static public $_f = array
(
	"name" => array("type"=>"string", "nonempty"=>true),
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"phpapp_id" => array("type"=>"object", "otype"=>"phpapp", "nonempty"=>true),
	"worker_nb_max" => array("type"=>"int", "default"=>PHP_WORKER_NB_MAX),
	"worker_max_requests" => array("type"=>"int", "default"=>PHP_WORKER_MAX_REQUESTS),
	"webmaster_email" => array("type"=>"string"),
	"error_reporting" => array("type"=>"string", "default"=>PHP_ERROR_REPORTING),
	"error_display" => array("type"=>"bool", "default"=>PHP_ERROR_DISPLAY),
	"error_filesave" => array("type"=>"bool", "default"=>PHP_ERROR_FILESAVE),
	"max_execution_time" => array("type"=>"int", "default"=>PHP_MAX_EXECUTION_TIME),
	"max_input_time" => array("type"=>"int", "default"=>PHP_MAX_INPUT_TIME),
	"memory_limit" => array("type"=>"int", "default"=>PHP_MEMORY_LIMIT),
	"post_max_size" => array("type"=>"int", "default"=>PHP_POST_MAX_SIZE),
	"file_uploads" => array("type"=>"bool", "default"=>PHP_FILE_UPLOADS),
	"upload_max_filesize" => array("type"=>"int", "default"=>PHP_UPLOAD_MAX_FILESIZE),
	"max_file_upload" => array("type"=>"int", "default"=>PHP_MAX_FILE_UPLOAD),
	"short_open_tag" => array("type"=>"bool", "default"=>PHP_SHORT_OPEN_TAG),
	"include_path" => array("type"=>"string", "default"=>PHP_INCLUDE_PATH),
	"open_basedir" => array("type"=>"string"),
	"apc_stat" => array("type"=>"bool", "default"=>PHP_APC_STAT),
	"apc_lazy" => array("type"=>"bool", "default"=>PHP_APC_LAZY),
	"extension" => array(),
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

return $this->account()."-".$this->name;

}

/**
 * @see db_object::url()
 */
function url()
{

return "php.php?pool_id=$this->id";

}

function system_user()
{
	return $this->account()->php_user();
}
function system_group()
{
	return $this->account()->php_group();
}

/* ACCESS */

/**
 * Retrieve managing account
 * @return account
 */
public function account()
{

if ($account=account()->get($this->account_id))
	return $account;
else
	return account_common();

}

/**
 * Retrieve phpapp
 * @return phpapp
 */
public function phpapp()
{

return phpapp()->get($this->phpapp_id);

}

/**
 * @return language_bin
 */
public function language_bin()
{

if ($phpapp=$this->phpapp())
	return $phpapp->language_bin();

}

public function phpext_list()
{

$list = array();
if (is_array($this->extension) && count($this->extension))
{
	$query_string = "SELECT * FROM language_php_ext WHERE id IN (".implode(", ", $this->extension).") ORDER BY priority DESC";
	$query = mysql_query($query_string);
	while($row=mysql_fetch_assoc($query))
		$list[$row["id"]] = $row;
}
return $list;

}

/* FOLDERS */

/**
 * @return string
 */
public function log_folder()
{

return $this->account()->log_folder()."/php";

}
/**
 * @return string
 */
public function conf_folder()
{

return $this->account()->conf_folder()."/php/pool";

}
/**
 * @return string
 */
public function socket_folder()
{

return $this->account()->socket_folder();

}
/**
 * @return string
 */
public function app_pool_folder()
{

if ($phpapp=$this->phpapp())
	return $phpapp->pool_folder();

}
/**
 * @return string
 */
public function apache_conf_folder()
{

return $this->account()->conf_folder()."/apache";

}
/**
 * @return string
 */
public function session_folder()
{

return $this->account()->session_folder();

}

/* FILES */

/**
 * Returns error log filename
 * @return string
 */
public function errorlog_file()
{

return $this->log_folder()."/pool-".$this->name."_error.log";

}
/**
 * Returns mail log filename
 * @return string
 */
public function maillog_file()
{

return $this->log_folder()."/pool-".$this->name."_mail.log";

}
/**
 * Returns slow log filename
 * @return string
 */
public function slowlog_file()
{

return $this->log_folder()."/pool-".$this->name."_slow.log";

}
/**
 * @return string
 */
function socket_file()
{

return $this->socket_folder()."/pool-".$this->name.".sock";

}
/**
 * @return string
 */
function config_file()
{

return $this->conf_folder()."/".$this->name.".conf";

}
/**
 * @return string
 */
function app_pool_file()
{

return $this->app_pool_folder()."/".$this->account()->name."-".$this->name.".conf";

}
/**
 * @return string
 */
function ini_file()
{

return $this->conf_folder()."/".$this->name.".ini";

}
/**
 * @return string
 */
function apache_file()
{

return $this->apache_conf_folder()."/php-".$this->name.".conf";

}
/**
 * @return string
 */
function pid_file()
{

return $this->conf_folder()."/".$this->name.".pid";

}

/**
 * Returns PID
 * @return int
 */
public function pid()
{

return (int)file_get_contents($this->pid_file());

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
if (isset($infos["phpapp_id"]) && (!($phpapp=phpapp()->get($infos["phpapp_id"])) || (!login()->perm("admin") && $phpapp->account_id && $phpapp->account_id != login()->id)))
{
	unset($infos["phpapp_id"]);
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
if (isset($infos["phpapp_id"]) && ($phpapp=phpapp($infos["phpapp_id"])) && $phpapp->account_id && $phpapp->account_id != login()->id)
{
	unset($infos["phpapp_id"]);
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
	$query = mysql_query("SELECT ext_id FROM phppool_ext_ref WHERE phppool_id='$this->id'");
	while(list($ext_id)=mysql_fetch_row($query))
		$this->extension[] = $ext_id;
	// Disabled functions
	$this->disable_functions = array();
	$query = mysql_query("SELECT function_id FROM phppool_disable_functions_ref WHERE phppool_id='$this->id'");
	while(list($function_id)=mysql_fetch_row($query))
		$this->disable_functions[] = $function_id;
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
	mysql_query("DELETE FROM phppool_ext_ref WHERE phppool_id='$this->id'");
	if (is_array($infos["extension"]))
	{
		$query_list = array();
		foreach($infos["extension"] as $ext_id)
			$query_list[] = "('$this->id', '$ext_id')";
		if (count($query_list))
		{
			$query_string = "INSERT INTO phppool_ext_ref (phppool_id, ext_id) VALUES ".implode(" , ",$query_list);
			mysql_query($query_string);
		}
	}
	$return = true;
}
if (isset($infos["disable_functions"]))
{
	mysql_query("DELETE FROM phppool_disable_functions_ref WHERE phppool_id='$this->id'");
	if (is_array($infos["disable_functions"]))
	{
		$query_list = array();
		foreach($infos["disable_functions"] as $fct_id)
			$query_list[] = "('$this->id', '$fct_id')";
		if (count($query_list))
		{
			$query_string = "INSERT INTO phppool_disable_functions_ref (phppool_id, function_id) VALUES ".implode(" , ",$query_list);
			mysql_query($query_string);
		}
	}
	$return = true;
}

return (db_object::db_update($infos) || $return);

}

/* REPLACE MAP */

/**
 * Parameters for template files
 * @return array
 */
function replace_map()
{

$phpapp = $this->phpapp();
$account = $this->account();

$map = array
(
	"{PHP_POOL_NAME}" => $this->name,
	"{PHP_WRAPPER}" => $account->folder()."/cgi-bin/php-".$this->name, // Only for apache fastcgi config compatibility
	"{PHP_SYSTEM_USER}" => $this->system_user(),
	"{PHP_SYSTEM_GROUP}" => $this->system_group(),
	"{PHP_SOCK_SYSTEM_GROUP}" => $this->system_group(),
	"{PHP_SOCK}" => $this->socket_file(),
	"{PHP_CONF}" => $this->config_file(),
	"{PHP_INI}" => $this->ini_file(),
	"{PHP_PID}" => $this->pid_file(),
	"{PHP_TMP_DIR}" => $account->tmp_folder(),
	"{PHP_SESSION_DIR}" => $this->session_folder(),
	"{WEBSERVER_GROUP}" => WEBSERVER_GROUP,
	"{PHP_WORKER_NB_MAX}" => $this->worker_nb_max,
	"{PHP_WORKER_SPARE_NB_MIN}" => 1,
	"{PHP_WORKER_SPARE_NB_MAX}" => 1,
	"{PHP_WORKER_MAX_REQUESTS}" => $this->worker_max_requests,
	"{PHP_ERROR_LOG}" => $this->errorlog_file(),
	"{PHP_MAIL_LOG}" => $this->maillog_file(),
	"{PHP_SLOW_LOG}" => $this->slowlog_file(),
	"{PHP_WEBMASTER_EMAIL}" => $this->webmaster_email,
	"{PHP_DISABLE_FUNCTIONS}" => "",
	"{PHP_ERROR_REPORTING}" => $this->error_reporting,
	"{PHP_SHORT_OPEN_TAG}" => $this->short_open_tag,
	"{PHP_MAX_EXECUTION_TIME}" => $this->max_execution_time ?$this->max_execution_time :30,
	"{PHP_MAX_INPUT_TIME}" => $this->max_input_time ?$this->max_input_time :60,
	"{PHP_MEMORY_LIMIT}" => $this->memory_limit,
	"{PHP_POST_MAX_SIZE}" => $this->post_max_size,
	"{PHP_FILE_UPLOADS}" => $this->file_uploads,
	"{PHP_UPLOAD_MAX_FILESIZE}" => $this->upload_max_filesize,
	"{PHP_MAX_FILE_UPLOAD}" => $this->max_file_upload,
	"{PHP_INCLUDE_PATH}" => ($this->include_path ?$this->include_path : "."),
	"{PHP_OPEN_BASEDIR}" => ($this->open_basedir ?$this->open_basedir : $account->private_folder().":".$account->public_folder()),
	"{PHP_APC_STAT}" => $this->apc_stat,
	"{PHP_APC_LAZY}" => $this->apc_lazy,
	"{PHP_POOL_EXTENSIONS}" => "",
);

if ($this->worker_nb_max > 2)
{
	$map["{PHP_WORKER_SPARE_NB_MIN}"] = floor($this->worker_nb_max/3);
	$map["{PHP_WORKER_SPARE_NB_MAX}"] = ceil($this->worker_nb_max*2/3);
}

// @todo : à retravailler
if (isset($this->system_user) && !is_null($this->system_user))
{
	$map["{PHP_SYSTEM_USER}"] = $this->system_user;
}
if (isset($this->system_group) && !is_null($this->system_group))
{
	$map["{PHP_SYSTEM_GROUP}"] = $this->system_group;
	$map["{PHP_SOCK_SYSTEM_GROUP}"] = $this->system_group;
}
$phpapp_ext_list = $phpapp->phpext_loaded_list();
foreach($this->phpext_list() as $ext)
{
	if (!isset($phpapp_ext_list[$ext["id"]]))
	{
		$map["{PHP_POOL_EXTENSIONS}"] .= "php_admin_value[extension] = ".$ext["name"].".so\n";
	}
}

replace_map_merge($map, $phpapp->replace_map());

return $map;

}

/* ROOT SCRIPTS */

/**
 * @see db_object::script_structure()
 */
function script_structure()
{

$account = $this->account();

$account->mkdir($this->conf_folder(), "750", "root");
$account->mkdir($this->log_folder(), "1750", "root");

}

/**
 * @see db_object::script_update()
 */
function script_update()
{

$phpapp = $this->phpapp();
$account = $this->account();

$replace_map = $this->replace_map();

copy_tpl("php/php-fpm-app.conf", $this->config_file(), $replace_map, "0644");
filesystem::link($this->config_file(), $this->app_pool_file());

// Webserver handler
$account->copy_tpl("apache/php.conf", $this->apache_file(), $replace_map, "0644");
filesystem::link($this->apache_file(), APACHE_VHOST_DIR."/php-".$account->name."-".$this->name.".conf");

$phpapp->script_update();
website::script_webserver_reload();
//$this->script_reload();

}

/**
 *  @see db_object::script_reload()
 */
public function script_reload()
{

$this->phpapp()->script_reload();
website::script_webserver_reload();

}

}

?>
