<form method="post" class="edit">
<?php if ($domain->id) { ?>
<input type="hidden" name="id" value="<?php echo $domain->id; ?>" />
<?php } ?>

<?php if (login()->perm("manager")) { ?>
<div style="width: 600px;" class="cadre">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label">Propriétaire :</td>
	<td class="field"><select name="account_id"<?php if ($domain->id) {?> onclick="if (false) alert('Attention !')"<?php }; ?>><?php
	if (count($account_list)>1)
	{
		echo "<option value=\"\">-- Choisir --</option>";
	}
	foreach($account_list as $row)
	{
		if (isset($account) && $row["id"] == $account->id)
			echo "<option value=\"$row[id]\" selected>$row[nom] $row[prenom] [$row[name]]</option>\n";
		else
			echo "<option value=\"$row[id]\">$row[nom] $row[prenom] [$row[name]]</option>\n";
	}
	?></select></td>
</tr>
</table>
</div>
<?php } ?>

<div style="width: 600px;" class="cadre">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<?php if (!$domain->id) { ?>
<tr>
	<td class="label">Nom</td>
	<td class="field"><input name="name" value="<?php echo $db->name; ?>" /></td>
</tr>
<?php } ?>
<tr>
	<td class="label">Prise en charge des emails</td>
	<td class="field"><input type="radio" name="email_actif" value="1"<?php if ($domain->email_actif) echo " checked"; ?> /> OUI <input type="radio" name="email_actif" value="0"<?php if (!$domain->email_actif) echo " checked"; ?> /> NON</td>
</tr>
</table>
</div>

<p><input type="submit" name="<?php echo $form_submit_name; ?>" value="<?php echo $form_submit_text; ?>" /></p>

</form>
