<?php

/**
 * Shared (common) account
 * 
 * @package siteadm
 */
class common
{

public $id = 0;
	
public $name = "common";

function system_id()
{

return "65534"; // User nobody/nogroup

}
function system_name()
{

return "nobody"; // User nobody/nogroup

}
function system_group()
{

return "www-data"; // User nobody/nogroup

}

function language_bin_list()
{

$list = array();
$query_string = "SELECT id FROM langage_bin";
$query = mysql_query($query_string);
while (list($id)=mysql_fetch_row($query))
{
	$list[] = language_bin($id);
}
return $list;

}

function folder()
{

return SITEADM_ROOT."/common";

}

function mkdir($folder, $mode="750", $usergroup=null)
{

exec("mkdir -m $mode \"".$this->folder()."/$folder\"");

$this->chown($folder, $usergroup);

}

function chown($file, $usergroup=null)
{

if (is_null($usergroup))
	$usergroup = $this->system_name().".".$this->system_group();
elseif (!is_numeric($pos=strpos($usergroup, ".")))
	$usergroup = $usergroup.".".$this->system_group();
elseif ($pos == 0)
	$usergroup = $this->system_name().".".$usergroup;

file_chown($this->folder()."/".$file, $usergroup);

}

function replace_map()
{

$map = array
(
	"{ACCOUNT_ID}" => "0",
	"{ACCOUNT_NAME}" => $this->name,
	"{ACCOUNT_SYSTEM_ID}" => $this->system_id(),
	"{ACCOUNT_SYSTEM_NAME}" => $this->system_name(),
	"{ACCOUNT_SYSTEM_GROUP}" => $this->system_group(),
	"{ACCOUNT_EMAIL}" => SHARED_EMAIL,
	"{ACCOUNT_ROOT}" => $this->folder(),
	"{ACCOUNT_PUBLIC}" => $this->folder()."/public",
	"{ACCOUNT_SYSTEM_ID}" => $this->system_id(),
	"{ACCOUNT_SYSTEM_NAME}" => $this->system_name(),
	"{ACCOUNT_TMP_DIR}" => $this->folder()."/tmp",
	"{CGI_ROOT}" => $this->folder()."/cgi-bin",
	"{PHP_TMP_DIR}" => $this->folder()."/tmp",
	"{PHP_BASEDIR}" => $this->folder()."/public",
);

return array_merge(replace_map(), $map);

}

function copy_tpl($file_from, $file_to, $replace_map=array(), $mode="0644", $usergroup=null)
{

copy_tpl($file_from, $this->folder()."/".$file_to, $replace_map, $mode, $usergroup);
$this->chown($file_to, $usergroup);

}

}

?>