<h3>Pools Applicatifs (avec mémoire partagée) :</h3>
<table cellspacing="2" cellpadding="2" border="1">
<?php
$query_string = "SELECT t1.*, t2.name as phpapp_name, t2.language_bin_id as phpapp_language_bin_id, t3.name as account_name, COUNT(t4.id) as website_nb FROM phppool as t1 LEFT JOIN phpapp as t2 ON t1.phpapp_id=t2.id LEFT JOIN account as t3 ON t2.account_id=t3.id LEFT JOIN website as t4 ON t1.id=t4.phppool_id $query_phppool_where GROUP BY t1.id ORDER BY t1.account_id, t1.name";
$query = mysql_query($query_string);
if ($nb = mysql_num_rows($query))
{
?>
<tr class="colname">
	<td valign="top">Nom</td>
	<td valign="top">Propriétaire</td>
	<td valign="top">Processus parent</td>
	<td valign="top">Language</td>
	<td valign="top">Workers max</td>
	<td valign="top">Memory max</td>
	<td valign="top">Websites</td>
</tr>
<?php
}
else
{
	echo "<p>Aucun pool</p>";
}
while($row=mysql_fetch_assoc($query))
{
?>
<tr>
	<td><a href="?account_id=<?php echo $row["account_id"]; ?>&pool_id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
	<td><?php if ($row["account_id"]) echo account($row["account_id"])->link(); else echo "Common"; ?></td>
	<td><?php
	if ($row["phpapp_id"])
	{
		$language_bin = language_bin($row["phpapp_language_bin_id"]);
		if ($row["account_name"])
			echo "<a href=\"php.php?app_id=$row[phpapp_id]\">[$row[account_name]]$row[phpapp_name]</a>";
		else
			echo "<a href=\"php.php?app_id=$row[phpapp_id]\">$row[phpapp_name]</a>";
	}
	else
	{
		if ($row["langage_id"])
			$language_bin = language_bin($row["language_bin_id"]);
		echo "<i>Aucun</i>";
	}
	?></td>
	<td><?php echo $language_bin; ?></td>
	<td align="right"><?=$row["worker_nb_max"]?></td>
	<td align="right"><?=$row["memory_limit"]?> M</td>
	<td align="right"><?=$row["website_nb"]?></td>
</tr>
<?php
}
?>
</table>
