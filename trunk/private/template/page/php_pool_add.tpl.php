<h1>Pool de processus applicatifs PHP : AJOUT</h1>
<?php

$phppool = new phppool();

$form_submit_name = "_phppool_add";
$form_submit_text = "Ajouter";

if (isset($_GET["account_id"]))
	$phppool->account_id = $_GET["account_id"];

// A vérifier absolument !!
foreach($_POST as $i=>$j)
{
	$phppool->$i = $j;
}

include "template/form/phppool.tpl.php";

?>