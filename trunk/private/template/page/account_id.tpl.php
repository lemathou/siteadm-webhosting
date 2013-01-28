<div style="float: right; width: 600px;">
	<?php include "template/page/account_stats.tpl.php"; ?>
</div>

<form method="post" action="account.php?id=<?php echo $account->id; ?>">
	<input type="hidden" name="id" value="<?php echo $account->id; ?>" /> <input
		type="submit" name="_account_password_reset"
		value="Réinitialiser le mot de passe" />
</form>

<h3>Modifier un compte utilisateur</h3>

<?php

$form_submit_name = "_account_update";
$form_submit_text = "Mettre à jour";

include "template/form/account.tpl.php";

?>