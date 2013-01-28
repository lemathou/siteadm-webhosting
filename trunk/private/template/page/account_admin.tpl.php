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
			$row["quota"] = $a->root_quota();
			preg_match("/".str_replace("/", "\/", QUOTA_DISK)."[ ]*([0-9A-Z]+)[ ]*([0-9A-Z]+)[ ]*/", $row["quota"], $matches);
			if (substr($matches[1], -1, 1) == "M" && substr($matches[1], 0, -1) >= 1024)
			{
				$row["quota1_k"] = substr($matches[1], 0, -1)*1024;
				$row["quota1"] = substr($matches[1], 0, -1)/1024;
				$row["quota1_u"] = "G";
			}
			elseif (substr($matches[1], -1, 1) == "M")
			{
				$row["quota1_k"] = substr($matches[1], 0, -1)*1024;
				$row["quota1"] = substr($matches[1], 0, -1);
				$row["quota1_u"] = "M";
			}
			elseif (substr($matches[1], -1, 1) == "K" && substr($matches[1], 0, -1) >= 1024)
			{
				$row["quota1_k"] = substr($matches[1], 0, -1);
				$row["quota1"] = substr($matches[1], 0, -1)/1024;
				$row["quota1_u"] = "M";
			}
			elseif (substr($matches[1], -1, 1) == "K")
			{
				$row["quota1_k"] = substr($matches[1], 0, -1);
				$row["quota1"] = substr($matches[1], 0, -1);
				$row["quota1_u"] = "K";
			}
			if (substr($matches[2], -1, 1) == "M" && substr($matches[2], 0, -1) >= 1024)
			{
				$row["quota2_k"] = substr($matches[2], 0, -1)*1024;
				$row["quota2"] = substr($matches[2], 0, -1)/1024;
				$row["quota2_u"] = "G";
			}
			elseif (substr($matches[2], -1, 1) == "M")
			{
				$row["quota2_k"] = substr($matches[2], 0, -1)*1024;
				$row["quota2"] = substr($matches[2], 0, -1);
				$row["quota2_u"] = "M";
			}
			elseif (substr($matches[2], -1, 1) == "K" && substr($matches[2], 0, -1) >= 1024)
			{
				$row["quota2_k"] = substr($matches[2], 0, -1);
				$row["quota2"] = substr($matches[2], 0, -1)/1024;
				$row["quota2_u"] = "M";
			}
			elseif (substr($matches[2], -1, 1) == "K")
			{
				$row["quota2_k"] = substr($matches[2], 0, -1);
				$row["quota2"] = substr($matches[2], 0, -1);
				$row["quota2_u"] = "K";
			}
			$offer = $a->offer();
			?>
		<tr>
			<td><input type="checkbox" name="list_id[]" value="<?=$row["id"]?>" />
			</td>
			<td><a href="?id=<?php echo $a->id; ?>"><?php echo $a->name; ?>
			</a>
			</td>
			<td><?php echo $a->system_user(); ?>
			</td>
			<td><?php
			if ($a->type=="admin")
				echo "<b style=\"color:red\">";
			elseif ($row["type"]=="manager")
			echo "<b>";
			?><?php echo $account_type_list[$a->type]; ?>
				<?php
				if ($row["type"]=="admin" || $row["type"]=="manager")
					echo "</b>";
				?></td>
			<td><?php if ($m=$a->manager()) {
				?><a href="?id=<?php echo $m->id; ?>"><?php echo $m->name; ?>
			</a>
			<?php
			} ?></td>
			<td><?php echo $a->prenom; ?>
			</td>
			<td><?php echo $a->nom; ?>
			</td>
			<td><?php echo $a->societe; ?>
			</td>
			<td><?php if ($offer) echo $offer; ?>
			</td>
			<td align="right"><?php
			echo round($row["quota1"], 2).$row["quota1_u"]." / ".((isset($row["quota2"]) && $row["quota2"])?(round($row["quota2"], 2).$row["quota2_u"]):"&infin;");
			if (isset($row["quota2_k"]) && $row["quota2_k"])
				echo "<br />".round(100*$row["quota1_k"]/$row["quota2_k"], 2)."&nbsp;%";
			?></td>
			<td align="right"><a href="domain.php?account_id=<?=$row["id"]?>"><?=$row["domain_nb"]?>
			</a>
			</td>
			<td align="right"><a href="php.php?account_id=<?=$row["id"]?>"><?=$row["php_nb"]?>
			</a>
			</td>
			<td align="right"><a href="email.php?account_id=<?=$row["id"]?>"><?=$row["email_nb"]?>
			</a>
			</td>
			<td align="right"><a href="website.php?account_id=<?=$row["id"]?>"><?=$row["website_nb"]?>
			</a>
			</td>
			<td align="right"><a href="mysql.php?account_id=<?=$row["id"]?>"><?=$row["mysql_nb"]?>
			</a>
			</td>
		</tr>
		<?php
}
?>
	</table>
</form>

