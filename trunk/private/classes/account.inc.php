<?php

/**
 * User account management
 * 
 * @package siteadm
 */
class account_manager extends db_object_manager
{

static protected $name = "account";

}

/**
 * User account
 * 
 * @package siteadm
 */
class account extends db_object
{

static protected $_name = "account";
static protected $_db_table = "account";

protected $password;

public $language_bin_list = array();

static public $_f = array
(
	"type" => array("type"=>"select", "list"=>array("user", "manager", "admin"), "nonempty"=>true),
	"name" => array("type"=>"string", "nonempty"=>true),
	"folder" => array("type"=>"string"),
	"actif" => array("type"=>"bool"),
	"manager_id" => array("type"=>"object", "otype"=>"account"),
	"offre_id" => array("type"=>"object", "otype"=>"offer"),
	"nom" => array("type"=>"string"),
	"prenom" => array("type"=>"string"),
	"email" => array("type"=>"string"),
	"societe" => array("type"=>"string"),
	"disk_quota_soft" => array("type"=>"int"),
	"disk_quota_hard" => array("type"=>"int"),
);

/**
 * @see db_object::__toString()
 */
function __toString()
{

return "$this->nom $this->prenom [$this->name]";

}

/**
 * @see db_object::url()
 */
function url()
{

return "account.php?id=$this->id";

}

// ACCESS

function manager()
{

if ($this->manager_id)
	return account($this->manager_id);

}

/**
 * @return offer
 */
function offer()
{

if  ($this->offre_id)
	return offer($this->offre_id);

}

function language_bin_list()
{

if (!$this->id)
	return array();

$list = array();
$query_string = "SELECT `language_bin_id` FROM `account_language_bin_ref` WHERE `account_id`='$this->id'";
$query = mysql_query($query_string);
while (list($id)=mysql_fetch_row($query))
{
	$list[] = language_bin($id);
}
return $list;

}

/**
 * OS (Linux) system user name
 * 
 * @return string
 */
public function system_name()
{

return "$this->name$this->id";

}
/**
 * OS (Linux) system group name
 * 
 * @return string
 */
public function system_group()
{

return $this->system_name();

}
/**
 * OS (Linux) system user id (uid) and group id (gid)
 * 
 * @return int
 */
public function system_id()
{

return (SITEADM_ACCOUNT_UID_MIN+$this->id);

}

/**
 * Returns root folder
 * 
 * @return string
 */
function folder()
{

return SITEADM_USER_DIR."/$this->folder";

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

// PERM

/**
 * @see db_object::insert_perm()
 */
static function insert_perm()
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
// Other (user or not connected)
else
{
	return false;
}

}

/**
 * @see db_object::update_perm()
 */
function update_perm()
{

// Admin
if (login()->perm("admin"))
{
	return "admin";
}
// Manager
elseif (($account=account($this->account_id)) && ($account->manager_id == login()->id))
{
	return "manager";
}
// User
elseif ($this->account_id == login()->id)
{
	return "user";
}
// Bad User or not connected
else
{
	return false;
}

}

/**
 * Returns if the account has the permission $name
 *  
 * @param string $name
 * @return boolean
 */
function perm($name)
{

if ($name == "manager")
	return (in_array($this->type, array("manager", "admin")));
elseif ($name == "admin")
	return ($this->type == "admin");

}

// UPDATE

/**
 * @see db_object::insert()
 */
public function insert($infos)
{

if (!($perm=static::insert_perm()) || !is_array($infos))
	return false;

if ($perm == "manager")
{
	$infos["manager_id"] = login()->id;
	$infos["type"] = "user";
}

if (!isset($infos["name"]) || !is_string($infos["name"]) || !strlen($infos["name"]))
	return false;

$infos["folder"] = $infos["name"];

return db_object::insert($infos);

}

/**
 * @see db_object::update()
 */
public function update($infos)
{

if (!($perm=$this->update_perm()))
	return false;

if ($perm == "user" || $perm == "manager")
{
	if (isset($infos["manager_id"]))
		unset($infos["manager_id"]);
	if (isset($infos["type"]))
		unset($infos["type"]);
}
if ($perm == "user")
{
	if (isset($infos["actif"]))
		unset($infos["actif"]);
	if (isset($infos["offre_id"]))
		unset($infos["offre_id"]);
}
if (isset($infos["folder"]))
	unset($infos["folder"]);

return db_object::update($infos);

}

// DB

/**
 * @see db_object::db_retrieve()
 */
protected function db_retrieve($id)
{

if (!db_object::db_retrieve($id))
	return false;

$this->language_bin_list = array();
$query_string = "SELECT `language_bin_id` FROM `account_language_bin_ref` WHERE `account_id`='$this->id'";
$query = mysql_query($query_string);
while(list($lang_id)=mysql_fetch_row($query))
	$this->language_bin_list[] = $lang_id;

return true;

}

/**
 * @see db_object::db_insert()
 */
protected function db_insert($infos)
{

if (db_object::db_insert($infos))
{
	if (isset($infos["language_bin_list"]) && is_array($infos["language_bin_list"]))
	{
		$query_list = array();
		foreach($infos["language_bin_list"] as $lang_id)
			$query_list[] = "('$this->id', '$lang_id')";
		if (count($query_list))
		{
			$query_string = "INSERT INTO account_language_bin_ref (account_id, language_bin_id) VALUES ".implode(" , ",$query_list);
			mysql_query($query_string);
		}
	}
	return true;
}
else
{
	return false;
}

}

/**
 * @see db_object::db_update()
 */
protected function db_update($infos)
{

$return = false;

if (isset($infos["language_bin_list"]))
{
	mysql_query("DELETE FROM `account_language_bin_ref` WHERE `account_id`='$this->id'");
	if (is_array($infos["language_bin_list"]))
	{
		$query_list = array();
		foreach($infos["language_bin_list"] as $lang_id)
			$query_list[] = "('$this->id', '$lang_id')";
		if (count($query_list))
		{
			$query_string = "INSERT INTO account_language_bin_ref (account_id, language_bin_id) VALUES ".implode(" , ",$query_list);
			mysql_query($query_string);
		}
	}
	$return = true;
}

return (db_object::db_update($infos) || $return);

}

// ROOT SCRIPTS

/**
 * Update OS account password
 * 
 * This method will also save the password in /path/to/account/private
 * @param string $passwd
 */
function password_update($passwd=null)
{

$passfile = $this->folder()."/private/passwd";
$passfile_crypt = $this->folder()."/private/passwd_crypt";

if (!$passwd) // TODO : tests de robustesse de mot de passe
{
	exec("makepasswd --chars 8 > $passfile");
	$passwd = fread(fopen($passfile,"r"), filesize($passfile));
	$passwd = str_replace(array("\r\n","\n","\r"), "", $passwd);
}
else
{
	fwrite(fopen($passfile_crypt,"w"), $passwd);
}
$this->passwd = $passwd;

// Encrypt password
exec("makepasswd --crypt --clearfrom $passfile > $passfile_crypt");
unlink("$passfile");
exec("chmod 600 $passfile_crypt");
$passwd_crypt = array_pop(explode(" ",fread(fopen($passfile_crypt,"r"),filesize($passfile_crypt))));
$passwd_crypt = str_replace(array("\r\n","\n","\r"), "", $passwd_crypt);

// Update password in database
mysql_query("UPDATE `account` SET `password`='$passwd', `password_md5`=MD5('$passwd') WHERE `id`='$this->id'");

// Update system password
exec("usermod -p $passwd_crypt ".$this->system_name());

}

/**
 * Update quota
 */
function quota_update()
{

if ($offer=$this->offer())
{
	if ($offer->disk_quota_soft)
		exec("quotatool -u ".$this->system_name()." -b -q ".($offer->disk_quota_soft*1048576)." /");
	else
		exec("quotatool -u ".$this->system_name()." -b -q 0 /");
	if ($offer->disk_quota_hard)
		exec("quotatool -u ".$this->system_name()." -b -l ".($offer->disk_quota_soft*1048576)." /");
	else
		exec("quotatool -u ".$this->system_name()." -b -l 0 /");
}
else
{
	exec("quotatool -u ".$this->system_name()." -b -q 0 -l 0 /");
}

}

/**
 * Create a subfolder in the account root and eventually chown it
 *
 * @param string $folder
 * @param string $mode
 * @param string $usergroup
 */
function mkdir($folder, $mode="750", $usergroup=null)
{

if (substr($folder, 0, 1) != "/")
	$folder = $this->folder()."/".$folder;
exec("mkdir -m $mode \"".$folder."\"");
$this->chown($folder, $usergroup);

}

/**
 * Delete a folder and all subfolders
 *
 * @param string $folder
 */
function rmdir($folder)
{

if ($this->folder() && $folder)
	exec("rm -Rf \"".$this->folder()."/$folder\"");

}

/**
 * Delete a folder and all subfolders
 *
 * @param string $folder
 */
function rm($file)
{

if (file_exists($file=$this->folder()."/$file"))
	exec("rm \"".$file."\"");

}

/**
 * Chown a file in the account root
 * 
 * @param string $file
 * @param string $usergroup
 */
function chown($file, $usergroup=null)
{

if (is_null($usergroup))
	$usergroup = $this->system_name().".".$this->system_group();
elseif (!is_numeric($pos=strpos($usergroup, ".")))
	$usergroup = $usergroup.".".$this->system_group();
elseif ($pos == 0)
	$usergroup = $this->system_name().".".$usergroup;

if (substr($folder, 0, 1) != "/")
	$folder = $this->folder()."/".$folder;

file_chown($file, $usergroup);

}

/**
 * Create replacement map for template files
 * 
 * @return array
 */
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

/**
 * Copy a template file into the account root
 * 
 * @param string $file_from
 * @param string $file_to
 * @param array $replace_map
 * @param string $mode
 * @param string $usergroup
 */
function copy_tpl($file_from, $file_to, $replace_map=array(), $mode="0640", $usergroup=null)
{

if (is_null($usergroup))
	$usergroup = $this->system_id().".".$this->system_id();

if (substr($file_to, 0, 1) != "/")
	$file_to = $this->folder()."/".$file_to;

copy_tpl($file_from, $file_to, $replace_map, $mode, $usergroup);

}

/**
 * Create directory structure
 */
public function script_structure()
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
// eMail
$this->mkdir("mail", "700");

}

/**
 * @see db_object::script_insert()
 */
function script_insert()
{

// Add system group
exec("groupadd -g ".$this->system_id()." ".$this->system_group());
// Add system user
exec("useradd -u ".$this->system_id()." -g ".$this->system_id()." -d ".$this->folder()."/public"." -s /bin/bash ".$this->system_name());

// Add www-data in system group
exec("addgroup ".WEBSERVER_USER." ".$this->system_group());
// Add user in siteadm_user group,
// so that sshd_config section authorize only internal-sftp for users matching group siteadm_user
exec("addgroup ".$this->system_name()." siteadm_user");

// Root folder with private subfolder
$this->mkdir("", "750", "root");

$this->script_structure();
$this->script_update();
$this->script_password_update();

}

/**
 * @see db_object::script_update()
 */
function script_update()
{

}

function script_password_update($password=null)
{

$this->password_update($password);

}

}

?>