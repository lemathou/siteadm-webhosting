<h3>
	Base de donnée MySQL :
	<?php echo $db->name; ?>
</h3>

<?php

$form_submit_name = "_db_update";
$form_submit_text = "Mettre à jour";

include "template/form/db.tpl.php";

?>