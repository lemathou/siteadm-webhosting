<h1>Compte partagé</h1>

<form method="post">
<h3>Bases de donnée MySQL</h3>
<table cellspacing="2" cellpadding="2" border="1">
<tr class="colname">
	<td class="noborder">&nbsp;</td>
	<td class="noborder">Nom de la base</td>
	<td class="noborder">Nom d'utilisateur</td>
</tr>
<?php
$query = mysql_query("SELECT * FROM `db` WHERE `account_id` IS NULL ORDER BY `dbname`");
while ($row=mysql_fetch_assoc($query))
{
?>
<tr class="mail">
	<td class="noborder"><input type="checkbox" name="_list_id[]" value="<?=$row["id"]?>" /></td>
	<td><a href="?id=<?php echo $row["id"]; ?>"><?=$row["dbname"]?></a></td>
	<td><?=$row["username"]?></td>
</tr>
<?php
}
echo mysql_error();
?>
</table>
<p><select name="_action" onchange="if (this.value && confirm('Êtes-vous certain ?')) this.form.submit(); else this.selectedIndex = 0;">
	<option value="">-- Actions possibles --</option>
	<option value="delete">Effacer</option>
</select> : sur les bases de données sélectionnées</p>
</form>
