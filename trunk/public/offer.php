<?php

require_once "config/config.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common.inc.php";
require_once SITEADM_PRIVATE_DIR."/include/common_logged.inc.php";

// AUTH

if (!login()->perm("admin"))
{
	header("Location: index.php");
	die("Autorisation requise");
}

$menu = "offer";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">

<head>
<?php include "template/inc/html_head.tpl.php"; ?>
</head>

<body>
<?php

include "template/inc/menu.tpl.php";

if (isset($_GET["id"]) && ($offer=offer($_GET["id"])))
{
	include "template/page/offer_id.tpl.php";
}
else
{
	include "template/page/offer_list.tpl.php";
}

include "template/page/offer_add_form.tpl.php";

?>
</body>

</html>
