<div class="info_text">
<p>Un Pool applicatif prend en charge des scripts dans le langage pour lequel il est configuré.</p>
<p style="color: green;">ATTENTION : Pour l'instant seuls les pools PHP sont paramétrables.</p>
<p>Un pool est nécéssairement associé à un utilisateur, et ses seules applications (sites web, etc.) seront accessibles par le pool.</p>
<p>Un site web qui requiert l'utilisation d'un langage (PHP) doit être associé à un pool pour fonctionner convenablement.</p>
<p>Un pool peut être associé à un processus parent (comme PHP-FPM) pour plus de performance, une utilisation moindre de la mémoire.</p>
<p><a href="http://www.php.net/manual/en/ini.list.php" target="_blank">Directives de configuration PHP</a></p>
<p><a href="http://php.net/manual/en/ini.core.php" target="_blank">Détail des directives "core"</a></p>
<p><a href="http://fr.php.net/manual/fr/install.fpm.configuration.php" target="_blank">Liste des directives globales de php-fpm.conf</a></p>
</div>

<?php if (isset($info_text)) { ?>
<p style="color:red;font-weight: bold;"><?php echo $info_text; ?></p>
<?php } ?>

<form method="post" class="edit">

<div style="width: 600px;" class="cadre">
<?php if ($phppool->id) { ?>
<input type="hidden" name="id" value="<?php echo $phppool->id; ?>" />
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Compte de gestion :</td>
	<td class="field"><?php if ($phppool->account_id) echo account($phppool->account_id)->link(); else echo "Partagé / Pas de compte de gestion"; ?></td>
</tr>
<tr>
	<td class="label">Nom :</td>
	<td class="field"><?php echo $phppool->name; ?></td>
</tr>
<tr>
	<td class="label">Processus parent :</td>
	<td class="field"><?php
	if ($phpapp=$phppool->phpapp())
	{
		$language_bin = $phpapp->language_bin();
		echo $phpapp->link();
	}
	else
	{
		$language_bin = $phppool->language_bin();
		echo "<i>Aucun</i>";
	}
	?></td>
</tr>
<tr>
	<td class="label">Language : </td>
	<td class="field"><?php echo $language_bin; ?></td>
</tr>
</table>
<?php } else { ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td class="label" width="250">Compte de gestion :</td>
	<td class="field"><select name="account_id" onchange="this.form.submit()"><option value="0">Partagé / Pas de compte de gestion</option><?php
	foreach($account_list as $row)
	{
		if ($row["id"] == $phppool->account_id)
			echo "<option value=\"$row[id]\" selected>$row[nom] $row[prenom] [$row[name]]</option>";
		else
			echo "<option value=\"$row[id]\">$row[nom] $row[prenom] [$row[name]]</option>";
	}
	?></select></td>
</tr>
<tr>
	<td class="label">Nom :</td>
	<td class="field"><input name="name" value="<?php echo $phppool->name; ?>" /></td>
</tr>
<tr>
	<td class="label">Processus parent partagé (PHP-FPM) :<br />(optionnel mais recommandé dans la majorité des cas)</td>
	<td class="field"><select name="phpapp_id" onchange="this.form.submit()"><option></option><?php
	$query_string = "SELECT t1.*, t2.name as account_name FROM phpapp as t1 LEFT JOIN account as t2 ON t1.account_id=t2.id WHERE t1.account_id IS NULL OR t1.account_id IN ('$phppool->account_id')";
	$query = mysql_query($query_string);
	while($row = mysql_fetch_assoc($query))
	{
		if ($row[account_id])
			$phpapp_name = "$row[name] [$row[account_name]]";
		else
			$phpapp_name = "$row[name] (partagé)";
		if ($phppool->phpapp_id == $row[id])
			echo "<option value=\"$row[id]\" selected>$phpapp_name</option>";
		else
			echo "<option value=\"$row[id]\">$phpapp_name</option>";
	}
	?></select></td>
</tr>
<?php if ($phpapp=$phppool->phpapp()) { ?>
<tr>
	<td class="label">Langage / CGI / options de compilation :<br />(lié au processus parent)</td>
	<td class="field"><?php echo $phpapp->language_bin(); ?></td>
</tr>
<?php } else { ?>
<tr>
	<td class="label">Langage / CGI / options de compilation :<br />(si pas de processus parent)</td>
	<td class="field"><select name="language_id" onchange="this.form.submit()"><option></option><?php
	foreach($account>language_bin_list() as $language_bin) if ($language_bin->app_compatible)
	{
		if ($language_bin->id == $phppool->language_bin_id)
			echo "<option value=\"$language_bin->id\" selected>$language_bin</option>";
		else
			echo "<option value=\"$language_bin->id\">$language_bin</option>";
	}
	?></select></td>
</tr>
<?php } ?>
</table>
<?php } ?>
</div>

