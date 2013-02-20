<p>Ajouter une base de donnée MySQL :</p>

<?php

$db = new db();

$form_submit_name = "_db_add";
$form_submit_text = "Ajouter";

include "template/form/db.tpl.php";

?>