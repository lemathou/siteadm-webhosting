<?php

$domain = $website_alias->domain();
$account = $website_alias->account();

$form_submit_name = "_website_alias_update";
$form_submit_text = "Mettre à jour";

include "template/form/website_alias.tpl.php";

?>