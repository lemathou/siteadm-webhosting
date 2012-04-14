<h1><?=$domain->name?></h1>

<?php
$query_website_where = "WHERE t1.domain_id='$domain->id'";
include "template/page/website_list.tpl.php";
include "template/page/website_alias_list.tpl.php";
?>
