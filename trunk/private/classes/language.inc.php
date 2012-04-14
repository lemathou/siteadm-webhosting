<?php

/**
 * Programming languages management
 * 
 * @package siteadm
 */
class language_manager extends db_object_manager
{

static protected $name = "language";

}

/**
 * Programming languages
 * 
 * @package siteadm
 */
class language extends db_object
{

static protected $_name = "language";
static protected $_db_table = "langage";

static public $_f = array
(
	"name" => array("type"=>"string", "nonempty"=>true),
	"content_type" => array("type"=>"string"),
	"extension_list" => array("type"=>"string"),
);

/*
 * @var string
 */
public $name;
/*
 * @var string
 */
public $content_type;
/*
 * @var string
 */
public $extension_list;

}

?>