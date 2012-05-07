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
	"domain_id" => array("type"=>"object", "otype"=>"domain"),
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"name" => array("type"=>"string", "nonempty"=>true),
	"folder" => array("type"=>"string"),
	"name" => array("type"=>"string", "nonempty"=>true),
	"charset_default" => array("type"=>"select", "list"=>array("utf-8", "iso-8859-1")),
	"webmaster_email" => array("type"=>"string"),
	"ssl" => array("type"=>"bool"),
	"ssl_force_redirect" => array("type"=>"bool"),
	"index_files" => array("type"=>"string"),
	"folder_auth" => array("type"=>"bool"),
	"webapp_id" => array("type"=>"object", "otype"=>"webapp"),
	"phppool_id" => array("type"=>"object", "otype"=>"phppool"),
	//"php_short_open_tag" => array("type"=>"bool"),
	"php_open_basedir" => array("type"=>"string"),
	"php_short_open_tag" => array("type"=>"bool"),
	"php_include_path" => array("type"=>"string"),
	"php_apc_stat" => array("type"=>"bool"),
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

return $this->name();

}

/**
 * Website complete name
 * @return string
 */
public function name()
{

$domain = $this->domain();
if ($this->name)
	return $this->name.".".$domain->name;
else
	return $domain->name;

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
else
	return account_common();

}

/**
 * @return []website_alias
 */
function alias_list()
{

$list = array();
if ($this->id)
{
	$query_string = "SELECT website_alias.alias_name, domain.name
		FROM website_alias
		LEFT JOIN domain ON domain.id=website_alias.domain_id
		WHERE website_alias.website_id='".$this->id."'";
	$query = mysql_query($query_string);
	while(list($alias_name, $domain_name)=mysql_fetch_row($query))
	{
		if ($domain_name)
			$list[] = "$alias_name.$domain_name";
		else
			$list[] = "$alias_name.*";
	}
}
return $list;

}

// FOLDERS

/**
 * Returns storage folder name
 * @return string
 */
public function folder()
{

return $this->public_folder();

}
public function public_folder()
{

if ($account=$this->account() && $this->folder)
	return $account->public_folder()."/".$this->folder;

}
public function private_folder()
{

if ($account=$this->account())
	return $account->private_folder()."/".$this->folder;

}
/**
 * Returns config folder name
 * @return string
 */
function config_folder()
{

if ($account=$this->account())
	return $account->public_folder()."/config";

}

/**
 * Returns apache log folder name
 * @return string
 */
function apache_log_folder()
{

if ($account=$this->account())
	return $account->log_folder()."/apache";

}
/**
 * Returns apache config folder name
 * @return string
 */
function apache_conf_folder()
{

if ($account=$this->account())
	return $account->conf_folder()."/apache";

}

/**
 * Returns apache config folder name
 * @return string
 */
function awstats_log_folder()
{

if ($account=$this->account())
	return $account->log_folder()."/awstats";

}
/**
 * Returns apache config folder name
 * @return string
 */
function awstats_conf_folder()
{

if ($account=$this->account())
	return $account->conf_folder()."/awstats";

}

/**
 * Returns php log folder name
 * @return string
 */
function php_log_folder()
{

if ($account=$this->account())
	return $account->log_folder()."/php";

}
/**
 * Returns php config folder name
 * @return string
 */
function php_conf_folder()
{

if ($account=$this->account())
	return $account->conf_folder()."/php";

}
/**
 * @return string
 */
function php_vhost_folder()
{

if ($account=$this->account())
	return $account->conf_folder()."/php/vhost";

}

// FILES

/**
 * Returns access log filename
 * @return string
 */
public function accesslog_file()
{

return $this->apache_log_folder()."/".$this->name().".access.log";

}
/**
 * Returns error log filename
 * @return string
 */
public function errorlog_file()
{

return $this->apache_log_folder()."/".$this->name().".error.log";

}
/**
 * Returns PHP error log filename
 * @return string
 */
