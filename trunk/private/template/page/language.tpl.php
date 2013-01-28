<div style="width: 900px;" class="cadre">
	<h3>Langages disponibles</h3>
	<table cellspacing="2" cellpadding="2" border="1" width="100%">
		<tr class="colname">
			<td>Nom</td>
			<td>Content-type</td>
			<td>Extension list</td>
		</tr>
		<?php
		$query = mysql_query("SELECT * FROM language");
		while ($row=mysql_fetch_assoc($query))
		{
			echo "<tr>\n";
			echo "<td>$row[name]</td>\n";
			echo "<td>$row[content_type]</td>\n";
			echo "<td>$row[extension_list]</td>\n";
			echo "<tr>\n";
		}
		?>
	</table>
</div>

<div style="width: 900px;" class="cadre">
	<h3>Langages</h3>
	<table cellspacing="2" cellpadding="2" border="1" width="100%">
		<tr class="colname">
			<td>Nom</td>
			<td>Version</td>
			<td>App compatible</td>
			<td>Options</td>
			<td>CGI type</td>
			<td>Prefix</td>
			<td>Exec bin path</td>
		</tr>
		<?php
		$query_string = "SELECT `id` FROM `language_bin`";
		$query = mysql_query($query_string);
		while (list($id)=mysql_fetch_row($query))
		{
			$language_bin = language_bin($id);
			?>
		<tr>
			<td><?php echo $language_bin->language()->name; ?></td>
			<td><?php echo $language_bin->version; ?></td>
			<td><?php if ($language_bin->app_compatible) echo "PHPAPP"; ?></td>
			<td><?php echo $language_bin->options; ?></td>
			<td><?php echo $language_bin->cgi_type; ?></td>
			<td><?php echo $language_bin->prefix; ?></td>
			<td><?php echo $language_bin->exec_bin; ?></td>
		</tr>
		<?php
}
?>
	</table>
</div>
