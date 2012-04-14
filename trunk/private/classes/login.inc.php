<?php

/**
 * Login (what a description !)
 * 
 * @package siteadm
 */
class login extends account
{

/**
 * 
 * Enter description here ...
 * @param string $username
 * @param string $password
 * @return boolean
 */
function connect($username, $password)
{

if (!is_string($username) || !is_string($password))
	return false;

$query_string = "SELECT * FROM `account` WHERE `name`='".mysql_real_escape_string($username)."' AND `password`='".mysql_real_escape_string($password)."' AND `actif`='1'";
$query = mysql_query($query_string);

if ($infos=mysql_fetch_assoc($query))
{
	foreach($infos as $name=>$value)
	{
		$this->{$name} = $value;
	}
}

}

function disconnect()
{

$this->id = null;
$this->name = null;
$this->manager_id = null;
$this->type = null;
$this->offre_id = null;

}

}

?>