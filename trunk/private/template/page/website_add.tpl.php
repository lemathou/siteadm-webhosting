<h1>Site web : AJOUT</h1>
<?php
$account = $domain->account();
if ($account->id) { ?>
<p>
	Compte de gestion du domaine
	<?php echo $domain->link(); ?>
	:
	<?php echo $account->link(); ?>
</p>
<?php if ($manager=$account->manager()) { ?>
<p>
	Manager du compte :
	<?php echo $manager->link(); ?>
</p>
<?php } ?>
<?php } else { ?>
<p>
	Nom de domaine
	<?php echo $domain->link(); ?>
	sans compte de gestion
</p>
<?php }

$website = new website();
$website->domain_id = $domain->id;

$form_submit_name = "_website_add";
$form_submit_text = "Ajouter";

include "template/form/website.tpl.php";

?>
