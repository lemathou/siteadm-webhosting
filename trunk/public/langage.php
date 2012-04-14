<?php

require_once "include/common.inc.php";

// AUTH

if (!login()->perm("admin"))
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "langage";

// ACTIONS

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
</head>

<body>
<?php

include "template/inc/menu.tpl.php";

?>
<p><a href="?list">Liste</a> | <a href="?add">Ajouter</a></p>
<hr />
<?php

if (isset($_GET["add"]))
	include "template/page/langage_add.tpl.php";
else
	include "template/page/langage.tpl.php";
	
?>
</body>

</html>
