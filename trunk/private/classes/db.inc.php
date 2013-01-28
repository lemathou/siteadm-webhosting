<?php

/**
 * Database (MySQL) list management class
 *
 * @package siteadm
 */
class db_manager extends db_object_manager
{

	static protected $name = "db";

}

/**
 * User Database (MySQL)
 *
 * @package siteadm
 */
class db extends db_object
{

	static protected $_name = "db";
	static protected $_db_table = "db";

	static public $_f = array
	(
			"account_id" => array("type"=>"object", "otype"=>"account"),
			"dbname" => array("type"=>"string", "nonempty"=>true),
			"username" => array("type"=>"string", "nonempty"=>true),
			"password" => array("type"=>"string"),
			"quota" => array("type"=>"select", "list"=>array("10", "100", "1000", "10000")),
			"max_queries" => array("type"=>"numeric", "default"=>MYSQL_MAX_QUERIES),
			"max_user_connections" => array("type"=>"numeric", "default"=>MYSQL_MAX_USER_CONNECTIONS),
			"max_connections" => array("type"=>"numeric", "default"=>MYSQL_MAX_CONNECTIONS),
			"max_updates" => array("type"=>"numeric", "default"=>MYSQL_MAX_UPDATES),
	);

	// ACCESS

	/**
	 * @return account
	 */
	public function account()
	{

		return account()->get($this->account_id);

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
		// Manager
		elseif (login()->perm("manager"))
		{
			return "manager";
		}
		// User
		elseif (login()->id)
		{
			return "user";
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
		// Account Manager
		elseif (($account=$this->account()) && $account->manager_id == login()->id)
		{
			return "manager";
		}
		// User
		elseif ($this->account_id == login()->id)
		{
			return "user";
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

		if ($perm != "admin" && ($perm != "manager" || !isset($infos["account_id"]) || !($account=account($infos["account_id"])) || $account->manager_id != login()->id))
		{
			$infos["account_id"] = login()->id;
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

		if ($infos["account_id"] && $perm != "admin" && ($perm != "manager" || !($account=account($infos["account_id"])) || $account->manager_id != login()->id))
		{
			unset($infos["account_id"]);
		}

		return db_object::update($infos);

	}

	// ROOT SCRIPTS

	function script_insert()
	{

		// Création user
		mysql_query("CREATE USER '$this->username'@'localhost' IDENTIFIED BY '$this->password'");
		echo mysql_error();
		// Création table
		mysql_query("CREATE DATABASE `$this->dbname`");
		// Droits de base pour user
		mysql_query("GRANT USAGE ON *.* TO '$this->username'@'localhost' IDENTIFIED BY '$this->password' WITH MAX_QUERIES_PER_HOUR $this->max_queries MAX_CONNECTIONS_PER_HOUR $this->max_connections MAX_UPDATES_PER_HOUR $this->max_updates MAX_USER_CONNECTIONS $this->max_user_connections; ");
		// Droits spécifiques pour user
		mysql_query("GRANT ALL PRIVILEGES ON `$this->dbname`. * TO '$this->username'@'localhost'");

		return true;

	}

	function script_update()
	{

		mysql_query("SET PASSWORD FOR '$this->username'@'localhost' = PASSWORD('$this->password')");

		return true;

	}

	function script_delete()
	{

		// Suppression privilèges
		$query_string = "REVOKE ALL PRIVILEGES, GRANT OPTION FROM '$this->username'@'localhost'";
		mysql_query($query_string);
		// Suppression user
		$query_string = "DROP USER '$this->username'@'localhost'";
		mysql_query($query_string);
		// Suppression table
		$query_string = "DROP DATABASE `$this->dbname`";
		mysql_query($query_string);

		return true;

	}

}

?>