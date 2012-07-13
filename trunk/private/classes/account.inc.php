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
	"type" => array("type"=>"select", "list"=>array("user", "manager", "admin", 'shared'), "nonempty"=>true),
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

function phpapp_list()
{

	$list = array();
	$query_string = "SELECT t1.id FROM phpapp as t1 WHERE t1.account_id IS NULL OR t1.account_id = '$this->id'";
	$query = mysql_query($query_string);
	while(list($id)=mysql_fetch_row($query))
		$list[] = phpapp($id);
	return $list;

}

function phppool_list()
{

	$list = array();
	$query_string = "SELECT t1.id FROM phppool as t1 LEFT JOIN phpapp AS t2 ON t2.id=t1.phpapp_id AND (t2.account_id IS NULL OR t2.account_id='$this->id') WHERE t1.account_id IS NULL OR t1.account_id = '$this->id'";
	$query = mysql_query($query_string);
	while(list($id)=mysql_fetch_row($query))
		$list[] = phppool($id);
	return $list;

}

function phpext_list($language_bin_id=null)
{

	$list = array();
	$query_string = "SELECT language_php_ext.*, if(language_php_bin_ext_ref.phpext_id, 1, 0) as `already`, if (account_php_ext_ref.account_id, 1, 0) as `authorized`
	FROM language_php_ext
	LEFT JOIN account_php_ext_ref ON language_php_ext.id=account_php_ext_ref.phpext_id AND account_php_ext_ref.account_id='".$this->id."'
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
 * OS (Linux) system user id (uid) and group id (gid)
 * 
 * @return int
 */
public function system_id()
{

return (ACCOUNT_UID_MIN+$this->id);

}
/**
 * OS (Linux) system user name
 * 
 * @return string
 */
public function system_user()
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

return $this->system_user();

}
/**
 * PHP user ID
 * @return int
 */
function php_id()
{

return (PHP_UID_MIN+$this->id);

}
function php_user()
{

return "php_".$this->name;

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

return (EMAIL_UID_MIN+$this->id);

}
function email_user()
{

return "email_".$this->name;

}
function email_group()
{

return $this->system_group();

}

/**
 * Update usergroup relative to account
 * @param string $usergroup
 */
function usergroup(&$usergroup)
{

if (is_null($usergroup))
	$usergroup = $this->system_user().":".$this->system_group();
elseif (!is_numeric($pos=strpos($usergroup, ":")))
	$usergroup = $usergroup.":".$this->system_group();
elseif ($pos == 0)
	$usergroup = $this->system_user().":".$usergroup;

}

/* FOLDERS */

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
 * Public folder
 *
 * @return string
 */
function public_folder()
{

return $this->folder()."/public";

}
/**
 * Private folder
 *
 * @return string
 */
function private_folder()
{

return $this->folder()."/private";

}
/**
 * CGI-BIN folder
 *
 * @return string
 */
function cgi_folder()
{

return $this->folder()."/cgi-bin";

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
/**
 * Sessions folder
 *
 * @return string
 */
function session_folder()
{

return $this->folder()."/cookies";

}
/**
 * Dossier de stockage des emails associés au compte
 * @return string
 */
function email_folder()
{

return $this->folder()."/mail";

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
public function perm($name)
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

/* REPLACE MAP */

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
	"{ACCOUNT_SYSTEM_USER}" => $this->system_user(),
	"{ACCOUNT_SYSTEM_GROUP}" => $this->system_group(),
	"{ACCOUNT_EMAIL}" => $this->email,
	"{ACCOUNT_ROOT}" => $this->folder(),
	"{ACCOUNT_PUBLIC}" => $this->public_folder(),
	"{ACCOUNT_TMP_DIR}" => $this->tmp_folder(),
	"{ACCOUNT_CGI_DIR}" => $this->cgi_folder(),
	"{PHP_TMP_DIR}" => $this->tmp_folder(),
	"{PHP_BASEDIR}" => $this->public_folder(),
);

replace_map_merge($map, replace_map());

return $map;

}

/* ROOT METHODS */

/**
 * Update OS account password
 * 
 * This method will also save the password in /path/to/account/private
 * @param string $passwd
 */
protected function password_update($passwd=null)
{

$passfile = $this->folder()."/private/passwd";
$passfile_crypt = $this->folder()."/private/passwd_crypt";

if (!$passwd) // TODO : tests de robustesse de mot de passe
{
	exec("makepasswd --chars 8 > $passfile");
	$passwd = file_get_contents($passfile);
	$passwd = str_replace(array("\r\n","\n","\r"), "", $passwd);
}
else
{
	filesystem::write($passfile_crypt, $passwd);
}
$this->passwd = $passwd;

// Encrypt password
exec("makepasswd --crypt --clearfrom $passfile > $passfile_crypt");
unlink($passfile);
filesystem::chmod($passfile_crypt, "600");
$passwd_crypt = array_pop(explode(" ",fread(fopen($passfile_crypt,"r"),filesize($passfile_crypt))));
$passwd_crypt = str_replace(array("\r\n","\n","\r"), "", $passwd_crypt);

// Update password in database
mysql_query("UPDATE `account` SET `password`='$passwd' WHERE `id`='$this->id'");

// Update system password
exec("usermod -p $passwd_crypt ".$this->system_user());

}

