<?php

/**
 * Web Application management
 * 
 * @package siteadm
 */
class webapp_manager extends db_object_manager
{

static protected $name = "webapp";

}

/**
 * Web Application
 * 
 * @package siteadm
 */
class webapp extends db_object
{

static protected $_name = "webapp";
static protected $_db_table = "webapp";

static public $_f = array
(
	"name" => array("type"=>"string", "nonempty"=>true),
	"version" => array("type"=>"string"),
	"description" => array("type"=>"string"),
	"folder_alias" => array("type"=>"string"),
	"php_include_folder" => array("type"=>"string"),
	"php_open_basedir" => array("type"=>"string"),
	"php_short_open_tag" => array("type"=>"bool"),
);

}

?>