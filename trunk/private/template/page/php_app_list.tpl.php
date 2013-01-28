<h3>Processus mères avec mémoire partagée :</h3>
<table cellspacing="2" cellpadding="2" border="1">
	<?php
	$query_string = "SELECT t1.*, COUNT(t2.id) as phppool_nb, SUM(t2.worker_nb_max) as worker_nb, COUNT(t3.id) as website_nb FROM phpapp as t1 LEFT JOIN phppool as t2 ON t1.id = t2.phpapp_id LEFT JOIN website as t3 ON t2.id=t3.phppool_id $query_phpapp_where GROUP BY t1.id ORDER BY t1.account_id, t1.name";
	$query = mysql_query($query_string);
	if ($nb = mysql_num_rows($query))
	{
		?>
	<tr class="colname">
		<td valign="top">Nom</td>
		<td valign="top">Propriétaire</td>
		<td valign="top">Langage</td>
		<td valign="top">Mémoire partagée</td>
		<td valign="top">Pools</td>
		<td valign="top">Workers</td>
		<td valign="top">Sites web</td>
	</tr>
	<?php
	}
	else
	{
		echo "<p>Aucun processus.</p>";
	}
	while($row=mysql_fetch_assoc($query))
	{
		//var_dump(language_bin($row["language_bin_id"]));
		?>
	<tr>
		<td><a
			href="?account_id=<?php echo $row["account_id"]; ?>&app_id=<?=$row["id"]?>"><?=$row["name"]?>
		</a></td>
		<td><?php if ($row["account_id"]) {  
			echo account($row["account_id"])->link();
		} else { echo "<i>Partagé</i>";
		} ?>
		</td>
		<td><?php if ($row["language_bin_id"]) { 
			echo language_bin($row["language_bin_id"]);
		} ?>
		</td>
		<td align="right"><?=$row["apc_shm_size"]?> M</td>
		<td align="right"><?=$row["phppool_nb"]?></td>
		<td align="right"><?php echo ($row["worker_nb"])?$row["worker_nb"]:"0"; ?>
		</td>
		<td align="right"><?=$row["website_nb"]?></td>
	</tr>
	<?php
}
?>
</table>
