<?

if (isset($_FILES))
{
	//print_r($_FILES);
	foreach($_FILES as $file)
	{
		echo "<p>$file[name] :</p>\n";
		echo "<p>Moving from $file[tmp_name] to here</p>\n";
		move_uploaded_file("$file[tmp_name]", "test/$file[name]");
	}
}

?>

<form enctype="multipart/form-data" method="post">
<p><input type="file" name="file" /> <input type="submit" value="Envoyer" /></p>
</form>

