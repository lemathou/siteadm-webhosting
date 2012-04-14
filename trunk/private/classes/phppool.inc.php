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

/*
 * @var int
 */
public $account_id;
/*
 * @var string
 */
public $name;

/*
 * @var  string
 */
public $system_user;
/*
 * @var string
 */
public $system_group;

/*
 * @var int
 */
public $phpapp_id;
// If PHP version < 5.3, using own SWPAN_FCGI instead of (not provided by default) PHP-FPM
// Can also use simple CGI requests for sporadic requests (slow but memory-safe, useful in some cases)
/*
 * @var int
 */
public $language_bin_id;

// CGI
/*
 * @var int
 */
public $worker_nb_max = PHP_WORKER_NB_MAX;
/*
 * @var int
 */
public $worker_max_requests = PHP_WORKER_MAX_REQUESTS;

/*
 * @var string
 */
public $webmaster_email;

// Debugging
/*
 * @var string
 */
public $error_reporting = PHP_ERROR_REPORTING;
/*
 * @var int
 */
public $error_display = PHP_ERROR_DISPLAY;
/*
 * @var int
 */
public $error_filesave = PHP_ERROR_FILESAVE;

// Perf
/*
 * @var int
 */
public $max_execution_time = PHP_MAX_EXECUTION_TIME;
/*
 * @var int
 */
public $max_input_time = PHP_MAX_INPUT_TIME;
/*
 * @var int
 */
public $memory_limit = PHP_MEMORY_LIMIT;

// Upload
public $post_max_size = PHP_POST_MAX_SIZE;
public $file_uploads = PHP_FILE_UPLOADS;
public $upload_max_filesize = PHP_UPLOAD_MAX_FILESIZE;
public $max_file_upload = PHP_MAX_FILE_UPLOAD;

// Coding facilities / Security
public $short_open_tag = PHP_SHORT_OPEN_TAG;

// Include
public $include_path = PHP_INCLUDE_PATH;

// APC
public $apc_stat = PHP_APC_STAT;
public $apc_lazy = PHP_APC_LAZY; // functions and classes;

// Extensions
public $extension = array();
// Functions
public $disable_functions = array();

// Memcached
public $memcached_blabla;

static public $_f = array
(
	"name" => array("type"=>"string", "nonempty"=>true),
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"phpapp_id" => array("type"=>"object", "otype"=>"phpapp"),
	"language_bin_id" => array("type"=>"object", "otype"=>"language_bin"),
	"worker_nb_max" => array("type"=>"int"),
	"worker_max_requests" => array("type"=>"int"),
	"webmaster_email" => array("type"=>"string"),
	"error_reporting" => array("type"=>"string"),
	"error_display" => array("type"=>"bool"),
	"error_filesave" => array("type"=>"bool"),
	"max_execution_time" => array("type"=>"int"),
	"max_input_time" => array("type"=>"int"),
	"memory_limit" => array("type"=>"int"),
	"post_max_size" => array("type"=>"int"),
	"file_uploads" => array("type"=>"bool"),
	"upload_max_filesize" => array("type"=>"int"),
	"max_file_upload" => array("type"=>"int"),
	"short_open_tag" => array("type"=>"bool"),
	"include_path" => array("type"=>"string"),
	"apc_stat" => array("type"=>"bool"),
	"apc_lazy" => array("type"=>"bool"),
);

function __toString()
{

return "$this->name";

}

