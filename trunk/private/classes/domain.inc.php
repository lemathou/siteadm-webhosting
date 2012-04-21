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
	"account_id" => array("type"=>"object", "otype"=>"account", "nonempty"=>true),
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

if ($this->account_id)
	return account($this->account_id);

}

/**
 * Returns websites cumulative access log filename
 * 
 * @return string
 */
public function logaccessfile()
{

$account = $this->account();
return $account->log_folder()."/apache/$this->name.access.log";

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

if ($perm != "admin" && $perm != "manager")
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

// DB

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

// ROOT SCRIPTS

function replace_map()
{

$account = $this->account();

$map = array
(
	"{DOMAIN_NAME}" => $this->name,
	"{DOMAIN_NAME_REGEX}" => str_replace(".", "\\.", $this->name),
	"{DOMAIN_LOG_ACCESS}" => $this->logaccessfile(),
	"{AWSTATS_DATA_DIR}" => $account->folder()."/awstats",
);

return array_merge($account->replace_map(), $map);

}

/**
 * @see db_object::script_update()
 */
function script_update()
{

$account = $this->account();

$replace_map = $this->replace_map();

// Awstats
$account->copy_tpl("awstats/awstats.domain.conf", "conf/awstats/awstats.$this->name.conf", $replace_map, "644", "root");
if (file_exists(AWSTATS_CONFIG_DIR."/awstats.$this->name.conf"))
	exec("rm ".AWSTATS_CONFIG_DIR."/awstats.$this->name.conf");
exec("ln -s ".$account->folder()."/awstats/awstats.$this->name.conf ".AWSTATS_CONFIG_DIR."/");
if (!file_exists(SITEADM_DOMAIN_DIR."/".$this->name))
exec("mkdir ".SITEADM_DOMAIN_DIR."/".$this->name);

}

/**
 * @see db_object::script_delete()
 */
function script_delete()
{

if (file_exists(AWSTATS_CONFIG_DIR."/awstats.$this->name.conf"))
	exec("rm ".AWSTATS_CONFIG_DIR."/awstats.$this->name.conf");
exec("rm -Rf ".SITEADM_DOMAIN_DIR."/".$this->name);
$account->rm("conf/awstats/awstats.$this->name.conf");

}

}

?>