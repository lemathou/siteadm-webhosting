<h1>
	<?=$domain->name?>
</h1>

<?php
if (!isset($account))
{
	$account = account($domain->account_id);
}
if ($account)
{
	?>
<p>
	Compte de gestion : <a href="account.php?id=<?=$account->id?>"><?=$account->name?>
	</a>
</p>
<?php
}
?>

<div class="cadre objectlist">
	<form method="post">
		<h3>Boites Email</h3>
		<?php
		$query = mysql_query("SELECT * FROM email WHERE domain_id='$domain->id' ORDER BY name");
		if ($nb=mysql_num_rows($query))
		{
			?>
		<table cellspacing="0" cellpadding="0" border="0">
			<tr class="colname">
				<td class="noborder">&nbsp;</td>
				<td class="noborder">Boite email</td>
				<td class="noborder">Actif</td>
				<td class="noborder">Quota</td>
			</tr>
			<?php
		}
		else
		{
			echo "<p>Aucune boite email associée</p>\n";
		}
		while ($row=mysql_fetch_assoc($query))
		{
			?>
			<tr class="mail">
				<td class="noborder"><input type="checkbox" name="_list_id[]"
					value="<?=$row["id"]?>" /></td>
				<td><a
					href="?<?php if (isset($account)) echo "account_id=$account->id&"; ?>domain_id=<?=$domain->id?>&id=<?=$row["id"]?>"><?=$row["name"]?>@<?=$domain->name?>
				</a></td>
				<td><? if($row["actif"]) echo "ACTIF"; else echo "<b style=\"color: red;\">INACTIF</b>"; ?>
				</td>
				<td><? if ($row["quota"]<1000) echo $row["quota"]." MO"; else echo ($row["quota"]/1000)." GO"; ?>
				</td>
				<td class="delete noborder"><a
					href="?domain_id=<?=$domain->id?>&email_del_id=<?=$row["id"]?>"
					onclick="return(confirm('Êtes-vous certain de vouloir supprimer cette boite email ?'))">X</a>
				</td>
			</tr>
			<?php
		}
		?>
		</table>
		<?php
		if ($nb)
		{
			?>
		<p>
			<select name="_email_action"
				onchange="if (this.value && confirm('Êtes-vous certain ?')) this.form.submit(); else this.selectedIndex = 0;">
				<option value="">-- Choisissez une action --</option>
				<option value="delete">Effacer</option>
				<option value="activate">Réactiver</option>
				<option value="disable">Désactiver</option>
			</select> : sur les boites email sélectionnées
		</p>
		<?php
		}
		?>
	</form>
</div>

<div class="cadre objectlist">
	<form method="post">
		<h3>Alias &amp; Redirections</h3>
		<?php

		$query = mysql_query("SELECT email_alias.*, email.name as email_name, domain.name as domain_name FROM email_alias LEFT JOIN email ON email_alias.email_id=email.id LEFT JOIN domain ON email.domain_id=domain.id WHERE email_alias.domain_id='$domain->id' ORDER BY email_alias.name");
		if ($nb=mysql_num_rows($query))
		{

			?>
		<table cellspacing="0" cellpadding="0" border="0">
			<tr class="colname">
				<td class="noborder">&nbsp;</td>
				<td width="300" class="noborder">Adresse email (alias)</td>
				<td class="noborder">Alias de</td>
				<td class="noborder">Redirection vers</td>
				<td class="noborder">Actif</td>
			</tr>
			<?php
			while ($row=mysql_fetch_assoc($query))
			{
				$email_alias = new email_alias(null, $row);
				?>
			<tr class="mail">
				<td class="noborder"><?php if ($email_alias->update_perm()) { ?><input
					type="checkbox" name="_list_id[]" value="<?=$email_alias->id?>" />
					<?php } ?></td>
				<td><a
					href="?<?php if (isset($account)) echo "account_id=$account->id&"; ?>domain_id=<?=$domain->id?>&alias_id=<?=$row["id"]?>"><?=$row["name"]?>@<?=$domain->name?>
				</a></td>
				<td><? if ($row["email_name"]) echo "$row[email_name]@$row[domain_name]"?>
				</td>
				<td><?=$row["redirect_email"]?></td>
				<td><?php if ($row["actif"]) echo "ACTIF"; else echo "<b style=\"color: red;\">INACTIF</b>"; ?>
				</td>
				<?php if ($email_alias->update_perm()) { ?>
				<td class="delete noborder"><a
					href="?domain_id=<?=$domain->id?>&email_alias_del_name=<?=$row["name"]?>"
					onclick="return(confirm('Êtes-vous certain de vouloir supprimer cette boite email ?'))">X</a>
				</td>
				<?php } ?>
			</tr>
			<?php

			}
			?>
		</table>
		<p>
			<select name="_email_alias_action"
				onchange="if (this.value && confirm('Êtes-vous certain ?')) this.form.submit(); else this.selectedIndex = 0;">
				<option value="">-- Choisissez une action --</option>
				<option value="delete">Effacer</option>
				<option value="activate">Réactiver</option>
				<option value="disable">Désactiver</option>
			</select> : sur les boites email sélectionnées
		</p>
		<?php
}

else
{

echo "<p>Aucun email email associé</p>\n";

}

?>
	</form>
</div>
