<table>
	<tr>
		<td rowspan="2">Nom</td>
		<td rowspan="2">Description</td>
		<td rowspan="2">tarif</td>
		<td colspan="2">Quota disque</td>
		<td rowspan="2">Quota Base de donnée</td>
		<td rowspan="2">Quota RAM</td>
		<td rowspan="2">Quota processeur</td>
	</tr>
	<tr>
		<td>Soft</td>
		<td>Hard</td>
	</tr>
	<?php
	$query = mysql_query("SELECT * FROM offre ORDER BY tarif, disk_quota_soft");
	while ($offer = mysql_fetch_assoc($query))
	{
		echo "<tr>\n";
		echo "<td>$offer[name]</td>\n";
		echo "<td>$offer[description]</td>\n";
		echo "<td align=\"right\">$offer[tarif] &euro;</td>\n";
		echo "<td align=\"right\">$offer[disk_quota_soft] GO</td>\n";
		echo "<td align=\"right\">$offer[disk_quota_hard] GO</td>\n";
		echo "<tr>\n";
	}
?>
</table>