public function phperrorlog_file()
{

return $this->php_log_folder()."/".$this->name().".php_error.log";

}

/**
 * @return string
 */
public function htpasswd_file()
{

return $this->apache_conf_folder()."/".$this->name().".htpasswd";

}
/**
 * @return string
 */
function awstats_conf_file()
{

return $this->awstats_conf_folder()."/$this->name.$domain->name.conf";

}
/**
 * @return string
 */
function apache_conf_file()
{

return $this->apache_conf_folder()."/".$this->name().".conf";

}
/**
 * @return string
 */
function ssl_cert_file()
{

return $this->apache_conf_folder()."/".$this->name().".crt";

}
/**
 * @return string
 */
function ssl_key_file()
{

return $this->apache_conf_folder()."/".$this->name().".key";

}
/**
 * @return string
 */
function ssl_csr_file()
{

return $this->apache_conf_folder()."/".$this->name().".csr";

}
/**
 * @return string
 */
function ssl_info_file()
{

return $this->apache_conf_folder()."/".$this->name().".sslinfo";

}

/**
 * @return string
 */
function php_ini_file()
{

return $this->php_vhost_folder()."/".$this->name().".ini";

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

//var_dump($infos);

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
	exec("sudo ".SITEADM_SCRIPT_DIR."/db_object.psh ".get_called_class()." $this->id preupdate '$name' '$domain_id' '$account_id'");
}

}

/* REPLACE MAP */

/**
 * Config files vars replacement map
 * @return array
 */
function replace_map()
{

$domain = $this->domain();
$account = $this->account();

// @todo : alias_list

$map = array
(
	"{WEBSITE_LOG_ACCESS}" => $this->accesslog_file(),
	"{WEBSITE_LOG_ERROR}" => $this->errorlog_file(),
	"{WEBSITE_NAME}" => $this->name(),
	"{WEBSITE_ALIAS}" => (count($alias_list=$this->alias_list())?implode(" ", $alias_list):$this->name()),
	"{WEBSITE_INDEX_FILES}" => $this->index_files,
	"{AWSTATS_DATA_DIR}" => $this->awstats_log_folder(),
	"{WEBSITE_CGI_PATH}" => $account->folder()."/cgi-bin",
	"{WEBSITE_PUBLIC_DIR}" => $this->public_folder(),
	"{WEBSITE_PRIVATE_DIR}" => $this->private_folder(),
	"{WEBSITE_CONFIG_DIR}" => $this->config_folder(),
	"{WEBSITE_CHARSET}" => $this->charset_default,
	"{WEBSITE_SSL_CERT}" => $this->ssl_cert_file(),
	"{WEBSITE_SSL_KEY}" => $this->ssl_key_file(),
	"{WEBSITE_PHP_ERROR_LOG}" => $this->phperrorlog_file(),
	"{WEBSITE_FOLDER_ALIAS}" => "",
	"{WEBSITE_FOLDER_AUTH}" => "",
	"{PHP_SHORT_OPEN_TAG}" => $this->php_short_open_tag,
	"{PHP_OPEN_BASEDIR}" => ($this->php_open_basedir!==null) ?$this->php_open_basedir :$this->public_folder().":".$this->private_folder(),
	"{PHP_INCLUDE_PATH}" => $this->php_include_path,
	"{PHP_APC_STAT}" => $this->php_apc_stat,
);

if ($this->webmaster_email)
	$map["{WEBMASTER_EMAIL}"] = $this->webmaster_email;
else
	$map["{WEBMASTER_EMAIL}"] = $account->email;

if ($webapp=$this->webapp())
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

if ($this->folder_auth)
	$map["{WEBSITE_FOLDER_AUTH}"] = 'AuthName "Authentification requise"
	AuthUserFile '.$this->htpasswd_file().'
	AuthGroupFile /dev/null
	AuthType Basic
	require valid-user';

replace_map_merge($map, $domain->replace_map());

if ($phppool=$this->phppool())
	replace_map_merge($map, $phppool->replace_map());

return $map;

}

