<div style="float:right;">
<?php include "template/page/account_stats.tpl.php"; ?>
</div>

<form method="post" action="account.php?id=<?=$account->id?>" class="account">
<h3>Modifier un compte utilisateur</h3>
<table cellspacing="2" cellpadding="2" border="0" style="border:1px gray solid;">
<tr>
	<td class="label">ID</td>
	<td class="field"><?=$account->id?><input type="hidden" name="id" value="<?=$account->id?>" /></td>
</tr>
<tr>
	<td class="label">Type</td>
	<td class="field"><?php echo $account_type_list[$account->type]; ?></td>
</tr>
<tr>
	<td class="label">Managing account</td>
	<td class="field"><?php
	if ($manager = account($account->manager_id))
	{
		echo "$manager->prenom $manager->nom";
	}
	else
	{
		echo "<i>Compte autonaume</i>";
	}
	?></td>
</tr>
<tr>
	<td class="label">Actif</td>
	<td class="field"><?php if ($account->actif) echo "ACTIF"; else echo "INACTIF"; ?></td>
</tr>
<tr>
	<td class="label">Username</td>
	<td class="field"><?=$account->name?></td>
</tr>
<tr>
	<td class="label">Email</td>
	<td class="field"><input name="email" value="<?=$account->email?>" size="32" /></td>
</tr>
<tr>
	<td class="label">Civilité</td>
	<td class="field"><select name="civilite"><?
	foreach($civilite_list as $i=>$j)
	{
		if ($i==$account->civilite)
			echo "<option value=\"$i\" selected>$j</option>\n";
		else
			echo "<option value=\"$i\">$j</option>\n";
	}
	?></select></td>
</tr>
<tr>
	<td class="label">Prénom</td>
	<td class="field"><input name="prenom" value="<?=$account->prenom?>" /></td>
</tr>
<tr>
	<td class="label">Nom</td>
	<td class="field"><input name="nom" value="<?=$account->nom?>" /></td>
</tr>
<tr>
	<td class="label">Entreprise</td>
	<td class="field"><input name="societe" value="<?=$account->societe?>" /></td>
</tr>
<tr>
	<td class="label">Offre</td>
	<td class="field"><?php echo $offre_list[$account->offre_id][name]; ?></td>
</tr>
<tr>
	<td class="label">Expiration de l'offre</td>
	<td class="field"><?php echo $account->offre_expire; ?></select></td>
</tr>
</table>
<p><input type="submit" name="_account_update" value="Mettre à jour" /></p>
</form>

<form method="post" action="account.php?id=<?=$account->id?>">
<input type="hidden" name="id" value="<?=$account->id?>" />
<input type="submit" name="_account_password_reset" value="Réinitialiser le mot de passe" />
</form>