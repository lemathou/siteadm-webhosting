<form method="post" action="account.php">
<h3>Liste des comptes utilisateur</h3>
<table cellspacing="2" cellpadding="2" border="1">
<tr class="colname">
	<td>&nbsp;</td>
	<td>Username</td>
	<td>SSH/FTP</td>
	<td>Type</td>
	<td>Manager</td>
	<td>Prénom</td>
	<td>Nom</td>
	<td>Entreprise</td>
	<td>OFFRE</td>
	<td>Quota Disque</td>
	<td>Domaines</td>
	<td>PHP-FPM</td>
	<td>Boites email</td>
	<td>Sites web</td>
	<td>MySQL</td>
</tr>
<?php
$s_list = array("1"=>"KO", "MO", "GO");
foreach($account_list as $row) if (!isset($manager) || $row["manager_id"] == $manager->id)
{
	$a = account($row["id"]);
	list($row["domain_nb"]) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM domain WHERE account_id=$row[id]"));
	list($row["php_nb"]) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM phppool as t1 WHERE t1.account_id=$row[id]"));
	list($row["email_nb"]) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM email as t1, domain as t2 WHERE t1.domain_id=t2.id AND t2.account_id=$row[id]"));
	list($row["website_nb"]) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM website as t1, domain as t2 WHERE t1.domain_id=t2.id AND t2.account_id=$row[id]"));
	list($row["mysql_nb"]) = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM db as t1 WHERE t1.account_id=$row[id]"));
	if (true && file_exists("/home/siteadm/$row[name]")) // @todo : à effectuer en sudo
	{
		exec("sudo du -s /home/siteadm/$row[name]", $row["disk_usage"]);
		$row["disk_usage"] = array_shift(explode("\t", $row["disk_usage"][0]));
		$row["disk_usage_h"] = $row["disk_usage"];
		$row["disk_usage_u"] = 1;
		while ($row["disk_usage_h"]>1024)
		{
			$row["disk_usage_h"] = $row["disk_usage_h"]/1024;
			$row["disk_usage_u"]++;
		}
	}
	else
	{
		
	}
?>
<tr>
	<td><input type="checkbox" name="list_id[]" value="<?=$row["id"]?>" /></td>
	<td><a href="?id=<?=$row["id"]?>"><?=$row["name"]?></a></td>
	<td><?php echo $row["name"].$row["id"]; ?></td>
	<td><? if ($row["type"]=="admin") echo "<b style=\"color:red\">"; elseif ($row["type"]=="manager") echo "<b>"; ?><?=$account_type_list[$row["type"]]?><? if ($row["type"]=="admin" || $row["type"]=="manager") echo "</b>"; ?></td>
	<td><a href="?id=<?=$row["manager_id"]?>"><?=$row["manager_name"]?></a></td>
	<td><?=$row["prenom"]?></td>
	<td><?=$row["nom"]?></td>
	<td><?=$row["societe"]?></td>
	<td><?=$offre_list[$row["offre_id"]]["name"]?></td>
	<td align="right"><?php if (isset($row["disk_usage_h"])) { echo round($row["disk_usage_h"], 2)." ".$s_list[$row["disk_usage_u"]]; if ($row["offre_id"] && ($row["disk_quota"]=$offre_list[$row["offre_id"]]["disk_quota"])) { $r = ($row["disk_usage"]/1024/1024/$row["disk_quota"]*100); echo " / ".$row["disk_quota"]." GO (".round($r, 2)." %)"; } } else echo "<i>Empty</i>"; ?></td>
	<td align="right"><a href="domain.php?account_id=<?=$row["id"]?>"><?=$row["domain_nb"]?></a></td>
	<td align="right"><a href="php.php?account_id=<?=$row["id"]?>"><?=$row["php_nb"]?></a></td>
	<td align="right"><a href="email.php?account_id=<?=$row["id"]?>"><?=$row["email_nb"]?></a></td>
	<td align="right"><a href="website.php?account_id=<?=$row["id"]?>"><?=$row["website_nb"]?></a></td>
	<td align="right"><a href="mysql.php?account_id=<?=$row["id"]?>"><?=$row["mysql_nb"]?></a></td>
</tr>
<?php
}
?>
</table>
</form>

