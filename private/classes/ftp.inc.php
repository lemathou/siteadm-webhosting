<?php

/**
 * FTP account management
 *
 * @package siteadm
 */
class ftp_manager extends db_object_manager
{

static protected $name = "ftp";

}

/**
 * FTP account
 *
 * @package siteadm
 */
class ftp extends db_object
{

static protected $_name = "ftp";
static protected $_db_table = "ftp_user";

static public $_f = array
(
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"actif" => array("type"=>"bool"),
	"username" => array("type"=>"string"),
	"password" => array("type"=>"string"),
	"type" => array("type"=>"select", "list"=>array("public", "private"), "default"=>"public"),
	"folder" => array("type"=>"string"),
);

protected $password;

/**
 * @see db_object::__toString()
 */
public function __toString()
{

return $this->username();
	
}
public function username()
{

return $this->account()->name."_".$this->username;

}

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

}

?>