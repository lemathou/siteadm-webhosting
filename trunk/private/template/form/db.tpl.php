<form method="post" class="edit">
<?php if ($db->id) { ?>
<input type="hidden" name="id" value="<?php echo $db->id; ?>" />
<?php } ?>

<?php if (login()->perm("manager")) { ?>
<div style="width: 600px;" class="cadre">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Propriétaire :</td>
	<td class="field"><select name="account_id"><?php
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
<tr>
	<td class="label" width="250">Nom de la base :</td>
	<?php if ($db->id) { ?>
	<td class="field"><?php echo $db->dbname; ?></td>
	<?php } else { ?>
	<td class="field"><input name="dbname" value="<?php echo $db->dbname; ?>" /></td>
	<?php } ?>
</tr>
<tr>
	<td class="label" width="250">Nom d'utilisateur :</td>
	<?php if ($db->id) { ?>
	<td class="field"><?php echo $db->username; ?></td>
	<?php } else { ?>
	<td class="field"><input name="username" value="<?php echo $db->username; ?>" /></td>
	<?php } ?>
</tr>
<tr>
	<td class="label">Password :</td>
	<td class="field"><input name="password" value="<?php echo $db->password; ?>" /></td>
</tr>
<tr>
	<td class="label">Quota :</td>
	<td class="field"><select name="quota"><?php
	foreach($db->field("quota", "list") as $i)
	{
		if ($i >= 1000)
			$j = ($i/1000)." GO";
		else
			$j = "$i MO";
		if ($db->quota == $i)
			echo "<option value=\"$i\" selected>$j</option>";
		else
			echo "<option value=\"$i\">$j</option>";
	}
	?></select></td>
</tr>
</table>
</div>

<p><input type="submit" name="<?php echo $form_submit_name; ?>" value="<?php echo $form_submit_text; ?>" /></p>

</form>
