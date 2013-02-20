<h1>Processus PHP parent : AJOUT</h1>

<?php

$form_submit_name = "_phpapp_add";
$form_submit_text = "Ajouter";

if (isset($_GET["account_id"]))
	$phpapp->account_id = $_GET["account_id"];

// A vérifier absolument !!
foreach($_POST as $i=>$j)
{
	$phpapp->$i = $j;
}

include "template/form/phpapp.tpl.php";

?>

