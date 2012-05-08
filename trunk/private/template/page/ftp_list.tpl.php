<form method="post">
<table cellspacing="2" cellpadding="2" border="1">
<?php

if ($account)
	$query_ftp_where = "WHERE t2.id='".$account->id."'";
else
	$query_ftp_where = "";

$query_string = "SELECT * FROM ftp_user ORDER BY account_id, username";
$query = mysql_query($query_string);

if ($nb = mysql_num_rows($query))
{
?>
<tr class="colname">
	<td>&nbsp;</td>
	<td>Compte</td>
	<td>Nom d'utilisateur</td>
	<td>Type</td>
	<td>Sous-dossier</td>
</tr>
<?php
}
else
{
	echo "<p>Aucun compte FTP</p>";
}

while($row=mysql_fetch_assoc($query))
{
	$ftp = new ftp(null, $row);
?>
<tr>
	<td><input type="checkbox" name="_list_id[]" value="<?php echo $ftp->id; ?>" /></td>
	<td><?php echo $ftp->account(); ?></td>
	<td><a href="?id=<?php echo $ftp->id; ?>"><?php echo $ftp->username(); ?></a></td>
	<td><?php echo $ftp->type; ?></td>
	<td><?php echo $ftp->folder; ?></td>
</tr>
<?php
}

?>
</table>
</form>