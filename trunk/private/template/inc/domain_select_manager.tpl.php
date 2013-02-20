<form method="get">
<p>
Compte : <select name="account_id" onchange="this.form.submit();"><option></option><?php
unset($account);
foreach ($account_list as $row)
{
	if (isset($_GET["account_id"]) && $_GET["account_id"] == $row["id"])
	{
		$account = account($row["id"]);
		$query_where_list[] = "t1.`account_id`=$account->id";
		echo "<option value=\"$row[id]\" selected>$row[nom] $row[prenom] [$row[name]]</option>";
	}
	else
		echo "<option value=\"$row[id]\">$row[nom] $row[prenom] [$row[name]]</option>";
}
?>
</select> <input type="submit" value="Gérer" />
<?php if (isset($account)) { ?>
Domaine : <select name="domain_id" onchange="this.form.submit()"><option></option>
<?php
$query_string = "SELECT t1.id, t1.name FROM `domain` as t1 LEFT JOIN account as t2 ON t1.account_id=t2.id WHERE ".implode("AND", $query_where_list)." ORDER BY `name`";
$query = mysql_query($query_string);
while($row=mysql_fetch_assoc($query))
{
	if ($row["id"] == $_GET["domain_id"])
	{
		$domain = domain($row["id"]);
		echo "<option value=\"$row[id]\" selected>$row[name]</option>";
	}
	else
		echo "<option value=\"$row[id]\">$row[name]</option>";
}
?>
</select> <input type="submit" value="Changer" />
<?php } else { ?>
Domaine : <select name="domain_id" onchange="this.form.submit()"><option></option>
<?php
$query_string = "SELECT t1.id, t1.name FROM `domain` as t1 LEFT JOIN account as t2 ON t1.account_id=t2.id WHERE t2.manager_id=".login()->id." ORDER BY `name`";
$query = mysql_query($query_string);
while($row=mysql_fetch_assoc($query))
{
	if ($row["id"] == $_GET["domain_id"])
	{
		$domain = domain($row["id"]);
		echo "<option value=\"$row[id]\" selected>$row[name]</option>";
	}
	else
		echo "<option value=\"$row[id]\">$row[name]</option>";
}
?>
</select> <input type="submit" value="Changer" />
<?php } ?>
</p>
</form>