/* ROOT SCRIPTS */

/**
 * Create SSL certificate
 */
function script_ssl_create()
{

$domain = $this->domain();
$account = $this->account();

$replace_map = $this->replace_map();

exec("rm ".$this->ssl_csr_file());
exec("rm ".$this->ssl_cert_file());
exec("rm ".$this->ssl_key_file());
$account->copy_tpl("ssl-info", $this->ssl_info_file(), $replace_map);
exec("openssl genrsa -out ".$this->ssl_key_file()." 1024");
exec("cat ".$this->ssl_info_file()." | openssl req -new -key ".$this->ssl_key_file()." -out ".$this->ssl_csr_file());
exec("openssl x509 -req -days 365 -in ".$this->ssl_csr_file()." -signkey ".$this->ssl_key_file()." -out ".$this->ssl_cert_file());
exec("rm ".$this->ssl_info_file());

}

/**
 * @see db_object::script_structure()
 */
function script_structure()
{

$domain = $this->domain();
$account = $this->account();

$account->mkdir($this->apache_conf_folder(), "750", "root");
$account->mkdir($this->awstats_conf_folder(), "750", "root");
$account->mkdir($this->php_conf_folder(), "750", "root");
$account->mkdir($this->apache_log_folder(), "1750", "root");
$account->mkdir($this->awstats_log_folder(), "1750", "root");
$account->mkdir($this->php_log_folder(), "1770", "root");
$account->mkdir($this->public_folder(), "755");
$account->mkdir($this->private_folder(), "750");
$account->mkdir($this->config_folder(), "750");
filesystem::link($this->public_folder(), SITEADM_WEBSITE_DIR."/".$this->name());

$replace_map = $this->replace_map();

// Default public files
if (!file_exists($this->folder()."/index.html"))
	$account->copy_tpl("website/index.html", $this->public_folder()."/index.html", $replace_map, "0644");
if ($phppool && !file_exists($this->folder()."/phpinfo.php"))
	$account->copy_tpl("website/phpinfo.php", $this->public_folder()."/phpinfo.php", $replace_map, "0644");

}

/**
 * @see db_object::script_update()
 */
function script_update()
{

$domain = $this->domain();
$account = $this->account();

$replace_map = $this->replace_map();

// PHP vhost config file
if (($phppool=$this->phppool()) && ($phpapp=$phppool->phpapp()))
{
	$account->copy_tpl("php/vhost.conf", $this->php_ini_file(), $replace_map, "0644", "root");
	$phpapp->script_vhost();
	$phpapp->script_reload();
}

// SSL
if ($this->ssl && !file_exists($this->ssl_key_file()))
	$this->script_ssl_create();

// Folder Auth (.htaccess)
if ($this->folder_auth && !file_exists($this->htpasswd_file()))
	exec("touch ".$this->htpasswd_file());

// Webserver config file
if ($this->ssl && $this->ssl_force_redirect)
	$apache_tpl_file = "apache/vhost-ssl-redirect.conf";
elseif ($this->ssl)
	$apache_tpl_file = "apache/vhost-ssl.conf";
else
	$apache_tpl_file = "apache/vhost.conf";
$account->copy_tpl($apache_tpl_file, $this->apache_conf_file(), $replace_map, "0644", "root");
filesystem::link($this->apache_conf_file(), APACHE_VHOST_DIR."/$this->name.$domain->name.conf");

// Awstats
$account->copy_tpl("awstats/awstats.website.conf", $this->awstats_conf_file(), $replace_map, "644", "root");
filesystem::link($this->awstats_conf_file(), AWSTATS_CONFIG_DIR."/awstats.$this->name.$domain->name.conf");

// Reload webserver
$this->script_reload();

}

/**
 * @see db_object::script_preupdate()
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

/**
 * @see db_object::script_reload()
 */
function script_reload()
{

static::script_webserver_reload();

}
static function script_webserver_reload()
{

// Reload apache
script_exec("apache.sh reload");

}

}

?>