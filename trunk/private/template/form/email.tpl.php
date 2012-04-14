<?php if ($email->id) { ?>
<form method="post" action="?domain_id=<?php echo $domain->id; ?>">
<div style="width: 600px;" class="cadre">
<input type="hidden" name="id" value="<?php echo $email->id; ?>" />
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="label">Nouveau mot de passe :</td>
	<td class="field"><input name="password" value="" /></td>
</tr>
</table>
</div>
<p><input type="submit" name="_email_update" value="Changer le mot de passe" /></p>
</form>
<?php } ?>

<form method="post" action="?domain_id=<?php echo $domain->id; ?>">

<div style="width: 600px;" class="cadre">
<?php if ($email->id) { ?>
<input type="hidden" name="id" value="<?php echo $email->id; ?>" />
<?php } else { ?>
<input type="hidden" name="domain_id" value="<?php echo $domain->id; ?>" />
<?php } ?>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="label">Email :</td>
	<td class="field"><input name="name" value="<?php echo $email->name; ?>"<?php if ($email->id) echo " readonly"; ?> /> @ <?=$domain->name?></td>
</tr>
<tr>
	<td class="label">Boite active :</td>
	<td class="field"><input type="radio" name="actif" value="1"<?php if ($email->actif) echo " checked"; ?> /> OUI <input type="radio" name="actif" value="0"<?php if (!$email->actif) echo " checked"; ?> /> NON</td>
</tr>
<?php if (!$email->id) { ?>
<tr>
	<td class="label">Mot de passe :</td>
	<td class="field"><input name="password" value="" /></td>
</tr>
<?php } ?>
<tr>
	<td class="label">Quota :</td>
	<td class="field"><select name="quota">
	<?php
	foreach (email::field("quota", "list") as $i)
		if ($i == $email->quota)
			echo "<option value=\"$i\" selected>$i MO</option>";
		else
			echo "<option value=\"$i\">$i MO</option>";
	?>
	</select></td>
</tr>
</table>
</div>

<p><input type="submit" name="<?php echo $form_submit_name; ?>" value="<?php echo $form_submit_text; ?>" /></p>

</form>
