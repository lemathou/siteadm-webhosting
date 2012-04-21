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
$query_string = "SELECT id FROM language_bin";
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
/**
 * Log folder
 *
 * @return string
 */
function log_folder()
{

	return $this->folder()."/log";

}
/**
 * Conf folder
 *
 * @return string
 */
function conf_folder()
{

	return $this->folder()."/conf";

}

function mkdir($folder, $mode="750", $usergroup=null)
{

if (substr($folder, 0, 1) != "/")
	$folder = $this->folder()."/".$folder;
exec("mkdir -m $mode \"".$folder."\"");

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

if (substr($file, 0, 1) != "/")
	$file = $this->folder()."/".$file;

file_chown($file, $usergroup);

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

if (substr($file_to, 0, 1) != "/")
	$file_to = $this->folder()."/".$file_to;

copy_tpl($file_from, $file_to, $replace_map, $mode, $usergroup);
$this->chown($file_to, $usergroup);

}

// ROOT SCRIPTS

/**
 * 
 */
function script_insert()
{
	
$this->mkdir("", "750", "root");
$this->mkdir("conf", "750", "root");
// Awstats
$this->mkdir("conf/awstats", "1750", "root");
// Apache
$this->mkdir("conf/apache", "755", "root");
// nginx
$this->mkdir("conf/nginx", "755", "root");
// PHP
$this->mkdir("conf/php", "755", "root");
$this->mkdir("conf/php/pool", "755", "root");
$this->mkdir("conf/php/ext", "755", "root");
$this->mkdir("conf/php/vhost", "755", "root");
// Fetchmail
$this->mkdir("conf/fetchmail", "755", "root");
// CRON
$this->mkdir("conf/cron", "755", "root");
// CGI-BIN
$this->mkdir("cgi-bin", "750", "root");
// Backup
$this->mkdir("backup", "750", "root");
// Backup
$this->mkdir("backup/mysql", "755", "root");
// Logs
$this->mkdir("log", "750", "root");
$this->mkdir("log/apache", "1750", "root");
$this->mkdir("log/php", "1750", "root");
$this->mkdir("log/awstats", "1750", "root");
// Temp (PHP)
$this->mkdir("tmp", "1770");
// Cookies (PHP)
$this->mkdir("cookies", "1770");
// Private data & config
$this->mkdir("private", "750", "root");
$this->mkdir("private/config", "750");
$this->mkdir("private/scripts", "750");
$this->mkdir("private/data", "750");
// Public websites
$this->mkdir("public", "750", "root");
$this->mkdir("public/config", "750");
$this->mkdir("public/scripts", "750");
$this->mkdir("public/data", "750");
$this->mkdir("public/ftp", "750");

}

}

?>