<h1>Liste des Applications web utilisables</h1>

<table border="1">
	<tr>
		<td>Nom</td>
		<td>Version</td>
		<td>Description</td>
		<td>Folder Alias</td>
		<td>PHP include_folder</td>
		<td>PHP open_basedir</td>
		<td>PHP short_open_tag</td>
	</tr>
	<?php

	$query = mysql_query("SELECT * FROM `webapp` ORDER BY `name`, `version`");
	while($row=mysql_fetch_assoc($query))
	{
		echo "<tr>\n";
		echo "<td>$row[name]</td>\n";
		echo "<td>$row[version]</td>\n";
		echo "<td>$row[description]</td>\n";
		echo "<td>$row[folder_alias]</td>\n";
		echo "<td>$row[php_include_folder]</td>\n";
		echo "<td>$row[php_open_basedir]</td>\n";
		echo "<td>$row[php_short_open_tag]</td>\n";
		echo "</tr>\n";
	}

	?>
</table>
