<h3>Ajouter une boite email : <?php echo $domain->name; ?></h3>
<?php

$email = new email();

$form_submit_name = "_email_add";
$form_submit_text = "Ajouter";

include "template/form/email.tpl.php";

?>
