<form method="post">
	<div class="cadre objectlist">
		<h3>Sites web associés</h3>
		<table cellspacing="0" cellpadding="0" border="0" class="objectlist">
			<?php
			$query_string = "SELECT t1.*, t2.name as phppool_name, t3.name as domain_name FROM website as t1 LEFT JOIN phppool as t2 ON t1.phppool_id = t2.id LEFT JOIN domain as t3 ON t1.domain_id=t3.id $query_website_where ORDER BY t3.name, t1.name";
			$query = mysql_query($query_string);
			if ($nb = mysql_num_rows($query))
			{
				?>
			<tr class="colname">
				<td>&nbsp;</td>
				<td>Site web</td>
				<td>Webmaster</td>
				<td>Pool PHP</td>
			</tr>
			<?php
			}
			else
			{
				echo "<p>Aucun site web associé</p>";
			}
			while($row=mysql_fetch_assoc($query))
			{
				?>
			<tr>
				<td><input type="checkbox" name="_list_id[]" value="<?=$row["id"]?>" />
				</td>
				<td><a href="website.php?id=<?=$row["id"]?>"><?=$row["name"]?>.<?=$row["domain_name"]?>
				</a>
				</td>
				<td><?php echo $row["webmaster_email"]; ?>
				</td>
				<td><?php if ($row["phppool_id"] && $phppool=phppool($row["phppool_id"])) echo $phppool->link(); ?>
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
			<select name="_website_action"
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
