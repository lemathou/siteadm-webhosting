<?php

/**
 * eMail box management
 *
 * @package siteadm
 */
class email_manager extends db_object_manager
{

	static protected $name = "email";

}

/**
 * eMail box
 *
 * @package siteadm
 */
class email extends db_object
{

	static protected $_name = "email";
	static protected $_db_table = "email";

	static public $_f = array
	(
			"name" => array("type"=>"string", "nonempty"=>true),
			"domain_id" => array("type"=>"object", "otype"=>"domain", "nonempty"=>true),
			"account_id" => array("type"=>"object", "otype"=>"account"),
			"password" => array("type"=>"string"),
			"quota" => array("type"=>"select", "list"=>array(10, 100, 1000, 10000)),
			"actif" => array("type"=>"boolean", "default"=>"1"),
	);

	/**
	 * @see db_object::__toString()
	 */
	function __toString()
	{

		return $this->name();

	}
	/**
	 * @return string;
	 */
	function name()
	{

		return $this->name."@".$this->domain()->name;

	}

	// ACCESS

	/**
	 * Returns the associated domain
	 *
	 * @return domain
	 */
	function domain()
	{

		return domain()->get($this->domain_id);

	}

	/**
	 * Returns the associated account
	 *
	 * Attention, pas toujours le compte de gestion du domaine !
	 * @return account
	 */
	function account()
	{

		if ($account=account()->get($this->account_id))
			return $account;
		elseif ($domain=$this->domain())
		return $domain->account();

	}

	/* FOLDERS */

	/**
	 * @return string
	 */
	function folder()
	{

		return $this->account()->email_folder()."/".$this->name();

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
		// Domain Manager
		elseif (login()->perm("manager") && ($domain=$this->domain()) && ($account=$domain->account()) && $account->manager_id == login()->id)
		{
			return "domain_manager";
		}
		// Domain User
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

		if (isset($infos["domain_id"]))
			unset($infos["domain_id"]);
		if (isset($infos["name"]))
			unset($infos["name"]);

		return db_object::update($infos);

	}

	/* ROOT SCRIPTS */

	/**
	 * @see db_object::script_update()
	 */
	function script_update()
	{

		filesystem::link($this->folder(), SITEADM_EMAIL_DIR."/".$this->name());

	}

}

?>