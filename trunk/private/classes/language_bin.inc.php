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
			"extension_dir" => array("type"=>"string"),
			"extension" => array(),
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

		return language()->get($this->language_id);

	}

	public function phpext_list()
	{

		$list = array();
		if (is_array($this->extension) && count($this->extension))
		{
			$query_string = "SELECT * FROM language_php_ext WHERE id IN (".implode(", ", $this->extension).")";
			$query = mysql_query($query_string);
			while($row=mysql_fetch_assoc($query))
				$list[$row["id"]] = $row;
		}
		return $list;

	}

	// DB

	/**
	 * @see db_object::db_retrieve()
	 */
	function db_retrieve($id)
	{

		if (db_object::db_retrieve($id))
		{
			// Extensions
			$this->extension = array();
			$query_string = "SELECT ext_id FROM language_bin_php_ext_ref WHERE language_bin_id='$this->id'";
			$query = mysql_query($query_string);
			while(list($ext_id)=mysql_fetch_row($query))
				$this->extension[] = $ext_id;
		}

	}

}

?>