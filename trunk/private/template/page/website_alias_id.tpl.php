<?php

$domain = $website_alias->domain();
$account = $domain->account();

$form_submit_name = "_website_alias_update";
$form_submit_text = "Mettre à jour";

include "template/form/website_alias.tpl.php";

?>