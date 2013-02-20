<h3>Modifier un alias ou une redirection :</h3>
<?php

$domain = $email_alias->domain();

$form_submit_name = "_email_alias_update";
$form_submit_text = "Mettre à jour";

include "template/form/email_alias.tpl.php";

?>