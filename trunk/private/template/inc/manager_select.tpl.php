<form method="get">
	<p>
		Manager : <select name="manager_id" onchange="this.form.submit();"><option></option>
			<?php
			unset($manager);
			$query_string = "SELECT `id`, `nom`, `prenom`, `name` FROM `account` WHERE `type`='manager'";
			$query = mysql_query($query_string);
			while($row=mysql_fetch_assoc($query))
			{
				if (isset($_GET["manager_id"]) && $_GET["manager_id"] == $row["id"])
				{
					$manager = account($row["id"]);
					echo "<option value=\"$row[id]\" selected>$row[nom] $row[prenom] [$row[name]]</option>";
				}
				else
					echo "<option value=\"$row[id]\">$row[nom] $row[prenom] [$row[name]]</option>";
			}
			?>
		</select> <input type="submit" value="Gérer" />
	</p>
</form>

