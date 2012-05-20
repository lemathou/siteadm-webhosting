<?php

/*
 * List of classes using accessors
 */
$GLOBALS["object_list"] = array
(
	//"account" => array(),
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
	"email_sync" => array(),
	"db" => array(),
	"ftp" => array()
);

// ACCESSORS DEFINITION


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

	static $list;
	if (!$list)
		$list = array();

	if (!is_numeric($id) || $id < 0)
		return false;
	$id = (int)$id;
	if (isset($list[$id]))
		return $list[$id];
	if ($id == login()->id)
		return $_SESSION["login"];

	$account = new account($id);

	//var_dump($account);

	if ($account->id)
		return $list[$id] = $account;
	else
		return false;

}

/**
 * Returns common account
 * @return common
 */
function account_common()
{

	static $account;
	if (!$account)
		$account = new common();

	return $account;

}

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

static $list;
if (!$list)
	$list = $GLOBALS["object_list"];
if (!is_string($t) || !isset($list[$t]))
	return;

$p = &$list[$t];
$n = "${t}_manager";
$l = &$list[$n];

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
	include SITEADM_PRIVATE_DIR."/classes/$class_name.inc.php";
elseif (file_exists($filename=SITEADM_PRIVATE_DIR."/classes/$class_name.inc.php"))
	include $filename;

}

?>