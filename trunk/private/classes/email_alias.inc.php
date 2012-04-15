<?php

/**
 * Email alias / forward management
 * 
 * @package siteadm
 */
class email_alias_manager extends db_object_manager
{

static protected $name = "email_alias";

}

/**
 * Email alias / forward
 * 
 * @package siteadm
 */
class email_alias extends db_object
{

static protected $_name = "email_alias";
static protected $_db_table = "email_alias";

public $account_id;

static public $_f = array
(
	"name" => array("type"=>"string", "nonempty"=>true),
	"domain_id" => array("type"=>"object", "otype"=>"domain", "nonempty"=>true),
	"email_id" => array("type"=>"object", "otype"=>"email"),
	"redirect_email" => array("type"=>"string"),
	"actif" => array("type"=>"bool")
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

return $this->name."@".$this->domain()->name;

}

/**
 * @see db_object::url()
 */
function url()
{

return "email.php?alias_id=$this->id";

}

// ACCESS

/**
 * returns associated domain
 * 
 * @return domain
 */
public function domain()
{

if ($this->domain_id)
	return domain($this->domain_id);

}

/**
 * returns associated email
 * 
 * @return email
 */
public function email()
{

if ($this->email_id)
	return email($this->email_id);

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
// Alias email access
elseif (($email=$this->email()) && ($domain=$email->domain()))
{
	if (login()->perm("manager") && ($account=$domain->account()) && $account->manager_id == login()->id)
		return "email_manager";
	elseif ($domain->account_id == login()->id)
		return "email_user";
	else
		return false;
}
// Domain Manager
elseif (($domain=$this->domain()) && ($account=$domain->account()) && $account->manager_id == login()->id)
{
	return "domain_manager";
}
// User
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
 * @see db_object::insert($infos)
 */
public function insert($infos)
{

if (!($perm=static::insert_perm()) || !is_array($infos))
	return false;

if ($perm != "admin" && (!isset($infos["domain_id"]) || !($domain=domain($infos["domain_id"])) || $domain->update_perm()))
{
	return false;
}

return db_object::insert($infos);

}

/**
 * @see db_object::insert($update)
 */
public function update($infos)
{

if (!($perm=$this->update_perm()) || !is_array($infos))
	return false;

if (isset($infos["email_id"]) && ($perm == "email_user" || $perm == "email_manager") && ($email=email($infos["email_id"])) && !$email->update_perm())
{
	unset($infos["email_id"]);
}

if (isset($infos["domain_id"]))
	unset($infos["domain_id"]);
	
return db_object::update($infos);

}

}

?>