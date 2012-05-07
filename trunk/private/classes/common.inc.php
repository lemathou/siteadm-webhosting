<?php

/**
 * Shared (common) account
 * 
 * @package siteadm
 */
class common extends account
{

public $id = 0;
public $name = "common";
public $email = SHARED_EMAIL;
public $folder = "common";

function __toString()
{

return "Partagé [$this->name]";

}

/**
 * OS (Linux) system user id (uid) and group id (gid)
 * 
 * @return int
 */
function system_id()
{

return ACCOUNT_UID_MIN;

}
function system_user()
{

return "siteadm_common";

}
function system_group()
{

return "siteadm_common";

}
/**
 * PHP user ID
 * @return int
 */
function php_id()
{

return PHP_UID_MIN;

}
function php_user()
{

return "php_common";

}
function php_group()
{

return $this->system_group();

}
/**
 * Email user ID
 * @return int
 */
function email_id()
{

return EMAIL_UID_MIN;

}
function email_user()
{

return "email_common";

}
function email_group()
{

return $this->system_group();

}

/* OTHER */

function language_bin_list()
{

$list = array();
$query_string = "SELECT id FROM language_bin";
$query = mysql_query($query_string);
while (list($id)=mysql_fetch_row($query))
{
	$list[] = language_bin($id);
}
return $list;

}

function phpapp_list()
{

$list = array();
$query_string = "SELECT t1.id FROM phpapp as t1 WHERE t1.account_id IS NULL";
$query = mysql_query($query_string);
while(list($id)=mysql_fetch_row($query))
	$list[] = phpapp($id);
return $list;

}

function phppool_list()
{

$list = array();
$query_string = "SELECT DISTINCT t1.id FROM phppool as t1 LEFT JOIN phpapp AS t2 ON t2.id=t1.phpapp_id AND t2.account_id IS NULL WHERE t1.account_id IS NULL";
$query = mysql_query($query_string);
while(list($id)=mysql_fetch_row($query))
	$list[] = phppool($id);
return $list;

}

function phpext_list($language_bin_id=null)
{

$list = array();
$query_string = "SELECT language_php_ext.*, if(language_php_bin_ext_ref.phpext_id, 1, 0) as `already`, 1 as `authorized`
	FROM language_php_ext
	LEFT JOIN language_php_bin_ext_ref ON language_php_ext.id=language_php_bin_ext_ref.phpext_id AND language_php_bin_ext_ref.language_bin_id='".$language_bin_id."'
	WHERE language_php_ext.type='extension'
	ORDER BY language_php_ext.description";
$query = mysql_query($query_string);
while($row=mysql_fetch_assoc($query))
{
	$list[$row["id"]] = $row;
}
return $list;

}

/* INUTILE */

function __construct()
{

}
function insert()
{

}
function db_insert()
{

}
function update()
{

}
function db_update()
{

}
function delete()
{

}
function db_delete()
{

}

protected function password_update($passwd=null)
{

}

}

?>