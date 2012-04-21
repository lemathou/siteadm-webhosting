<h3>Langages</h3>
<table cellspacing="2" cellpadding="2" border="1">
<tr class="colname">
	<td>Nom</td>
	<td>Version</td>
	<td>Options</td>
	<td>CGI type</td>
	<td>Exec bin path</td>
	<td>Content-type</td>
	<td>Extension list</td>
</tr>
<?php
foreach(login()->language_bin_list() as $language_bin)
{
?>
<tr>
	<td><?php echo $language_bin->language()->name; ?></td>
	<td><?php echo $language_bin->version; ?></td>
	<td><?php echo $language_bin->options; ?></td>
	<td><?php echo $language_bin->cgi_type; ?></td>
	<td><?php echo $language_bin->exec_path_bin; ?></td>
	<td><?php echo $language_bin->content_type; ?></td>
	<td><?php echo $language_bin->extension_list; ?></td>
</tr>
<?php
}
?>
</table>
