<table>
<tr>
	<td>ID</td>
	<td>Nom</td>
	<td>Description</td>
	<td>tarif</td>
	<td>Quota disque</td>
	<td>Quota Base de donnée</td>
	<td>Quota RAM</td>
	<td>Quota processeur</td>
</tr>
<?php
$query = mysql_query("SELECT * FROM offre");
while ($offer = mysql_fetch_assoc($query))
{
	echo "<tr>\n";
	echo "<td>$offer[id]</td>\n";
	echo "<td>$offer[name]</td>\n";
	echo "<td>$offer[description]</td>\n";
	echo "<td align=\"right\">$offer[tarif]</td>\n";
	echo "<td align=\"right\">$offer[disk_quota]</td>\n";
	echo "<tr>\n";
}
?>
</table>