<div style="width: 600px;" class="cadre">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td width="250"><h4>Paramètres CGI</h4></td>
</tr>
<tr>
	<td class="label"><a href="http://fr.php.net/manual/fr/install.fpm.configuration.php#pm.max-chidlren" target="_blank">Nombre max. de processus</a> :</td>
	<td class="field"><input name="worker_nb_max" value="<?php echo $phppool->worker_nb_max; ?>" size="2" maxlength="4" /></td>
</tr>
<tr>
	<td class="label"><a href="http://fr.php.net/manual/fr/install.fpm.configuration.php#pm.max-requests" target="_blank">Nombre max. de requêtes par processus</a> :</td>
	<td class="field"><input name="worker_max_requests" value="<?php echo $phppool->worker_max_requests; ?>" size="4" maxlength="5" /></td>
</tr>
<tr> <td colspan="2"><hr /></td> </tr>
<tr>
	<td><h4>DEBUG</h4></td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/errorfunc.configuration.php#ini.error-reporting" target="_blank">Niveau d'erreurs</a> :<br />(Error reporting)</td>
	<td class="field">
		<input name="error_reporting" value="<?php echo $phppool->error_reporting; ?>" />
	</td>
</tr>
<tr>
	<td class="label"><a href="" target="_blank">Affichage des erreurs</a> :</td>
	<td class="field">
		<input name="error_display" type="radio" value="1"<?php if ($phppool->error_display) echo " checked"; ?> /> OUI
		<input name="error_display" type="radio" value="0"<?php if (!$phppool->error_display) echo " checked"; ?> /> NON
	</td>
</tr>
<tr>
	<td class="label"><a href="" target="_blank">Sauvegarder error_log</a> :</td>
	<td class="field">
		<input name="error_filesave" type="radio" value="1"<? if ($phppool->error_filesave) echo " checked"; ?> /> OUI
		<input name="error_filesave" type="radio" value="0"<? if (!$phppool->error_filesave) echo " checked"; ?> /> NON
	</td>
</tr>
<tr> <td colspan="2"><hr /></td> </tr>
<tr>
	<td><h4>Performance</h4></td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/info.configuration.php#ini.max-execution-time" target="_blank">Max execution time</a> :</td>
	<td class="field"><input name="max_execution_time" value="<?php echo $phppool->max_execution_time; ?>" size="2" maxlength="3" />s (temps processeur)</td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/info.configuration.php#ini.max-input-time" target="_blank">Max input time</a> :</td>
	<td class="field"><input name="max_input_time" value="<?php echo $phppool->max_input_time; ?>" size="2" maxlength="3" />s</td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.memory-limit" target="_blank">Memory limit</a> :</td>
	<td class="field"><input name="memory_limit" value="<?php echo $phppool->memory_limit; ?>" size="2" maxlength="3" />MO</td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/apc.configuration.php#ini.apc.stat" target="_blank">APC : stat</a> :</td>
	<td class="field">
		<input name="apc_stat" type="radio" value="1"<?php if ($phppool->apc_stat) echo " checked"; ?> /> OUI
		<input name="apc_stat" type="radio" value="0"<?php if (!$phppool->apc_stat) echo " checked"; ?> /> NON
	</td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/apc.configuration.php#ini.apc.lazy-functions" target="_blank">APC : lazy loading of functions &amp; classes</a> :</td>
	<td class="field">
		<input name="apc_lazy" type="radio" value="1"<?php if ($phppool->apc_lazy) echo " checked"; ?> /> OUI
		<input name="apc_lazy" type="radio" value="0"<?php if (!$phppool->apc_lazy) echo " checked"; ?> /> NON
	</td>
