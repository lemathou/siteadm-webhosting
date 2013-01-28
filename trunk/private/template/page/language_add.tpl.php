<div>
	<h3>Compiler un binaire PHP</h3>
	<table>
		<tr class="colname">
			<td>&nbsp;</td>
			<td>Option</td>
			<td>Description</td>
		</tr>
		<?php
		$query = mysql_query("SELECT t1.* FROM langage_compile_options as t1 WHERE t1.langage_id=1");
		while ($row=mysql_fetch_assoc($query))
		{
			echo "<tr>\n";
			echo "<td><input type=\"checkbox\" name=\"option[$row[id]]\" /></td>\n";
			echo "<td>$row[name]</td>\n";
			echo "<td>$row[desc]</td>\n";
			echo "<td></td>\n";
			echo "<tr>\n";
		}
		?>
	</table>
	<input type="submit" value="COMPILER" />
</div>
