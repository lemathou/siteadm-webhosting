<form method="post">
	<div class="cadre objectlist">
		<h3>Alias web</h3>
		<table cellspacing="0" cellpadding="0" border="0">
			<?php
			$query_string = "SELECT t1.*, CONCAT(t2.name, '.', t3.name) as website_name FROM website_alias as t1 LEFT JOIN website as t2 ON t1.website_id=t2.id LEFT JOIN domain as t3 ON t2.domain_id=t3.id $query_website_where ORDER BY t1.domain_id, t1.alias_name";
			$query = mysql_query($query_string);
			if ($nb = mysql_num_rows($query))
			{
				?>
			<tr class="colname">
				<td>&nbsp;</td>
				<td>Alias</td>
				<td>Site web</td>
				<td>Redirigé vers l'alias</td>
				<td>Redirection extérieure (url)</td>
			</tr>
			<?php
			}
			else
			{
				echo "<p>Aucun alias de site web associé</p>";
			}
			while($row=mysql_fetch_assoc($query))
			{
				?>
			<tr>
				<td><input type="checkbox" name="_list_id[]" value="<?=$row["id"]?>" />
				</td>
				<td><a href="website.php?alias_id=<?=$row["id"]?>"><?=$row["alias_name"]?>.<?=$domain->name?>
				</a>
				</td>
				<td><?php if ($row["website_id"]) { ?><a
					href="website.php?id=<?=$row["website_id"]?>"><?=$row["website_name"]?>
				</a>
				<?php } ?>
				</td>
				<td><?php echo ($row["website_redirect"]) ?"OUI" : "NON"; ?>
				</td>
				<td><?php echo $row["redirect_url"]; ?>
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
			<select name="_website_alias_action"
				onchange="if (this.value && confirm('Êtes-vous certain ?')) this.form.submit(); else this.selectedIndex = 0;">
				<option value="">-- Choisissez une action --</option>
				<option value="delete">Effacer</option>
				<option value="activate">Réactiver</option>
				<option value="disable">Désactiver</option>
			</select> : sur la sélection
		</p>
		<?php
}
?>
	</div>
</form>
