<div class="info_text">
<p>Il s'agit d'un processus parent contenant des pools d'applications qui en sont des forks (utilisant un php.ini générique commun).</p>
<p>Chaque pool sera ensuite paramétré de façon spécifique pour un utilisateur/groupe avec des directives PHP propres.</p>
<p>Quelques rares paramètres sont à spécifier au niveau du php.ini, comme apc.shm_size, commun à l'ensemble des pools, ou encore les spécificités par vhost ou par dossier qui seront intégrées automatiquement lorsque l'on associe un site à un pool PHP.</p>
<p>Certains paramètres sont surchargés lorsqu'ils ne sont pas de nouveau spécifiés.</p>
</div>

<?php if (isset($info_text)) { ?>
<p style="color:red;font-weight: bold;"><?php echo $info_text; ?></p>
<?php } ?>

<form method="post" class="edit">
<div style="width: 600px;" class="cadre">
<?php if ($phpapp->id) { ?>
<input type="hidden" name="id" value="<?php echo $phpapp->id; ?>" />
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Compte de gestion :</td>
	<td class="field"><?php if ($account=$phpapp->account()) echo $account->link(); else echo "Partagé / Pas de compte de gestion"; ?></td>
</tr>
<tr>
	<td class="label">Nom :</td>
	<td class="field"><?php echo $phpapp->name; ?></td>
</tr>
<tr>
	<td class="label">Langage :</td>
	<td class="field"><select name="language_bin_id"><option></option><?php
	foreach($phpapp->account()->language_bin_list() as $language_bin) if ($language_bin->app_compatible)
	{
		if ($language_bin->id == $phpapp->language_bin_id)
			echo "<option value=\"$language_bin->id\" selected>$language_bin</option>";
		else
			echo "<option value=\"$language_bin->id\">$language_bin</option>";
	}
	?></select></td>
</tr>
</table>
<?php } else { ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Compte de gestion :</td>
	<td class="field"><select name="account_id" onchange="this.form.submit()"><option value="0">Partagé / Pas de compte de gestion</option><?php
	foreach($account_list as $row)
	{
		if ($row["id"] == $phpapp->account_id)
			echo "<option value=\"$row[id]\" selected>$row[nom] $row[prenom] [$row[name]]</option>";
		else
			echo "<option value=\"$row[id]\">$row[nom] $row[prenom] [$row[name]]</option>";
	}
	?></select></td>
</tr>
<tr>
	<td>Nom :</td>
	<td class="field"><input name="name" value="<?php echo $phpapp->name; ?>" /></td>
</tr>
<tr>
	<td class="label" width="250">Langage :</td>
	<td class="field"><select name="language_bin_id"><option></option><?php
	foreach($phpapp->account()->language_bin_list() as $language_bin) if ($language_bin->app_compatible)
	{
		if ($language_bin->id == $phpapp->language_bin_id)
			echo "<option value=\"$language_bin->id\" selected>$language_bin</option>";
		else
			echo "<option value=\"$language_bin->id\">$language_bin</option>";
	}
	?></select></td>
</tr>
</table>
<?php } ?>
</div>

<div style="width: 600px;" class="cadre">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="label">Description :</td>
	<td class="field"><textarea name="description"><?php echo $phpapp->description; ?></textarea></td>
</tr>
<tr>
	<td class="label">Adresse email du Webmaster :</td>
	<td class="field"><input name="webmaster_email" value="<?php echo $phpapp->webmaster_email; ?>" /></td>
</tr>
<tr>
	<td class="label">APC : mémoire partagée (shm_size) :</td>
	<td class="field"><input name="apc_shm_size" value="<?php echo $phpapp->apc_shm_size; ?>" size="3" maxlength="3" /> MO</td>
</tr>
</table>
</div>

<p><input type="submit" name="<?php echo $form_submit_name; ?>" value="<?php echo $form_submit_text; ?>" /></p>

</form>