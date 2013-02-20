<form method="post" action="account.php">
<h3>Liste des comptes utilisateur que vous gérez</h3>
<table cellspacing="2" cellpadding="2" border="1">
<tr class="colname">
	<td>&nbsp;</td>
	<td>Username</td>
	<td>Email</td>
	<td>Civilité</td>
	<td>Prénom</td>
	<td>Nom</td>
	<td>Entreprise</td>
</tr>
<?php
foreach($account_list as $row)
{
?>
<tr>
	<td><input type="checkbox" name="list_id[]" value="<?=$row["id"]?>" /></td>
	<td><a href="?id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
	<td><?=$row["email"]?></td>
	<td><?=$civilite_list[$row["civilite"]]?></td>
	<td><?=$row["prenom"]?></td>
	<td><?=$row["nom"]?></td>
	<td><?=$row["societe"]?></td>
</tr>
<?php
}
?>
</table>
</form>
