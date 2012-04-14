<?php

/*
 * List of classes using accessors
 */
$GLOBALS["object_list"] = array
(
	"offer" => array(),
	"domain" => array(),
	"phpapp" => array(),
	"language" => array(),
	"language_bin" => array(),
	"phppool" => array(),
	"webapp" => array(),
	"website" => array(),
	"website_alias" => array(),
	"email" => array(),
	"email_alias" => array(),
	"db" => array()
);

// ACCESSORS DEFINITION



// AUTOMATIC ACCESSOR DEFINITION

/**
 * Retrieve an objet defined
 * 
 * @param string $t
 * @param int $id
 * @return mixed
 */
function object($t, $params=null)
{

if (!is_string($t) || !isset($GLOBALS["object_list"][$t]))
	return;

$p = &$GLOBALS["object_list"][$t];
$n = "${t}_manager";
$l = &$GLOBALS[$n];

// Object de gestion
if (!isset($l))
{
	if (!empty($p["cache"]) && ($object=apc_fetch($n)))
	{
		$l = $object;
	}
	else
	{
		$l = new $n();
		if (!empty($p["cache"]))
			apc_store($n, $l);
	}
}

// Renvoi de l'objet de gestion
if ($params === null)
{
	return $l;
}
// Renvoi si objet existant
else
{
	if ($object=$l->get($params))
		return $object;
	else
		return false;
}

}

// Definition of accessors
foreach($GLOBALS["object_list"] as $name=>$i)
{
	if (!function_exists($name))
	{
		eval("function $name(\$params=null) { return object(\"$name\", \$params); }");
	}
}

/**
 * Autoload
 * @param string $class_name
 * @return void
 */
function __autoload($class_name)
{

if (!is_string($class_name))
	return;

if ((substr($class_name, -8, 8) != "_manager" || ($class_name=substr($class_name, 0, -8))) && isset($GLOBALS["object_list"][$class_name]))
	include "../private/classes/$class_name.inc.php";
elseif (file_exists($filename="../private/classes/$class_name.inc.php"))
	include $filename;

}

/**
 * Returns login object
 * @return login
 */
function login()
{

if (!isset($_SESSION["login"]))
	$_SESSION["login"] = new login();

return $_SESSION["login"];

}

/**
 * Returns account object
 * @param integer $id
 * @return account
 */
function account($id)
{

if (!isset($GLOBALS["account_manager"]))
	$GLOBALS["account_manager"] = array();

if (!is_numeric($id))
	return false;
if (isset($GLOBALS["account_manager"][$id]))
	return $GLOBALS["account_manager"][$id];
if ($id == login()->id)
	return $_SESSION["login"];

$account = new account($id);

//var_dump($account);

if ($account->id)
	return $GLOBALS["account_manager"][$id] = $account;
else
	return false;

}

?>