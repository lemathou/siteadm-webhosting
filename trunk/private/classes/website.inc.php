<?php

/**
 * Website management
 * 
 * @package siteadm
 */
class website_manager extends db_object_manager
{

static protected $name = "website";

}

/**
 * Website
 * 
 * @package siteadm
 */
class website extends db_object
{

static protected $_name = "website";
static protected $_db_table = "website";

static public $_f = array
(
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"name" => array("type"=>"string", "nonempty"=>true),
	"folder" => array("type"=>"string"),
	"name" => array("type"=>"string", "nonempty"=>true),
	"default_charset" => array("type"=>"select", "list"=>array("utf-8", "iso-8859-1")),
	"webmaster_email" => array("type"=>"string"),
	"ssl" => array("type"=>"bool"),
	"ssl_force_redirect" => array("type"=>"bool"),
	"index_files" => array("type"=>"string"),
	"folder_auth" => array("type"=>"bool"),
	"webapp_id" => array("type"=>"object", "otype"=>"webapp"),
	"phppool_id" => array("type"=>"object", "otype"=>"phppool"),
	"php_short_open_tag" => array("type"=>"bool"),
	"php_open_basedir" => array("type"=>"string"),
	"php_include_path" => array("type"=>"string"),
	"php_apc_stat" => array("type"=>"bool"),
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

return $this->name.".".$this->domain()->name;

}

// ACCESS

/**
 * Returnd associated domain
 * @return domain
 */
public function domain()
{

if ($this->domain_id)
	return domain($this->domain_id);

}

/**
 * Returns associated webapp
 * @return webapp
 */
public function webapp()
{

if ($this->webapp_id)
	return webapp($this->webapp_id);

}

/**
 * Returns associated phppool
 * @return phppool
 */
public function phppool()
{

if ($this->phppool_id)
	return phppool($this->phppool_id);

}

/**
 * Returns associated account
 * @return account
 */
public function account()
{

if ($account=account($this->account_id))
	return $account;
elseif (($domain=$this->domain()) && ($account=$domain->account()))
	return $account;

}

/**
 * Returns storage folder name
 * @return string
 */
public function folder()
{

return $account->folder()."/public/$this->folder";

}

/**
 * Returns config folder name
 * @return string
 */
function config_folder()
{

$account = $this->account();
return $account->folder()."/public/config";

}

/**
 * Returns access log filename
 * @return string
 */
public function accesslogfile()
{

$domain = $this->domain();
$account = $this->account();
return $account->log_folder()."/$this->name.".$domain->name.".access.log";

}

/**
 * Returns error log filename
 * @return string
 */
public function errorlogfile()
{

$domain = $this->domain();
$account = $this->account();
return $account->log_folder()."/$this->name.".$domain->name.".error.log";

}

/**
 * Returns PHP error log filename
 * @return string
 */
public function phperrorlogfile()
{

$domain = $this->domain();
$account = $this->account();
return $account->log_folder()."/$this->name.".$domain->name.".php_error.log";

}

public function name()
{

$domain = $this->domain();
if ($this->name)
	return $this->name.".".$domain->name;
else
	return $domain->name;

}

// PERM

/**
 * @see db_object::insert_perm()
 */
static public function insert_perm()
{

// Admin
if (login()->perm("admin"))
{
	return "admin";
}
// Special account access
elseif (login()->perm("manager"))
{
	return "domain_manager";
}
// Domain User
elseif (login()->id)
{
	return "domain_user";
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
// Special account access
elseif ($account=account($this->account_id))
{
	if ($this->account_id == login()->id)
		return "user";
	elseif ($account->manager_id == login()->id)
		return "manager";
	else
		return false;
}
// Domain Manager
elseif (($domain=$this->domain()) && ($account=$domain->account()) && $account->manager_id == login()->id)
{
	return "domain_manager";
}
// Domain User
elseif (($domain=$this->domain()) && $domain->account_id == login()->id)
{
	return "domain_user";
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

if (!($perm=static::insert_perm()))
	return false;

if (isset($infos["account_id"]) && $perm != "admin" && ($perm != "manager" || !isset($infos["account_id"]) || !($account=account($infos["account_id"])) || $account->manager_id != login()->id) && ($infos["account_id"] != login()->id))
{
	unset($infos["account_id"]);
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
if (isset($infos["domain_id"]))
	unset($infos["domain_id"]);
if (isset($infos["name"]))
	unset($infos["name"]);

return db_object::update($infos);

}

// DB

/**
 * @see db_object::db_update_more()
 */
function db_update_more($infos)
{

$return = false;

if (isset($infos["php_extension"]))
{
	$query_list = array();
	foreach($infos["php_extension"] as $ext_id)
		$query_list[] = "('$this->id', '$ext_id')";
	mysql_query("DELETE FROM website_phpext_ref WHERE website_id='$this->id'");
	if (count($query_list))
		mysql_query("INSERT INTO website_phpext_ref (website_id, phpext_id) VALUES ".implode(" , ",$query_list));
	$return = true;
	unset($infos["php_extension"]);
}
if (isset($infos["php_disable_functions"]))
{
	$query_list = array();
	foreach($infos["php_disable_functions"] as $fct_id)
		$query_list[] = "('$this->id', '$fct_id')";
	mysql_query("DELETE FROM website_php_disable_functions_ref WHERE website_id='$this->id'");
	if (count($query_list))
		mysql_query("INSERT INTO website_php_disable_functions_ref (website_id, function_id) VALUES ".implode(" , ",$query_list));
	$return = true;
	unset($infos["php_disable_functions"]);
}

return $return;

}

// ROOT ACCESS SCRIPTS

/**
 * @see db_object::root_preupdate()
 */
protected function root_preupdate($infos)
{

if (!$this->id || !is_array($infos))
	return;

$update = false;
if (isset($infos["name"]))
{
	$name = $infos["name"];
	$update = true;
}
else
	$name = "";
if (isset($infos["domain_id"]))
{
	$domain_id = $infos["domain_id"];
	$update = true;
}
else
	$domain_id = "";
if (isset($infos["account_id"]))
{
	$account_id = $infos["account_id"];
	$update = true;
}
else
	$account_id = "";
if ($update)
{
	exec("sudo ".SITEADM_EXEC_DIR."/db_object.psh ".get_called_class()." $this->id preupdate '$name' '$domain_id' '$account_id'");
}

}

// ROOT SCRIPTS

/**
 * Config files vars replacement map
 * @return array
 */
function replace_map()
{

$domain = $this->domain();
$account = $this->account();
$phppool = $this->phppool();
$webapp = $this->webapp();

$map = array
(
	"{WEBSITE_LOG_ACCESS}" => $this->accesslogfile(),
	"{WEBSITE_LOG_ERROR}" => $this->errorlogfile(),
	"{WEBSITE_NAME}" => "$this->name.$domain->name",
	"{WEBSITE_ALIAS}" => "$this->name.$domain->name",
	"{WEBSITE_INDEX_FILES}" => $this->index_files,
	"{AWSTATS_DATA_DIR}" => $account->folder()."/awstats",
	"{WEBSITE_CGI_PATH}" => $account->folder()."/cgi-bin",
	"{WEBSITE_PUBLIC_DIR}" => $this->folder(),
	"{WEBSITE_CONFIG_DIR}" => $this->config_folder(),
	"{WEBSITE_CHARSET}" => $this->default_charset,
	"{WEBSITE_SSL_CERT}" => $account->folder()."/apache/$this->name.$domain->name.crt",
	"{WEBSITE_SSL_KEY}" => $account->folder()."/apache/$this->name.$domain->name.key",
	"{PHP_OPEN_BASEDIR}" => $this->folder()."/",
	"{WEBSITE_PHP_ERROR_LOG}" => $account->log_folder()."/$this->name.".$domain->name.".php_error.log",
	"{WEBSITE_FOLDER_ALIAS}" => "",
	"{WEBSITE_FOLDER_AUTH}" => $this->folder_auth,
);

if ($this->php_short_open_tag !== null)
	$map["{PHP_SHORT_OPEN_TAG}"] = $this->php_short_open_tag;
if ($this->php_open_basedir !== null)
	$map["{PHP_OPEN_BASEDIR}"] = $this->php_open_basedir;
if ($this->php_include_path !== null)
	$map["{PHP_INCLUDE_PATH}"] = $this->php_include_path;
if ($this->php_apc_stat !== null)
	$map["{PHP_APC_STAT}"] = $this->php_apc_stat;

if ($webapp)
{
	if ($webapp->php_open_basedir !== null)
		$map["{PHP_OPEN_BASEDIR}"] .= ":$webapp->php_open_basedir";
	if ($webapp->php_include_folder !== null)
		$map["{PHP_INCLUDE_FOLDER}"] .= ":$webapp->php_include_folder";
	if ($webapp->php_short_open_tag !== null)
		$map["{PHP_SHORT_OPEN_TAG}"] = $webapp->php_short_open_tag;
	if ($webapp->folder_alias !== null)
	{
		$folder_alias_list = json_decode($webapp->folder_alias, true);
		$map["{WEBSITE_FOLDER_ALIAS}"] = "";
		if (is_array($folder_alias_list))
		{
			foreach($folder_alias_list as $i=>$j)
				$map["{WEBSITE_FOLDER_ALIAS}"] .= "Alias $i $j\n";
		}
	}
}

if ($this->webmaster_email !== null)
	$map["{WEBMASTER_EMAIL}"] = $this->webmaster_email;

if ($this->folder_auth)
	$map["{WEBSITE_FOLDER_AUTH}"] = 'AuthName "Authentification requise"
	AuthUserFile '.$account->folder().'/apache/'.$this->name.'.'.$domain->name.'.htpasswd
	AuthGroupFile /dev/null
	AuthType Basic
	require valid-user';

if ($phppool)
	return array_merge($phppool->replace_map(), $domain->replace_map(), $map);
else
	return array_merge($domain->replace_map(), $map);

}

/**
 * Create SSL certificate
 */
function ssl_create()
{

$domain = $this->domain();
$account = $this->account();

$replace_map = $this->replace_map();

exec("rm ".$account->folder()."/apache/$this->name.$domain->name.csr");
exec("rm ".$account->folder()."/apache/$this->name.$domain->name.crt");
exec("rm ".$account->folder()."/apache/$this->name.$domain->name.key");
$account->copy_tpl("ssl-info", "apache/$this->name.$domain->name.sslinfo", $replace_map);
exec("openssl genrsa -out ".$account->folder()."/apache/$this->name.$domain->name.key 1024");
exec("cat ".$account->folder()."/apache/$this->name.$domain->name.sslinfo | openssl req -new -key ".$account->folder()."/apache/$this->name.$domain->name.key -out ".$account->folder()."/apache/$this->name.$domain->name.csr");
exec("openssl x509 -req -days 365 -in ".$account->folder()."/apache/$this->name.$domain->name.csr -signkey ".$account->folder()."/apache/$this->name.$domain->name.key -out ".$account->folder()."/apache/$this->name.$domain->name.crt");
exec("rm ".$account->folder()."/apache/$this->name.$domain->name.sslinfo");

}

function script_structure()
{

$domain = $this->domain();
$account = $this->account();

$account->mkdir("apache", "750", "root");
$account->mkdir("nginx", "750", "root");
$account->mkdir("awstats", "750", "root");
$account->mkdir("public", "750");
$account->mkdir("public/$this->folder", "750");
exec("mkdir ".SITEADM_WEBSITE_DIR."/".$this->name());

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

$domain = $this->domain();
$account = $this->account();
$phppool = $this->phppool();

$replace_map = $this->replace_map();

// Public file
if (!file_exists($this->folder()."/index.html"))
	$account->copy_tpl("website/index.html", "public/$this->folder/index.html", $replace_map, "0644", "root");
if ($phppool && !file_exists($this->folder()."/phpinfo.php"))
	$account->copy_tpl("website/phpinfo.php", "public/$this->folder/phpinfo.php", $replace_map, "0644", "root");

// SSL
if ($this->ssl && !file_exists($account->folder()."/apache/$this->name.$domain->name.key"))
	$this->ssl_create();

// Awstats
$account->copy_tpl("awstats/awstats.website.conf", "awstats/awstats.$this->name.$domain->name.conf", $replace_map, "644", "root");
if (file_exists(AWSTATS_CONFIG_DIR."/awstats.$this->name.$domain->name.conf"))
	exec("rm ".AWSTATS_CONFIG_DIR."/awstats.$this->name.$domain->name.conf");
exec("ln -s ".$account->folder()."/awstats/awstats.$this->name.$domain->name.conf ".AWSTATS_CONFIG_DIR."/");

// PHP vhost config file
if ($phppool)
{
	$account->copy_tpl("php/vhost.conf", "php/vhost/$this->name.$domain->name.ini", $replace_map, "0644", "root");
	$phppool->script_reload();
}

// Folder Auth (.htaccess)
if ($this->folder_auth && !file_exists($account->folder()."/apache/$this->name.$domain->name.htpasswd"))
	exec("touch ".$account->folder()."/apache/$this->name.$domain->name.htpasswd");

// Webserver config file
if ($this->ssl && $this->ssl_force_redirect)
	$account->copy_tpl("apache/vhost-ssl-redirect.conf", "apache/$this->name.$domain->name.conf", $replace_map, "0644", "root");
elseif ($this->ssl)
	$account->copy_tpl("apache/vhost-ssl.conf", "apache/$this->name.$domain->name.conf", $replace_map, "0644", "root");
else
	$account->copy_tpl("apache/vhost.conf", "apache/$this->name.$domain->name.conf", $replace_map, "0644", "root");
if (file_exists(APACHE_VHOST."/$this->name.$domain->name.conf"))
	exc("rm ".APACHE_VHOST."/$this->name.$domain->name.conf");
exec("ln -s ".$account->folder()."/apache/$this->name.$domain->name.conf ".APACHE_VHOST."/");

// Reload webserver
$this->script_reload();

}

/**
 * 
 * @param string $name
 * @param int $domain_id
 * @param int $account_id
 */
function script_preupdate($name, $domain_id, $account_id)
{

// @todo : penser à copier les données en backup pour l'ancien compte ou l'ancien nom de domaine

// Déplacer vers un autre compte
if ($account_id)
{
	
}
// Cahanger le nom de domaine
if ($name || $domain_id)
{
	
}

}

function script_reload()
{

$this->script_webserver_reload();

}
function script_webserver_reload()
{

// Reload apache
exec(SITEADM_EXEC_DIR."/apache_reload.sh > /dev/null &");

}

}

?>