</tr>
<tr> <td colspan="2"><hr /></td> </tr>
<tr>
	<td><h4>$_POST &amp; Upload</h4></td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.post-max-size" target="_blank">$_POST max size</a> :</td>
	<td class="field"><input name="post_max_size" value="<?php echo $phppool->post_max_size; ?>" size="2" maxlength="3" />MO</td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.file-uploads" target="_blank">File uploads</a> :<br />(Activé ou non)</td>
	<td class="field">
		<input name="file_uploads" type="radio" value="1"<?php if ($phppool->file_uploads) echo " checked"; ?> /> OUI
		<input name="file_uploads" type="radio" value="0"<?php if (!$phppool->file_uploads) echo " checked"; ?> /> NON
	</td>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.upload-max-filesize" target="_blank">Upload max filesize</a> :</td>
	<td class="field"><input name="upload_max_filesize" value="<?php echo $phppool->upload_max_filesize; ?>" size="2" maxlength="3" />MO</td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.max-file-uploads" target="_blank">Max file upload</a> :</td>
	<td class="field"><input name="max_file_upload" value="<?php echo $phppool->max_file_upload; ?>" size="2" maxlength="2" /></td>
</tr>
<tr> <td colspan="2"><hr /></td> </tr>
<tr>
	<td><h4>Paramètres des applications</h4></td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.include-path" target="_blank">Include Path</a> :</td>
	<td class="field"><input name="include_path" value="<?php echo $phppool->include_path; ?>" size="32" maxlength="64" /></td>
</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.extension" target="_blank">Extensions dynamique chargées</a> :<br />(Parmis la liste des modules disponibles à l'utilisateur - certains modules sont aussi intégrés à PHP à la compilation)</td>
	<td class="field"><input type="hidden" name="extension" /><select name="extension[]" multiple size="5">
	<?php
	$query=mysql_query("SELECT langage_php_ext.*, langage_php_bin_ext_ref.ext_id as `already`, account_php_ext_ref.account_id as `authorized` FROM langage_php_ext LEFT JOIN account_php_ext_ref ON langage_php_ext.id=account_php_ext_ref.ext_id AND account_php_ext_ref.account_id='$phppool->account_id' LEFT JOIN langage_php_bin_ext_ref ON langage_php_ext.id=langage_php_bin_ext_ref.ext_id AND langage_php_bin_ext_ref.langage_bin_id='$phppool->langage_id' WHERE langage_php_ext.type='extension' ORDER BY langage_php_ext.description");
	while($ext=mysql_fetch_assoc($query))
	{
		if ($ext["already"])
			echo "<option value=\"$ext[id]\" disabled style=\"color:red;\">$ext[description] (core)</option>\n";
		elseif (in_array($ext["id"], $phppool->extension))
			echo "<option value=\"$ext[id]\" selected>$ext[description]</option>\n";
		elseif ($ext["authorized"])
			echo "<option value=\"$ext[id]\">$ext[description]</option>\n";
		else
			echo "<option value=\"$ext[id]\" disabled>$ext[description] (non autorisé)</option>\n";
	}
	?>
	</select><?php echo mysql_error(); ?></td>
	</tr>
<tr>
	<td class="label"><a href="http://www.php.net/manual/en/ini.core.php#ini.disable-functions" target="_blank">Fonctions désactivées</a> :<br />(Pour des raisons de sécurité)</td>
	<td class="field"><input type="hidden" name="disable_functions" /><select name="disable_functions[]" multiple size="5">
	<?php
	$query = mysql_query("SELECT t1.* FROM langage_php_functions as t1 ORDER BY t1.security, t1.name");
	$g = "";
	while ($ext=mysql_fetch_assoc($query))
	{
		if ($g != $ext["security"])
		{
			if ($g)
				echo "</optgroup>\n";
			$g = $ext["security"];
				echo "<optgroup label=\"$g\">\n";
		}
		if (in_array($ext["id"], $phppool->disable_functions))
			echo "<option value=\"$ext[id]\" selected>$ext[name]</option>\n";
		else
			echo "<option value=\"$ext[id]\">$ext[name]</option>\n";
	}
	echo "</optgroup>\n";
	?>
	</select><?php echo mysql_error(); ?></td>
</tr>
</table>
</div>

<p><input type="submit" name="<?php echo $form_submit_name; ?>" value="<?php echo $form_submit_text; ?>" /></p>

</form>
