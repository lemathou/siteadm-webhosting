<h3>
	Ajouter un alias ou une redirection :
	<?php echo $domain->name; ?>
</h3>
<?php

$email_alias = new email_alias();

$form_submit_name = "_email_alias_add";
$form_submit_text = "Ajouter";

include "template/form/email_alias.tpl.php";

?>