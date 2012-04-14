<form method="post" action="?domain_id=<?php echo $domain->id; ?>">
<?php if ($website_alias->id) { ?>
<input type="hidden" name="id" value="<?php echo $website_alias->id; ?>" />
<?php } else { ?>
<input type="hidden" name="domain_id" value="<?php echo $domain->id; ?>" />
<?php } ?>

<div style="width: 600px;" class="cadre">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td>Sous-domaine :</td>
	<td><input name="alias_name" value="<?php echo $website_alias->name; ?>" />.<?=$domain->name?></td>
</tr>
<tr>
	<td>Alias de :</td>
	<td><select name="website_id"><option value="0">-- Choisir au besoin --</option><?
	$query=mysql_query("SELECT website.id, website.name, domain.id as domain_id, domain.name as domain_name FROM website, domain WHERE website.domain_id=domain.id ORDER BY domain.name, website.name");
	$d=0;
	while ($row=mysql_fetch_assoc($query))
	{
		if ($d != $row["domain_id"])
		{
			if ($d)
				echo "</optgroup>\n";
			echo "<optgroup label=\"$row[domain_name]\">\n";
			$d=$row["domain_id"];
		}
		if ($website_alias->website_id && $row["id"])
			echo "<option value=\"$row[id]\" selected>$row[name].$row[domain_name]</option>\n";
		else
			echo "<option value=\"$row[id]\">$row[name].$row[domain_name]</option>\n";
	}
	if ($d)
		echo "</optgroup>\n";
	?></select> <input type="checkbox" name="website_redirect" value="1"<?php echo ($website_alias->website_redirect) ?" checked" : ""; ?> /> Rediriger vers l'alias</td>
</tr>
<tr>
	<td>Redirection vers :<br />(url complète)</td>
	<td><input name="redirect_url" size="64" value="<?php echo $website_alias->redirect_url; ?>" /></td>
</tr>
</table>
</div>

<p><input type="submit" name="<?php echo $form_submit_name; ?>" value="<?php echo $form_submit_text; ?>" /></p>

</form>