function url()
{

return "php.php?pool_id=$this->id";

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
 * Retrieve phpapp
 */
public function phpapp()
{

if ($this->phpapp_id)
	return phpapp($this->phpapp_id);

}

public function language_bin()
{

if ($phpapp=$this->phpapp())
	return $phpapp->language_bin();
elseif ($this->language_bin_id)
	return language_bin($this->language_bin_id);

}

/**
 * Returns error log filename
 * @return string
 */
public function errorlogfile()
{

if ($account=account($this->account_id))
	return $account->folder()."/log/$this->name.php_error.log";
else
	return SHARED_ROOT."/log/$this->name.php_error.log";

}
/**
 * Returns mail log filename
 * @return string
 */
public function maillogfile()
{

if ($account=account($this->account_id))
	return $account->folder()."/log/$this->name.php_mail.log";
else
	return SHARED_ROOT."/log/$this->name.php_mail.log";

}
/**
 * Returns slow log filename
 * @return string
 */
public function slowlogfile()
{

if ($account=account($this->account_id))
	return $account->folder()."/log/$this->name.php_slow.log";
else
	return SHARED_ROOT."/log/$this->name.php_slow.log";

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

public function insert($infos)
{

if (!($perm=static::insert_perm()) || !is_array($infos))
	return false;

if ($perm != "admin" && ($perm != "manager" || !isset($infos["account_id"]) || !($account=account($infos["account_id"])) || $account->manager_id != login()->id))
{
	$infos["account_id"] = login()->id;
}
if (isset($infos["phpapp_id"]) && ($phpapp=phpapp($infos["phpapp_id"])) && $phpapp->account_id && $phpapp->account_id != login()->id)
{
	unset($infos["phpapp_id"]);
}

return db_object::insert($infos);

}

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

function db_update($infos)
{

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

// ROOT SCRIPTS

/**
 * Parameters for template files
 * @return array
 */
function replace_map()
{

$phpapp = $this->phpapp();
$account = $this->account();
$language_bin = $this->language_bin();

$account_folder = $account->folder();

if ($phpapp)
	$folder = $phpapp->pool_folder();
else
	$folder = "$account_folder/php/pool";

$map = array
(
	"{PHP_NAME}" => $this->name,
	"{PHP_WRAPPER}" => "$account_folder/cgi-bin/php-".$this->name,
	"{PHP_SYSTEM_USER}" => PHP_DEFAULT_SYSTEM_USER,
	"{PHP_SYSTEM_GROUP}" => $account->system_group(),
	"{PHP_SOCK_SYSTEM_GROUP}" => $account->system_group(),
	"{PHP_SOCK}" => "$folder/$account->name-php-$this->name.sock",
	"{PHP_CONF}" => "$folder/$account->name-php-$this->name.conf",
	"{PHP_INI}" => "$folder/$account->name-php-$this->name.ini",
	"{PHP_PID}" => "$folder/$account->name-php-$this->name.pid",
	"{PHP_TMP_DIR}" => "$account_folder/tmp",
	"{WEBSERVER_GROUP}" => WEBSERVER_GROUP,
	"{PHP_WORKER_NB_MAX}" => $this->worker_nb_max,
	"{PHP_WORKER_SPARE_NB_MIN}" => 1,
	"{PHP_WORKER_SPARE_NB_MAX}" => 1,
	"{PHP_WORKER_MAX_REQUESTS}" => $this->worker_max_requests,
	"{PHP_ERROR_LOG}" => $this->errorlogfile(),
	"{PHP_MAIL_LOG}" => $this->maillogfile(),
	"{PHP_SLOW_LOG}" => $this->slowlogfile(),
	"{PHP_WEBMASTER_EMAIL}" => $this->webmaster_email,
	"{PHP_DISABLE_FUNCTIONS}" => "",
	"{PHP_ERROR_REPORTING}" => $this->error_reporting,
	"{PHP_SHORT_OPEN_TAG}" => $this->short_open_tag,
	"{PHP_MAX_EXECUTION_TIME}" => $this->max_execution_time,
	"{PHP_MAX_INPUT_TIME}" => $this->max_input_time,
	"{PHP_MEMORY_LIMIT}" => $this->memory_limit,
	"{PHP_POST_MAX_SIZE}" => $this->post_max_size,
	"{PHP_FILE_UPLOADS}" => $this->file_uploads,
	"{PHP_UPLOAD_MAX_FILESIZE}" => $this->upload_max_filesize,
	"{PHP_MAX_FILE_UPLOAD}" => $this->max_file_upload,
	"{PHP_INCLUDE_PATH}" => $this->include_path,
	"{PHP_APC_STAT}" => $this->apc_stat,
	"{PHP_APC_LAZY}" => $this->apc_lazy,
);

if ($this->worker_nb_max > 2)
{
	$map["{PHP_WORKER_SPARE_NB_MIN}"] = floor($this->worker_nb_max/3);
	$map["{PHP_WORKER_SPARE_NB_MAX}"] = ceil($this->worker_nb_max*2/3);
}

if (!is_null($this->system_user))
{
	$map["{PHP_SYSTEM_USER}"] = $this->system_user;
}
if (!is_null($this->system_group))
{
	$map["{PHP_SYSTEM_GROUP}"] = $this->system_group;
	$map["{PHP_SOCK_SYSTEM_GROUP}"] = $this->system_group;
}

if ($language_bin)
	$map["{PHP_EXEC}"] = $language_bin->exec_bin;
else
	$map["{PHP_EXEC}"] = PHP_DEFAULT_EXEC;

if ($phpapp)
	return array_merge($phpapp->replace_map(), $account->replace_map(), $map);
else
	return array_merge($account->replace_map(), $map);

}

function script_insert()
{

$this->script_structure();
$this->script_update();

}

function script_structure()
{

$account = $this->account();

$account->mkdir("apache", "750");
$account->mkdir("nginx", "750");

}

function script_update()
{

$phpapp = $this->phpapp();
$account = $this->account();

$replace_map = $this->replace_map();

if (!$phpapp) // Self SPAWN_FCGI (useful only for PHP version < 5.3 or if not using PHP-FPM)
{
	$account->copy_tpl("php/php-spawn-fcgi-init.sh", "php/pool/$this->name.sh", $replace_map, "0755");
	$account->copy_tpl("php/php.ini", "php/fcgi-$this->name.ini", $replace_map, "0644");
	exec("ln -s ".$account->folder()."/php/pool/$fcgi-this->name.sh /etc/init.d/$account->name-$this->name.sh");
}
else
{
	copy_tpl("php/php-fpm-app.conf", $phpapp->pool_folder()."/$account->name-php-$this->name.conf", $replace_map, "0644");
}

// Webserver handler
$account->copy_tpl("apache/php.conf", "apache/$account->name-php-$this->name.conf", $replace_map, "0644" );
exec("ln -s ".$account->folder()."/apache/$account->name-php-$this->name.conf ".APACHE_VHOST."/$account->name-php-$this->name.conf");

$this->script_reload();

}

public function script_reload()
{

// Depends of parent process manager
if ($phpapp=$this->phpapp())
{
	$phpapp->script_reload();
}
// Self
else
{
	sleep(2);
	exec($account->folder()."/php/pool/fcgi-$this->name.sh restart > /dev/null &");
}

}

}

?>