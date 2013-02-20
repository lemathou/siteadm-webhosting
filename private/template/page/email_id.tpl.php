<h3>Mettre à jour une boite email : <?php echo $email->name."@".$email->domain()->name; ?></h3>
<?php

$form_submit_name = "_email_update";
$form_submit_text = "Mettre à jour";

include "template/form/email.tpl.php";

?>
