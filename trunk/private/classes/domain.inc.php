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

if ($this->account_id)
	return account($this->account_id);
else
	return account_common();

}

// FOLDERS

/**
 * Returns websites log folder
 *
 * @return string
 */
public function apache_log_folder()
{

if ($account=$this->account())
	return $account->log_folder()."/apache";

}
/**
 * Returns awstats conf folder
 *
 * @return string
 */
public function awstats_conf_folder()
{

if ($account=$this->account())
	return $account->conf_folder()."/awstats";

}
/**
 * Returns awstats log folder
 *
 * @return string
 */
public function awstats_log_folder()
{

if ($account=$this->account())
	return $account->log_folder()."/awstats";

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

$map = array
(
	"{DOMAIN_NAME}" => $this->name,
	"{DOMAIN_NAME_REGEX}" => str_replace(".", "\\.", $this->name),
	"{DOMAIN_LOG_ACCESS}" => $this->logaccess_file(),
	"{AWSTATS_DATA_DIR}" => $this->awstats_log_folder(),
);

if ($account=$this->account())
	replace_map_merge($map, $account->replace_map());

return $map;

}

/**
 * @see db_object::script_structure()
 */
function script_structure()
{

exec("mkdir ".SITEADM_DOMAIN_DIR."/".$this->name);

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