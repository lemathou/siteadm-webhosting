<?php
$account = $domain->account();
?>
<h3>Nom de domaine : <?php echo $domain->name; ?></h3>

<div style="float: right;height: 250px;overflow:auto;width: 400px;" class="cadre">
<h3>Données Whois</h3>
<p><?php
exec("whois $domain->name", $whois);
$whois = utf8_encode(implode("\n", $whois));
echo str_replace("\n", "<br />", $whois);
?></p>
</div>

<?php if ($account->id) { ?>
<div style="width: 600px;" class="cadre">
<p>Account : <?php echo $account->link(); ?></p>
<?php if ($manager=account($account->manager_id)) { ?>
<p>Manager : <?php echo $manager->link(); ?></p>
<?php } ?>
</div>
<?php } ?>

<div style="width: 600px;" class="cadre">
<p>Registrar : </p>
<p>Date de création : <?php echo $domain->creation_date; ?></p>
<p>Date de renouvellement : <?php echo $domain->renew_date; ?></p>
<p>Serveurs DNS : </p>
</div>

<div style="width: 600px;" class="cadre">
<h3>Gestion DNS</h3>
</div>

<div style="width: 600px;" class="cadre">
<h3><a href="email.php?domain_id=<?php echo $domain->id; ?>">Emails</a></h3>
<p>Boites email : <?php echo $domain->email_nb; ?></p>
<p>Alias / Redirections email : <?php echo $domain->email_alias_nb; ?></p>
<h3><a href="website.php?domain_id=<?php echo $domain->id; ?>">Sites web</a></h3>
<p>Sites web : <?php echo $domain->website_nb; ?></p>
<p>Alias / Redirections web : <?php echo $domain->website_alias_nb; ?></p>
</div>

<?php

$form_submit_name = "_domain_update";
$form_submit_text = "Mettre à jour";

include "template/form/domain.tpl.php";

?>