/**
 * Update quota
 */
protected function quota_update()
{

// Limitations by offer
if ($offer=$this->offer())
{
	if ($offer->disk_quota_soft)
		exec("quotatool -u ".$this->system_user()." -b -q ".($offer->disk_quota_soft*1048576)." /");
	else
		exec("quotatool -u ".$this->system_user()." -b -q 0 /");
	if ($offer->disk_quota_hard)
		exec("quotatool -u ".$this->system_user()." -b -l ".($offer->disk_quota_soft*1048576)." /");
	else
		exec("quotatool -u ".$this->system_user()." -b -l 0 /");
}
// No limitations
else
{
	exec("quotatool -u ".$this->system_user()." -b -q 0 -l 0 /");
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

/**
 * Chown a file in the account root
 *
 * @param string $file
 * @param string $usergroup
 */
function chmod($file, $mode="750")
{

$this->subfolder($file);

filesystem::chmod($file, $mode);

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

$this->subfolder($file_to);
$this->usergroup($usergroup);

copy_tpl($file_from, $file_to, $replace_map, $mode, $usergroup);

}

/* ROOT SCRIPTS */

/**
 * Update account password
 * @param string $password
 */
function script_password_update($password=null)
{

$this->password_update($password);

}

/**
 * Create directory structure
 */
public function script_structure()
{

$this->mkdir("", "750", "root");
filesystem::setacl($this->folder(), WEBSERVER_USER);

// SSH
$this->mkdir(".ssh", "700");

// Config files
$this->mkdir("conf", "750", "root");	
filesystem::setacl($this->folder()."/conf", WEBSERVER_USER);
// Awstats
$this->mkdir("conf/awstats", "750", "root");
// Apache
$this->mkdir("conf/apache", "750", "root");
filesystem::setacl($this->folder()."/conf/apache", WEBSERVER_USER);
// PHP
$this->mkdir("conf/php", "750", "root");
$this->mkdir("conf/php/pool", "755", "root");
$this->mkdir("conf/php/ext", "755", "root");
$this->mkdir("conf/php/vhost", "755", "root");
// Fetchmail
$this->mkdir("conf/fetchmail", "750", "root");
// CRON
$this->mkdir("conf/cron", "750", "root");

// CGI-BIN
$this->mkdir("cgi-bin", "750", "root");
filesystem::setacl($this->cgi_folder(), WEBSERVER_USER);

// Backup
$this->mkdir("backup", "750", "root");
$this->mkdir("backup/mysql", "755", "root");

// Logs
$this->mkdir("log", "750", "root");
$this->mkdir("log/apache", "1755", "root");
$this->mkdir("log/php", "1775", "root");
$this->mkdir("log/awstats", "1755", "root");

// Temp (PHP)
$this->mkdir("tmp", "1777", "root");
// Cookies (PHP)
$this->mkdir("cookies", "1770", "root");
// Socket (PHP)
$this->mkdir("socket", "750", "root");
filesystem::setacl($this->socket_folder(), WEBSERVER_USER);

// Private data & config
$this->mkdir("private", "750", "root");
$this->mkdir("private/data", "750");
$this->mkdir("private/ftp", "750");

// Public websites
$this->mkdir("public", "750", "root");
filesystem::setacl($this->public_folder(), WEBSERVER_USER);
$this->mkdir("public/data", "750");
$this->mkdir("public/ftp", "755");

// eMail
$this->mkdir("mail", "700", $this->email_user());

}

/**
 * @see db_object::script_insert()
 */
function script_insert()
{

// Add system group
exec("groupadd -g ".$this->system_id()." ".$this->system_group());
// Add system user
exec("useradd -u ".$this->system_id()." -g ".$this->system_id()." -d ".$this->public_folder()." -s /bin/bash ".$this->system_user());

// Add system php user
exec("useradd -u ".$this->php_id()." -g ".$this->system_id()." -d ".$this->public_folder()." -s /bin/false ".$this->php_user());

// Add system email user
exec("useradd -u ".$this->email_id()." -g ".$this->system_id()." -d ".$this->email_folder()." -s /bin/false ".$this->email_user());

// Add user in siteadm_account group,
// so that sshd_config section authorize only internal-sftp for users matching group ACCOUNT_SYSTEM_GROUP
exec("addgroup ".$this->system_user()." ".ACCOUNT_SYSTEM_GROUP);

// User Root folder
$this->mkdir("", "750", "root");

$this->script_structure();
$this->script_update();
$this->script_password_update();

}

}

?>