<?php
$domain = $website->domain();
$account = $domain->account();
?>
<h1>Site web : <?php echo $website->name(); ?></h1>
<p><a href="http://<?php echo $website->name(); ?>" target="_blank">Voir le site</a></p>
<?php
$account = $domain->account();
$phppool = phppool($website->phppool_id);
if ($account->id) { ?>
<p>Compte de gestion du domaine <?php echo $domain->link(); ?> : <?php echo $account->link(); ?></p>
<?php if ($manager=$account->manager()) { ?>
<p>Manager du compte : <?php echo $manager->link(); ?></p>
<?php } ?>
<?php } else { ?>
<p>Nom de domaine <?php echo $domain->link(); ?> sans compte de gestion</p>
<?php } ?>

<div style="float: right;width: 320px;" class="cadre">
<h3>Website acces log</h3>
<div style="border: 1px black solid;padding: 4px;max-height:200px; overflow: auto;">
<?php echo $account->root_tail($website->accesslog_file()); ?>
</div>
<h3>Website Error log</h3>
<div style="border: 1px black solid;padding: 4px;max-height:200px; overflow: auto;">
<?php echo $account->root_tail($website->errorlog_file()); ?>
</div>
<h3> Website PHP Error log</h3>
<div style="border: 1px black solid;padding: 4px;max-height:200px; overflow: auto;">
<?php echo $account->root_tail($website->phperrorlog_file()); ?>
</div>
</div>

<?php

$form_submit_name = "_website_update";
$form_submit_text = "Mettre à jour";

include "template/form/website.tpl.php";

?>
