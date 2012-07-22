<h3>Quota / Utilisation / Statistiques</h3>
<div style="margin: 5px;border: 1px gray solid;padding: 4px;">
<table width="100%" cellspacing="0" cellpadding="0">

<tr>
	<td>Dossier de stockage</td>
	<td><?php echo $account->folder(); ?></td>
</tr>

<tr>
	<td valign="top">Espace disque</td>
	<td><table width="100%" cellspacing="0" cellpadding="0">
	<tr><td>Stockage publique (sites, FTP, etc.) :</td> <td align="right"><?php echo $account->root_foldersize($account->public_folder()); ?></td></tr>
	<tr><td>Stockage privé sécurisé (clefs, etc.) :</td> <td align="right"><?php echo $account->root_foldersize($account->private_folder()); ?></td></tr>
	<tr><td>Emails :</td> <td align="right"><?php echo $account->root_foldersize($account->email_folder()); ?></td></tr>
	<tr><td>Backups :</td> <td align="right"><?php echo $account->root_foldersize($account->backup_folder()); ?></td></tr>
	<tr><td>Logs & stats :</td> <td align="right"><?php echo $account->root_foldersize($account->log_folder()); ?></td></tr>
	<tr><td>Fichiers de configuration :</td> <td align="right"><?php echo $account->root_foldersize($account->conf_folder()); ?></td></tr>
	<tr><td>Cookies :</td> <td align="right"><?php echo $account->root_foldersize($account->session_folder()); ?></td></tr>
	<tr><td>Fichies temporaire :</td> <td align="right"><?php echo $account->root_foldersize($account->tmp_folder()); ?></td></tr>
	<tr><td><b>Utilisation totale / Quota :</b></td> <td><?php echo $account->root_foldersize($account->folder()); ?></td>
	<td><?php
	if ($offer=offer($account->offre_id))
	{
		if ($offer->disk_quota)
			echo " / $offer->disk_quota GO";
		else
			echo " / illimité";
	}
	?></td></tr>
	</table></td>
</tr>

<tr>
	<td>Sites web :</td>
	<td><?php
	echo array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM website as t1, domain as t2 WHERE t1.domain_id=t2.id AND t2.account_id=$account->id")));
	?></td>
</tr>
<tr>
	<td>Alias web :</td>
	<td><?php
	echo array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM website_alias as t1, domain as t2 WHERE t1.domain_id=t2.id AND t2.account_id=$account->id")));
	?></td>
</tr>
<tr>
	<td>Boites email</td>
	<td><?php
	echo array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM email as t1, domain as t2 WHERE t1.domain_id=t2.id AND t2.account_id=$account->id")));
	?></td>
</tr>
<tr>
	<td>Alias email :</td>
	<td><?php
	echo array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM email_alias as t1, domain as t2 WHERE t1.domain_id=t2.id AND t2.account_id=$account->id")));
	?></td>
</tr>
<tr>
	<td>PHP-FPM :</td>
	<td><?php
	echo array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM phppool as t1 WHERE t1.account_id=$account->id")));
	?></td>
</tr>
<tr>
	<td>Bases de donnée :</td>
	<td><?php
	echo array_pop(mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM `mysql` as t1 WHERE t1.`account_id`='$account->id'")));
	/*
	$query_string = "SELECT SUM(t1.data_length+t1.index_length) as db_size FROM information_schema.tables as t1, siteadm_dev.mysql as t2, siteadm_dev.account as t3 WHERE t1.table_schema = t2.name AND t2.account_id = t3.id AND t3.id=$account->id GROUP BY t3.id";
	$query = mysql_query($query_string);
	echo mysql_num_rows($query);
	list($db_size) = mysql_fetch_row($query);
	echo "$db_size index";
	*/
	?></td>
</tr>

<tr>
	<td valign="top">Processus :</td>
	<td><?php
	exec("ps u -G ".$account->system_id(), $ps);
	echo implode("<br />", $ps);
	?></td>
</tr>

</table>
</div>