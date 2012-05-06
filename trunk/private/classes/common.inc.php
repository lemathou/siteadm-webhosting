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
public $email = SHARED_EMAIL;
public $folder = "common";

function __toString()
{

return $this->name;

}

function system_id()
{

return ACCOUNT_UID_MIN; // User nobody/nogroup

}
function system_name()
{

return "siteadm_common"; // User nobody/nogroup

}
function system_group()
{

return "siteadm_common"; // User nobody/nogroup

}
function php_user()
{

return "php_common";

}
function php_group()
{

return $this->system_group();

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

/**
 * Update usergroup relative to account
 * @param string $usergroup
 */
function usergroup(&$usergroup)
{

if (is_null($usergroup))
	$usergroup = $this->system_name().".".$this->system_group();
elseif (!is_numeric($pos=strpos($usergroup, ".")))
	$usergroup = $usergroup.".".$this->system_group();
elseif ($pos == 0)
	$usergroup = $this->system_name().".".$usergroup;

}

/* FOLDERS */

function folder()
{

return SITEADM_USER_DIR."/$this->folder";

}
/**
 * Update folder relative to account
 * @param string $folder
 * @return string
 */
function subfolder(&$folder)
{

if (substr($folder, 0, 1) != "/")
	$folder = $this->folder()."/".$folder;

}

/**
 * Public folder for FTP ans Websites
 */
function public_folder()
{

return $this->folder()."/public";

}
/**
 * Private folder for FTP ans Websites
 */
function private_folder()
{

return $this->folder()."/private";

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
/**
 * PHP sockets folder
 * 
 * @return string
 */
function socket_folder()
{

return $this->folder()."/socket";

}
/**
 * Temp folder
 *
 * @return string
 */
function tmp_folder()
{

return $this->folder()."/tmp";

}

/* SCRIPTS */

function mkdir($folder, $mode="750", $usergroup=null)
{

$this->subfolder($folder);
$this->usergroup($usergroup);

filesystem::mkdir($folder);
$this->chmod($folder, $mode);
$this->chown($folder, $usergroup);

}

/**
 * Delete a folder and all subfolders
 *
 * @param string $folder
 */
function rmdir($folder)
{

$this->subfolder($folder);

if ($folder && $folder!= "/")
	filesystem::rmdir($folder);

}

/**
 * Delete a folder and all subfolders
 *
 * @param string $folder
 */
function rm($file)
{

$this->subfolder($file);

filesystem::unlink($file);

}

/**
 * Chown a file in the account root
 *
 * @param string $file
 * @param string $usergroup
 */
function chown($file, $usergroup=null, $recursive=false)
{

$this->subfolder($file);
$this->usergroup($usergroup);

filesystem::chown($file, $usergroup, $recursive);

}

function replace_map()
{

$map = array
(
	"{ACCOUNT_ID}" => $this->id,
	"{ACCOUNT_NAME}" => $this->name,
	"{ACCOUNT_SYSTEM_ID}" => $this->system_id(),
	"{ACCOUNT_SYSTEM_NAME}" => $this->system_name(),
	"{ACCOUNT_SYSTEM_GROUP}" => $this->system_group(),
	"{ACCOUNT_EMAIL}" => $this->email,
	"{ACCOUNT_ROOT}" => $this->folder(),
	"{ACCOUNT_PUBLIC}" => $this->public_folder(),
	"{ACCOUNT_SYSTEM_ID}" => $this->system_id(),
	"{ACCOUNT_SYSTEM_NAME}" => $this->system_name(),
	"{ACCOUNT_TMP_DIR}" => $this->tmp_folder(),
	"{CGI_ROOT}" => $this->folder()."/cgi-bin",
	"{PHP_TMP_DIR}" => $this->tmp_folder(),
	"{PHP_BASEDIR}" => $this->public_folder(),
);

return array_merge(replace_map(), $map);

}

function copy_tpl($file_from, $file_to, $replace_map=array(), $mode="0644", $usergroup=null)
{

$this->subfolder($file_to);
$this->usergroup($usergroup);

copy_tpl($file_from, $file_to, $replace_map, $mode, $usergroup);

}

// ROOT SCRIPTS

function script_structure()
{

$this->mkdir("", "750", "root");
$this->mkdir("conf", "750", "root");
// Awstats
$this->mkdir("conf/awstats", "1750", "root");
// Apache
$this->mkdir("conf/apache", "755", "root");
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
$this->mkdir("backup/mysql", "755", "root");
// Logs
$this->mkdir("log", "750", "root");
$this->mkdir("log/apache", "1755", "root");
$this->mkdir("log/php", "1775", "root");
$this->mkdir("log/awstats", "1755", "root");
// Temp (PHP)
$this->mkdir("tmp", "1770", "root");
// Cookies (PHP)
$this->mkdir("cookies", "1770", "root");
// Private data & config
$this->mkdir("private", "750", "root");
$this->mkdir("private/config", "750");
$this->mkdir("private/scripts", "750");
$this->mkdir("private/data", "750");
$this->mkdir("private/ftp", "750");
// Public websites
$this->mkdir("public", "750", "root");
exec("setfacl -m u:".WEBSERVER_USER." rx ".$this->folder());
exec("setfacl -m u:".WEBSERVER_USER." rx ".$this->public_folder());
$this->mkdir("public/config", "750");
$this->mkdir("public/scripts", "750");
$this->mkdir("public/data", "750");
$this->mkdir("public/ftp", "750");
// eMail
$this->mkdir("mail", "700");

}

/**
 * 
 */
function script_insert()
{

// Add system group
exec("groupadd -g ".$this->system_id()." ".$this->system_group());
// Add system user
exec("useradd -u ".$this->system_id()." -g ".$this->system_id()." -d ".$this->public_folder()." -s /bin/bash ".$this->system_name());

// Add user in siteadm_account group,
// so that sshd_config section authorize only internal-sftp for users matching group siteadm_user
exec("addgroup ".$this->system_name()." ".ACCOUNT_SYSTEM_GROUP);

// Root folder with private subfolder
$this->mkdir("", "750", "root");

$this->script_structure();

}

}

?>