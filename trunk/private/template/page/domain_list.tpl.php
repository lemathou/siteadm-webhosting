<form method="post">
<table cellspacing="2" cellpadding="2" border="1">
<?php

$query = mysql_query("SELECT t1.*, t2.name as account_name, t2.manager_id as manager_id, (SELECT COUNT(1) FROM email as t3 WHERE t1.id=t3.domain_id) as email_nb, (SELECT COUNT(1) FROM email_alias as t4 WHERE t1.id=t4.domain_id) as email_alias_nb, (SELECT COUNT(1) FROM website as t5 WHERE t1.id=t5.domain_id) as website_nb, (SELECT COUNT(1) FROM website_alias as t6 WHERE t1.id=t6.domain_id) as website_alias_nb FROM domain as t1 LEFT JOIN account as t2 ON t1.account_id=t2.id $query_domain_where GROUP BY t1.id ORDER BY t1.name");

if ($nb = mysql_num_rows($query))
{
?>
<tr class="colname">
	<td rowspan="2">&nbsp;</td>
	<td rowspan="2" valign="top">ID</td>
	<td rowspan="2" valign="top">Nom de domaine</td>
	<td rowspan="2" valign="top">Date de<br />renouvellement</td>
	<td rowspan="2" valign="top">Propriétaire</td>
	<td colspan="4" valign="top">SITES WEB</td>
	<td colspan="5" valign="top">EMAIL</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>Sites</td>
	<td>Alias</td>
	<tD>&nbsp;</td>
	<td>ACTIF</td>
	<td>Boite</td>
	<td>Alias</td>
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
	<td><input type="checkbox" name="_list_id[]" value="<?=$row["id"]?>" /></td>
	<td><?=$row["id"]?></td>
	<td><a href="?id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
	<td><?=$row["renew_date"]?></td>
	<td><a href="account.php?id=<?=$row["account_id"]?>"><?=$row["account_name"]?></a></td>
	<td><a href="website.php?domain_id=<?=$row["id"]?>">WEBSITES</a></td>
	<td align="right"><? if ($row["website_nb"]) echo $row["website_nb"]; else echo "<span style=\"color:red;\">0</span>"; ?></td>
	<td align="right"><?=$row["website_alias_nb"]?></td>
	<td><a href="email.php?domain_id=<?=$row["id"]?>">EMAILS</a></td>
	<td><?php if ($row["email_actif"]) echo "ACTIF"; else echo "<span style=\"color:red;\">INACTIF</span>"; ?></td>
	<td align="right"><? if ($row["email_nb"]) echo $row["email_nb"]; else echo "<span style=\"color:red;\">0</span>"; ?></td>
	<td align="right"><?=$row["email_alias_nb"]?></td>
</tr>
<?php
}
?>
</table>
<?php
if ($nb)
{
?>
<p><select name="_domain_action" onchange="if (this.value && confirm('Êtes-vous certain ?')) this.form.submit(); else this.selectedIndex = 0;">
	<option value="">-- Choisissez une action --</option>
	<option value="delete">Effacer</option>
	<option value="email_activate">Réactiver gestion email</option>
	<option value="email_disable">Désactiver gestion emails</option>
</select> : sur la sélection</p>
<?php
}
?>
</form>
