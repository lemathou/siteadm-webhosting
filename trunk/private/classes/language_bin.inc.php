<?php

/**
 * Programming language binaries management
 * 
 * @package siteadm
 */
class language_bin_manager extends db_object_manager
{

static protected $name = "language_bin";

}

/**
 * Programming language binaries
 * 
 * @package siteadm
 */
class language_bin extends db_object
{

static protected $_name = "language_bin";
static protected $_db_table = "language_bin";

static public $_f = array
(
	"language_id" => array("type"=>"object", "otype"=>"language"),
	"app_compatible" => array("type"=>"bool"),
	"version" => array("type"=>"string"),
	"cgi_type" => array("type"=>"string"),
	"options" => array("type"=>"string"),
	"prefix" => array("type"=>"string"),
	"exec_bin" => array("type"=>"string"),
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

if ($this->options)
	$options = " $this->options";
else
	$options = "";

if ($language=$this->language())
	return "$language->name $this->version $this->cgi_type$options";
else
	return "<i>undefined</i>";

}

/**
 * Returns language
 * @return language
 */
function language()
{

if ($this->language_id)
	return language($this->language_id);

}

}

?>