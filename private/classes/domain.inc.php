<?php

/**
 * Domain name management
 * 
 * @package siteadm
 */
class domain_manager extends db_object_manager
{

static protected $name = "domain";

}

/**
 * Domain name
 * 
 * @package siteadm
 */
class domain extends db_object
{

static protected $_name = "domain";
static protected $_db_table = "domain";

public $email_nb;
public $email_alias_nb;
public $website_nb;
public $website_alias_nb;

static public $_f = array
(
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"name" => array("type"=>"string", "nonempty"=>true),
	"email_actif" => array("type"=>"boolean"),
	"creation_date" => array("type"=>"date"),
	"renew_date" => array("type"=>"date"),
);

// ACCESS

/**
 * Retrieve managing account
 * 
 * @return account
 */
public function account()
{

if ($account=account()->get($this->account_id))
	return $account;
else
	return account_common();

}

/* FOLDERS */

/**
 * Returns apache config folder name
 * @return string
 */
function apache_conf_folder()
{

return $this->account()->conf_folder()."/apache";

}
/**
 * Returns websites log folder name
 * @return string
 */
public function apache_log_folder()
{

return $this->account()->log_folder()."/apache";

}

/**
 * Returns awstats conf folder name
 * @return string
 */
public function awstats_conf_folder()
{

return $this->account()->conf_folder()."/awstats";

}
/**
 * Returns awstats log folder name
 * @return string
 */
public function awstats_log_folder()
{

return $this->account()->log_folder()."/awstats";

}

// FILES

/**
 * Returns websites cumulative access log filename
 *
 * @return string
 */
public function logaccess_file()
{

return $this->apache_log_folder()."/$this->name.access.log";

}
/**
 * Returns awstats cumulative file
 * 
 * @return string
 */
public function awstats_conf_file()
{

return $this->awstats_conf_folder()."/$this->name.conf";

}

/**
 * @return string
 */
function ssl_cert_file()
{

return $this->apache_conf_folder()."/".$this->name.".crt";

}
/**
 * @return string
 */
function ssl_key_file()
{

return $this->apache_conf_folder()."/".$this->name.".key";

}
/**
 * @return string
 */
function ssl_csr_file()
{

return $this->apache_conf_folder()."/".$this->name.".csr";

}
/**
 * @return string
 */
function ssl_info_file()
{

return $this->apache_conf_folder()."/".$this->name.".sslinfo";

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
// Domain Manager
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
// Domain Manager
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

if (!($perm=static::insert_perm()))
	return false;

if ($perm != "admin" && ($perm != "manager" || false))
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

if ($perm != "admin")
{
	if (isset($infos["account_id"]))
		unset($infos["account_id"]);
}
if (isset($infos["name"]))
	unset($infos["name"]);

return db_object::update($infos);

}

/* DB */

/**
 * @see db_object::db_retrieve_more()
 */
function db_retrieve_more($id)
{

// TODO : voir si besoin de mise à jour systématique
$this->email_nb = array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `email` WHERE `domain_id`='$this->id'")));
$this->email_alias_nb = array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `email_alias` WHERE `domain_id`='$this->id'")));
$this->website_nb = array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `website` WHERE `domain_id`='$this->id'")));
$this->website_alias_nb = array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `website_alias` WHERE `domain_id`='$this->id'")));

}

/* REPLACE MAP */

function replace_map()
{

$map = array
(
	"{DOMAIN_NAME}" => $this->name,
	"{DOMAIN_NAME_REGEX}" => str_replace(".", "\\.", $this->name),
	"{DOMAIN_LOG_ACCESS}" => $this->logaccess_file(),
	"{AWSTATS_DATA_DIR}" => $this->awstats_log_folder(),
	"{DOMAIN_SSL_CERT}" => $this->ssl_cert_file(),
	"{DOMAIN_SSL_KEY}" => $this->ssl_key_file(),
);

if ($account=$this->account())
	replace_map_merge($map, $account->replace_map());

return $map;

}

/* ROOT SCRIPTS */

/**
 * Create SSL certificate
 */
function script_ssl_create()
{

$account = $this->account();

$replace_map = $this->replace_map();

$account->rm($this->ssl_csr_file());
$account->rm($this->ssl_cert_file());
$account->rm($this->ssl_key_file());
$account->copy_tpl("ssl/ssl-info", $this->ssl_info_file(), $replace_map);
exec("openssl genrsa -out ".$this->ssl_key_file()." 1024");
exec("cat ".$this->ssl_info_file()." | openssl req -new -key ".$this->ssl_key_file()." -out ".$this->ssl_csr_file());
exec("openssl x509 -req -days 3650 -in ".$this->ssl_csr_file()." -CA ".SSL_CA_CRT." -CAkey ".SSL_CA_KEY." -CAcreateserial -out ".$this->ssl_cert_file());
$account->rm($this->ssl_info_file());
filesystem::chmod($this->ssl_csr_file(), "640");
filesystem::chmod($this->ssl_cert_file(), "640");
filesystem::chmod($this->ssl_key_file(), "640");

}

/**
 * @see db_object::script_structure()
 */
function script_structure()
{

$this->account()->mkdir(SITEADM_DOMAIN_DIR."/".$this->name, "750", "root");

}

/**
 * @see db_object::script_insert()
 */
function script_insert()
{

$this->script_ssl_create();

db_object::script_insert();

}

/**
 * @see db_object::script_update()
 */
function script_update()
{

$account = $this->account();

$replace_map = $this->replace_map();

// Awstats
$account->copy_tpl("awstats/awstats.domain.conf", $this->awstats_conf_file(), $replace_map, "644", "root");
filesystem::link($this->awstats_conf_file(), AWSTATS_CONFIG_DIR."/awstats.$this->name.conf");

}

/**
 * @see db_object::script_delete()
 */
function script_delete()
{

$account->rm($this->awstats_conf_file());
filesystem::rm(AWSTATS_CONFIG_DIR."/awstats.$this->name.conf");
filesystem::rmdir(SITEADM_DOMAIN_DIR."/".$this->name);

}

}

?>