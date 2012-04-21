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
	"actif" => array("type"=>"bool"),
	"account_id" => array("type"=>"object", "otype"=>"account"),
	"username" => array("type"=>"string"),
	"password" => array("type"=>"string"),
	"folder" => array("type"=>"string"),
);

}

?>