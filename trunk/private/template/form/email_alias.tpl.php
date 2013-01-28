<form method="post" action="?domain_id=<?php echo $domain->id; ?>">
	<?php if ($email_alias->id) { ?>
	<input type="hidden" name="id" value="<?php echo $email_alias->id; ?>" />
	<?php } else { ?>
	<input type="hidden" name="domain_id"
		value="<?php echo $domain->id; ?>" />
	<?php } ?>
	<div style="width: 600px;" class="cadre">
		<table cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td class="label">Email (de l'alias) :</td>
				<td class="field"><input name="name"
					value="<?php echo $email_alias->name; ?>" /> @<?=$domain->name?>
				</td>
			</tr>
			<tr>
				<td class="label">Actif :</td>
				<td class="field"><input type="radio" name="actif" value="1"
				<?php if ($email_alias->actif) echo " checked"; ?> /> OUI <input
					type="radio" name="actif" value="0"
					<?php if (!$email_alias->actif) echo " checked"; ?> /> NON</td>
			</tr>
			<tr>
				<td class="label">Alias de :</td>
				<td class="field"><select name="email_id"
					onchange="this.form.redirect_email.value='';//this.form.redirect_email.disabled=true;"><option
							value="0">-- Choisir au besoin --</option>
						<?
						$query_email=mysql_query("SELECT email.id as id, email.name as email_name, domain.name as domain_name FROM email, domain WHERE email.domain_id=domain.id ORDER BY domain.name, email.name");
						$email_domain="";
						while ($email=mysql_fetch_assoc($query_email))
						{
							if ($email_domain != $email["domain_name"] && $email_domain)
								echo "</optgroup>\n";
							if ($email_domain != $email["domain_name"])
							{
								$email_domain = $email["domain_name"];
								echo "<optgroup label=\"$email[domain_name]\">";
							}
							if ($email_alias->email_id == $email["id"])
								echo "<option value=\"$email[id]\" selected>$email[email_name]@$email[domain_name]</option>";
							else
								echo "<option value=\"$email[id]\">$email[email_name]@$email[domain_name]</option>";
						}
						if ($email_domain)
							echo "</optgroup>\n";
						?></select>
				</td>
			</tr>
			<tr>
				<td class="label">Redirection vers :</td>
				<td class="field"><input name="redirect_email"
					value="<?php echo $email_alias->redirect_email; ?>" />
				</td>
			</tr>
		</table>
	</div>

	<p>
		<input type="submit" name="<?php echo $form_submit_name; ?>"
			value="<?php echo $form_submit_text; ?>" />
	</p>

</form>
