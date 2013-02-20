<?php

/**
 * Website alias management
 * 
 * @package siteadm
 */
class website_alias_manager extends db_object_manager
{

static protected $name = "website_alias";

}

/**
 * Website alias
 * 
 * @package siteadm
 */
class website_alias extends db_object
{

static protected $_name = "website_alias";
static protected $_db_table = "website_alias";

static public $_f = array
(
	"domain_id" => array("type"=>"object", "otype"=>"domain"),
	"alias_name" => array("type"=>"string", "nonempty"=>true),
	"website_id" => array("type"=>"object", "otype"=>"website"),
	"website_redirect" => array("type"=>"boolean", "nonempty"=>true),
	"redirect_url" => array("type"=>"string"),
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

if ($domain=$this->domain())
	return $this->alias_name.".".$domain->name;
else
	return "";

}

/**
 * @see db_object::url()
 */
function url()
{

if ($this->id)
	return "website.php?domain_id=$this->domain_id&alias_id=$this->id";
else
	return "website.php?domain_id=$this->domain_id&alias_add";

}

// ACCESS

function alias()
{

return website()->get($this->website_id);

}

function alias_url()
{

if ($website=$this->website())
{
	return (string)$website;
}
else
{
	return (string)$this->website_url;
}

}

function domain()
{

return domain()->get($this->domain_id);

}

function account()
{

if ($domain=$this->domain())
	return $domain->account();
else
	return account_common();

}

// PERM

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
elseif ($account=$this->account())
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

// DB

/**
 * @see db_object::insert()
 */
function insert($infos=array())
{

if (!isset($infos["alias_name"]))
	return false;
if ((!isset($infos["domain_id"]) || !(domain($infos["domain_id"]))) && $this->insert_perm() != "admin")
	return false;
if (!isset($infos["website_redirect"]))
	$infos["website_redirect"] = "0";

return db_object::insert($infos);

}

/**
 * @see db_object::update()
 */
function update($infos=array())
{

if (!($perm=$this->update_perm()))
	return false;

if (isset($infos["account_id"]) && $perm != "admin" && ($perm != "manager" || !isset($infos["account_id"]) || !($account=account($infos["account_id"])) || $account->manager_id != login()->id) && ($infos["account_id"] != login()->id))
{
	unset($infos["account_id"]);
}

if (isset($infos["domain_id"]))
	unset($infos["domain_id"]);
if (isset($infos["alias_name"]))
	unset($infos["alias_name"]);

return db_object::update($infos);

}

}

?>