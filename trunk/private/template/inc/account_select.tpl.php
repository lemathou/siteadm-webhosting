<form method="get">
<p>Compte : <select name="account_id" onchange="this.form.submit();"><option></option><?php
foreach ($account_list as $row)
{
	if (isset($_GET["account_id"]) && $_GET["account_id"] == $row["id"])
	{
		$account = account($row["id"]);
		echo "<option value=\"$row[id]\" selected>$row[nom] $row[prenom] [$row[name]]</option>";
	}
	else
		echo "<option value=\"$row[id]\">$row[nom] $row[prenom] [$row[name]]</option>";
}
?>
</select> <input type="submit" value="Gérer" /></p>
</form>

