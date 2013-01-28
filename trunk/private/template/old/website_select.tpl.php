<form method="get" action="">
	<p>
		<select name="id" onchange="if (this.value != '') this.form.submit();"><option
				value="">-- Site web à configurer --</option>
			<?php
			$query = mysql_query("SELECT website.id, website.name, domain.name as domain_name, account.id as account_id FROM website JOIN domain ON website.domain_id=domain.id JOIN account ON domain.account_id=account.id WHERE ".implode(" AND ",$website_query_where)." ORDER BY domain.name, website.name");
			while ($row = mysql_fetch_assoc($query))
			{
				if (isset($_GET["id"]) && $_GET["id"] == $row["id"])
				{
					$id = $row["id"];
					echo "<option value=\"$row[id]\" selected>$row[name].$row[domain_name]</option>";
				}
				else
					echo "<option value=\"$row[id]\">$row[name].$row[domain_name]</option>";
			}
			?>
		</select> <input type="submit" value="Gérer" />
	</p>
</form>

