<?php

/**
 * Offer management
 * 
 * @package siteadm
 */
class offer_manager extends db_object_manager
{

static protected $name = "offer";

}

/**
 * Offer
 * 
 * @package siteadm
 */
class offer extends db_object
{

static protected $_name = "offer";
static protected $_db_table = "offre";

public $name;
public $description;
public $tarif;
public $disk_quota;
public $worker_max;
public $worker_ram_max;
public $mysql_db_max;

static public $_f = array
(
	"name" => array("type"=>"string", "nonempty"=>true),
	"description" => array("type"=>"string"),
	"tarif" => array("type"=>"numeric"),
	"disk_quota" => array("type"=>"numeric"),
	"worker_max" => array("type"=>"numeric"),
	"worker_ram_max" => array("type"=>"numeric"),
	"mysql_db_max" => array("type"=>"numeric"),
);

}

?>