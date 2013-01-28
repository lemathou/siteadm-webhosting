<form method="get">
	<p>
		Domaine : <select name="domain_id" onchange="this.form.submit()"><option></option>
			<?php
			$query_string = "SELECT id, name FROM `domain` WHERE `account_id`='".login()->id."' ORDER BY `name`";
			$query = mysql_query($query_string);
			while($row=mysql_fetch_assoc($query))
			{
				if (isset($_GET["domain_id"]) && $row["id"] == $_GET["domain_id"])
				{
					$domain = domain($row["id"]);
					echo "<option value=\"$row[id]\" selected>$row[name]</option>";
				}
				else
					echo "<option value=\"$row[id]\">$row[name]</option>";
			}
			?>
		</select> <input type="submit" value="Changer" />
	</p>
</form>
