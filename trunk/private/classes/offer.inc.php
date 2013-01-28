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

	static public $_f = array
	(
			"name" => array("type"=>"string", "nonempty"=>true),
			"description" => array("type"=>"string"),
			"tarif" => array("type"=>"numeric"),
			"disk_quota_soft" => array("type"=>"numeric"),
			"disk_quota_hard" => array("type"=>"numeric"),
			"worker_max" => array("type"=>"numeric"),
			"worker_ram_max" => array("type"=>"numeric"),
			"mysql_db_max" => array("type"=>"numeric"),
			"domain_nb_max" => array("type"=>"numeric"),
			"website_nb_max" => array("type"=>"numeric"),
	);

	/**
	 * @see db_object::__toString()
	 */
	function __toString()
	{

		return (string)$this->name;

	}

	/**
	 * @see db_object::url()
	 */
	function url()
	{

		if ($this->id)
			return "offer.php?id=".$this->id;

	}

}

?>