<?php

require_once "config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common_logged.inc.php";

// AUTH

if (!login()->id)
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "folder";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"
	dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
</head>

<body>
	<?php

	include "template/inc/menu.tpl.php";

	$origin = login()->folder();

	// Context parameters
	$path =  (!isset($_GET["path"]) || !is_string($_GET["path"]) || !@is_dir($origin."/".$_GET["path"])) ? "." : $_GET["path"];
	$hidden = (empty($_GET["hidden"])) ? "0" : "1";
	$file_choose_name = (empty($_GET["file_choose_name"])) ? "" : $_GET["file_choose_name"];

	// Actions
	if (isset($_POST["folder_create"]) && is_string($name=$_POST["folder_create"]))
	{
		filesystem::mkdir("$origin/$path/$name");
	}
	if (isset($_POST["file_delete"]) && is_string($name=$_POST["file_delete"]))
	{
		filesystem::rmdir("$origin/$path/$name");
	}
	if (isset($_POST["file_rename"]) && is_string($name=$_POST["file_rename"]) && preg_match("/([[\.]*[a-z0-9_-]+[a-z0-9_\.-])/i", $name) && isset($_POST["file_rename_new"]) && is_string($newname=$_POST["file_rename_new"]) && preg_match("/([[\.]*[a-z0-9_-]+[a-z0-9_\.-])/i", $newname) && file_exists("$origin/$path/$name") && is_writable("$origin/$path") && is_writable("$origin/$path/$name"))
	{
		filesystem::rename("$origin/$path/$name", "$origin/$path/$newname");
	}

	// Display
	filesystem::folder_disp($path, array("origin"=>$origin, "limit"=>1, "hidden"=>$hidden, "file_choose_name"=>$file_choose_name));

	?>
</body>

</html>
