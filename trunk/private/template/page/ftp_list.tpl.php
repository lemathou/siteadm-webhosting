<form method="post">
<table cellspacing="2" cellpadding="2" border="1">
<?php

$query = mysql_query("SELECT t1.*, t2.name as account_name, t2.manager_id as manager_id FROM ftp_user as t1 INNER JOIN account as t2 ON t1.account_id=t2.id $query_domain_where GROUP BY t1.id ORDER BY t1.username");

if ($nb = mysql_num_rows($query))
{
?>
<tr class="colname">
	<td>&nbsp;</td>
	<td>Nom d'utilisateur</td>
	<td>Mot de passe</td>
	<td>Dossier</td>
	<td>Propriétaire</td>
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
	<td><input type="checkbox" name="_list_id[]" value="<?php echo $row["id"]; ?>" /></td>
	<td><a href="?id=<?php echo $row["id"]; ?>"><?php echo $row["username"]; ?></a></td>
	<td><?php echo $row["password"]; ?></td>
	<td><?php echo $row["folder"]; ?></td>
	<td><?php echo $row["account_name"]; ?></td>
</tr>
<?php
}
?>
</table>
</form>