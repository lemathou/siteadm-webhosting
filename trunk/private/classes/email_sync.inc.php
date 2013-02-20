<?php

/**
 * eMail sync management
 * 
 * @package siteadm
 */
class email_sync_manager extends db_object_manager
{

static protected $name = "email_sync";
static protected $_db_table = "email_sync";

function migrate($hostname)
{



}

}

/**
 * eMail sync
 * 
 * @package siteadm
 */
class email_sync extends db_object
{

static protected $_name = "email_sync";
static protected $_db_table = "email_sync";

static public $_f = array
(
	"email_id" => array("type"=>"object", "otype"=>"email", "nonempty"=>true),
	"servertype" => array("type"=>"select", "list" => array('pop','pops','imap','imaps')),
	"encryption" => array("type"=>"select", "list" => array('plain','login','md5')),
	"hostname" => array("type"=>"string"),
	"username" => array("type"=>"string"),
	"password" => array("type"=>"string"),
	"actif" => array("type"=>"boolean", "default"=>"1"),
);

/* ACCESS */

/**
 * Returns associated email account
 * 
 * @return email
 */
function email()
{

if ($this->email_id)
	return email($this->email_id);

}

/* SCRIPTS */

/**
 * Options : 
 * -i : initialize mailbox (remove existing messages first)
 * -m : mailbox list (eg "Inbox, Drafts, Notes". Default is all mailboxes)
 * -R : include submailboxes when used with -m
 * -T : copy custom flags
 * -U : Update mode, don't copy messages that already exists
 * -q : quiet mode
 * -d : debug
 * -r : remove msgs from source mbx after copying
 * 
 * @param bool $purge
 */
private function sync($purge=false, $init=false)
{

$options = "-R -T -U";
if (false) // quiet
	$options .= " -q";
if (false) // debugging
	$options .= " -d";
if ($purge)
	$options .= " -r";
if ($init)
	$options .= " -i";
if ($this->encryption == "md5")
	$encryption = "/CRAM-MD5";
else
	$encryption = "";

if ($email = $this->email())
{
	$command = SITEADM_SCRIPT_DIR."/imapcopy.pl -S ".$this->hostname."/".$this->username."/".$this->password.$encryption." -D localhost/".$email->name()."/".$email->password." ".$options;
	exec($command, $exec);
	echo implode("\n", $exec)."\n";
}

}

/* ROOT SCRIPTS */

function script_sync()
{

$this->sync();

}

}

?>
