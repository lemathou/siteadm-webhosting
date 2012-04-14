<form method="post" class="edit">
<input type="hidden" name="domain_id" value="<?php echo $website->domain_id; ?>" />
<div style="width: 600px;" class="cadre">
<?php if ($website->id) { ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Nom (sous-domaine) :</td>
	<td class="field"><?php echo "<b>$website->name</b>.$domain->name"; ?></td>
</tr>
</table>
<?php } else { ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Nom (sous-domaine) :</td>
	<td class="field"><input name="name" value="<?php echo $website->name; ?>" /> .<?php echo $domain->name; ?></td>
</tr>
</table>
<?php } ?>
</div>

<div style="width: 600px;" class="cadre">
<h3>Paramètres globaux du site</h3>
<input type="hidden" name="id" value="<?php echo $website->id; ?>" />
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Adresse email du Webmaster :</td>
	<td class="field"><input name="webmaster_email" value="<?php echo $website->webmaster_email; ?>" /></td>
</tr>
<tr>
	<td class="label">Page(s) par défaut du site :<br />(en ordre de préférence, séparées par un espace)</td>
	<td class="field"><input name="index_files" value="<?php echo $website->index_files; ?>" /></td>
</tr>
<tr>
	<td class="label">Default charset</td>
	<td class="field">
		<input name="default_charset" type="radio" value=""<?php if (!$website->default_charset) echo " checked"; ?> /> AUCUN
		<input name="default_charset" type="radio" value="utf-8"<?php if ($website->default_charset=="utf-8") echo " checked"; ?> /> UTF-8
		<input name="default_charset" type="radio" value="iso-8859-1"<?php if ($website->default_charset=="iso-8859-1") echo " checked"; ?> /> ISO-8859-1
	</td>
</tr>
<tr>
	<td><h4>Paramètres SSL</h4></td>
</tr>
<tr>
	<td class="label">SSL (https) :<br />(Un certificat dédié au nom de domaine sera automatiquement créé)</td>
	<td class="field">
		<input name="ssl" type="radio" value="1"<?php if ($website->ssl) echo " checked"; ?> /> OUI
		<input name="ssl" type="radio" value="0"<?php if (!$website->ssl) echo " checked"; ?> /> NON
	</td>
</tr>
<tr>
	<td class="label">SSL forcé :<br />(redirection http vers https)</td>
	<td class="field">
		<input name="ssl_force_redirect" type="radio" value="1"<?php if ($website->ssl_force_redirect) echo " checked"; ?> /> OUI
		<input name="ssl_force_redirect" type="radio" value="0"<?php if (!$website->ssl_force_redirect) echo " checked"; ?> /> NON
	</td>
</tr>
<tr>
	<td><h4>Application Web</h4></td>
</tr>
<tr>
	<td class="label">Application Web :</td>
	<td class="field">
		<select name="webapp_id"><option value="">-- Choisir si besoin --</option>
		<?php
		$query = mysql_query("SELECT `id`, `name` FROM `webapp`");
		while ($webapp=mysql_fetch_assoc($query))
		{
			if ($website->webapp_id == $webapp["id"])
				echo "<option value=\"$webapp[id]\" selected>$webapp[name]</option>";
			else
				echo "<option value=\"$webapp[id]\">$webapp[name]</option>";
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td><h4>Paramètres PHP CGI</h4></td>
</tr>
<tr>
	<td class="label">Pool PHP à utiliser :<br />(Il est conseillé de créer un pool dédié pour chaque application, toutefois si cela ne pose aucun problème de performance on peut mutualiser)</td>
	<td class="field"><select name="phppool_id"><option></option><?php
	$query_string = "SELECT t1.id, t1.name FROM phppool as t1 WHERE t1.account_id='$account->id'";
	$query = mysql_query($query_string);
	while ($row=mysql_fetch_assoc($query))
	{
		if ($website->phppool_id == $row["id"])
			echo "<option value=\"$row[id]\" selected>$row[name]</option>";
		else
			echo "<option value=\"$row[id]\">$row[name]</option>";
	}
	?></select> <input type="button" value="" class="phppool_edit" style="display: none;" /></td>
</tr>
<tr>
	<td><h4>Paramètres PHP (forcé)</h4></td>
</tr>
<tr>
	<td class="label">Short open tag</td>
	<td class="field">
		<input name="php_short_open_tag" type="radio" value=""<?php if (!in_array($website->php_short_open_tag, array("0", "1"), true)) echo " checked"; ?> /> DEFAUT
		<input name="php_short_open_tag" type="radio" value="1"<?php if ($website->php_short_open_tag === "1") echo " checked"; ?> /> OUI
		<input name="php_short_open_tag" type="radio" value="0"<?php if ($website->php_short_open_tag === "0") echo " checked"; ?> /> NON
	</td>
</tr>
<tr>
	<td class="label">Open Basedir</td>
	<td class="field"><input name="php_open_basedir" value="<?php echo $website->php_open_basedir; ?>" /></td>
</tr>
<tr>
	<td class="label">Include Path</td>
	<td class="field"><input name="php_include_path" value="<?php echo $website->php_include_path; ?>" /></td>
</tr>
<tr>
	<td class="label">APC Stat</td>
	<td class="field">
		<input name="php_apc_stat" type="radio" value=""<?php if (!in_array($website->php_apc_stat, array("0", "1"), true)) echo " checked"; ?> /> DEFAUT
		<input name="php_apc_stat" type="radio" value="1"<?php if ($website->php_apc_stat === "1") echo " checked"; ?> /> OUI
		<input name="php_apc_stat" type="radio" value="0"<?php if ($website->php_apc_stat === "0") echo " checked"; ?> /> NON
	</td>
</tr>
<tr>
	<td><h4>Protection des pages</h4></td>
</tr>
<tr>
	<td class="label">Limiter l'accès<br />(à l'aide d'un fichier .htaccess)</td>
	<td class="field">
		<input name="folder_auth" type="radio" value="1"<?php if ($website->folder_auth) echo " checked"; ?> /> OUI
		<input name="folder_auth" type="radio" value="0"<?php if (!$website->folder_auth) echo " checked"; ?> /> NON
	</td>
</tr>
</table>
</div>

<p><input type="submit" name="<?php echo $form_submit_name; ?>" value="<?php echo $form_submit_text; ?>" /></p>

</form>
