<?php

/**
  * Copyright 2008-2012 Mathieu Moulin - lemathou@free.fr
  * 
  * Licence : http://www.gnu.org/copyleft/gpl.html  GNU General Public License
  * 
  */

/**
 * Filesystem management
 * 
 * @package siteadm
 */

class filesystem
{

/**
 * Change file owner
 * @param string $filename
 * @param string $usergroup
 * @param bool $recursive
 */
static function chown($filename, $usergroup, $recursive=false)
{

$options = "";
if ($recursive)
	$options .= " -R";

exec("chown$options $usergroup \"$filename\"");

}

/**
 * Change file mode
 * @param string $filename
 * @param string $mode
 */
static function chmod($filename, $mode)
{

if (file_exists($filename))
	exec("chmod $mode \"$filename\"");

}

/**
 * Update ACL for a file or an folder
 * @param string $filename
 * @param string $user
 * @param string $mode
 */
static function setacl($filename, $user, $mode="rx")
{

if (file_exists($filename))
	exec("setfacl -m u:".$user.":".$mode." ".$filename);

}

static function exists($filename)
{
	return file_exists($filename);
}

/**
 * Write data in a file
 * @param string $filename
 * @param string $contents
 */
static function write($filename, $contents="")
{

if ((!file_exists($filename) || !is_dir($filename)) && ($fp_to=fopen($filename, "w")))
{
	fwrite($fp_to, $contents);
	fclose($fp_to);
}

}

/**
 * Create a folder
 * @param string $file
 */
static function mkdir($file)
{

if (!is_string($file) || @file_exists($file))
	return false;

return @mkdir($file);

}

/**
 * 
 * @param string $filename_from
 * @param string $filename_to
 */
static function link($filename_from, $filename_to)
{

if (!file_exists($filename_from) || (file_exists($filename_to) && !is_link($filename_to)))
	return;

if (is_link($filename_to))
	self::unlink($filename_to);

return @symlink($filename_from, $filename_to);

}

/**
 * Delete a single file or empty folder
 * @param string $file
 */
static function unlink($file)
{

if (!is_string($file) || !@file_exists($file))
	return false;

if (is_dir($file))	
	return @rmdir($file);
else
	return @unlink($file);

}
static function rm($file)
{

static::unlink($file);

}

/**
 * Rename a function
 * @param string $from
 * @param string $to
 */
static function rename($from, $to)
{

return @rename($from, $to);

}

/**
 * Delete an entire folder, recursively
 * @param string $folder
 * @param boolean $recursive
 */
static function rmdir($folder, $recursive=true)
{

if (!is_string($folder) || !@file_exists($folder))
	return false;

if (is_dir($folder))
{
	//echo "<p>Deleting $folder ...</p>\n";
	$fp = opendir($folder);
	while($file = readdir($fp)) if ($file != "." && $file != "..")
	{
		if (is_dir("$folder/$file"))
		{
			//echo "<p>-> Deleting $folder/$file ...</p>\n";
			self::rmdir("$folder/$file", $recursive);
		}
		else
		{
			//echo "<p>-> Deleting $folder/$file ...</p>\n";
			@unlink("$folder/$file");
		}
	}
	return @rmdir($folder);
}
else
{
	return @unlink($folder);
}

}

/**
 * Copy an entire folder
 * @param string $source
 * @param string $dest
 */
static function copydir($source, $dest)
{

if (!is_string($source) || !is_string($dest) || !@is_dir($source) || !(@is_dir($dest) || self::mkdir($dest)))
	return false;

$command1 = "cp -a ".str_replace(" ", "\\ ", $source)."/* ".str_replace(" ", "\\ ", $dest)."/";
$command1 = "cp -a ".str_replace(" ", "\\ ", $source)."/.??* ".str_replace(" ", "\\ ", $dest)."/";
//echo "<p>$command1</p>";
//echo "<p>$command2</p>";

$ok = true;
@system($command1, $ok);
if ($ok)
	@system($command2, $ok);

if (!function_exists("system") || !$ok)
{
	$fp = opendir($source);
	while($file = readdir($fp)) if ($file != "." && $file != "..")
	{
		if (@is_dir("$source/$file"))
		{
			if (!self::copydir("$source/$file", "$dest/$file"))
				return false;
		}
		elseif (@is_file("$source/$file"))
		{
			if (!@copy("$source/$file", "$dest/$file"))
				return false;
		}
	}
}

return true;

}

/* DISPLAY */

/**
 *
 * @param mixed $folder
 */
static function foldersize($folder)
{

	$s = 0;
	$nb = 0;
	if (is_array($folder))
	{
		foreach($folder as $f) if (file_exists($f))
		{
			$j = exec("sudo du -sc $f");
			$nb += substr($j, 0, strpos($j, "\t"));
		}
	}
	elseif (file_exists($folder))
	{
		$j = exec("sudo du -sc $folder");
		$nb += substr($j, 0, strpos($j, "\t"));
	}

	while ($nb > 1024)
	{
		$s++;
		$nb = $nb/1024;
	}

	$nb = round($nb, 2);
	if ($s == 0)
		return "$nb KO";
	elseif ($s == 1)
		return "$nb MO";
	elseif ($s == 2)
		return "$nb GO";
	else
		return "$nb TO";

}

static function tail($filename)
{

if (!@file_exists($filename))
	return;

return shell_exec("tail $filename");

}

/**
 * Display a file using its mime type
 * @param string $file
 */
static function display($file, $opt=array())
{

	if (!@file_exists($file))
		return;

	session_cache_limiter("no-cache");
	header("Content-Length: ".filesize($file));
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	header("Content-type: ".$finfo->file($file));
	header("Content-Disposition: inline; filename=\"".addslashes(array_pop(explode("/", $file))))."\"";
	readfile($file);

}

/**
 * Navigate into a folder
 * 
 * @param string $path
 */
static function folder_disp($path, $opt=array())
{

// Verify opt
if (!is_array($opt))
	$opt = array();
if (!isset($opt["limit"]))
	$opt["limit"] = "1";
if (!isset($opt["hidden"]))
	$opt["hidden"] = "0";
if (!isset($opt["file_choose_name"]))
	$opt["file_choose_name"] = "";

// verify cols
if (!isset($opt["cols"]) || !is_array($opt["cols"]))
	$opt["cols"] = array("type", "size");
else foreach($opt["cols"] as $i=>$j)
{
	if (!in_array($j, array("type", "size", "created", "updated", "perm", "thumb")))
		unset($opt["cols"][$i]);
}
$colnum = count($opt["cols"])+4;
// Verify $origin exists
echo $opt["origin"];
if (!isset($opt["origin"]) || !is_string($origin=$opt["origin"]) || !file_exists($origin) || !is_dir($origin))
{
	$e = explode("/", $_SERVER["SCRIPT_FILENAME"]);
	array_pop($e);
	$origin = implode("/", $e);
}
// Verify $path exists
if (!is_string($path) || !file_exists("$origin/$path") || !is_dir("$origin/$path"))
	$path = ".";
// Limit path to origin
if ($opt["limit"] && is_numeric(strpos($path, "..")))
	$path = ".";

// Analyse $path structure
if ($path == ".")
{
	$fullpath = "$origin";
	$path_parent = "..";
}
elseif (strpos($path, "..") === false)
{
	$fullpath = "$origin/$path";
	$e = explode("/", $path);
	array_pop($e);
	if (count($e))
		$path_parent = implode("/", $e);
	else
		$path_parent = ".";
}
else
{
	$a = $e = explode("/", $path);
	$b = explode("/", $origin);
	foreach($a as $i=>$j)
	{
		if ($j == "..")
		{
			unset($a[$i]);
			array_pop($b);
		}
	}
	$fullpath = implode("/", $b)."/".implode("/", $a);
	if ($e[count($e)-1] == "..")
		$path_parent = implode("/", $e)."/..";
	else
	{
		array_pop($e);
		$path_parent = implode("/", $e);
	}
}
?>
<form method="post">
<h3><?php echo $fullpath; ?> <input name="file_choose" type="button" value="Choose" onclick="path_choose('<?php echo $opt["file_choose_name"]; ?>', '<?php echo $fullpath; ?>')" /></h3>
<p><a href="<?php echo "?path=$path&file_choose_name=$opt[file_choose_name]&hidden=".((!$opt["hidden"]) ? "1" : "0"); ?>">Hidden files</a></p>
<input type="hidden" name="action" />
<table cellspacing="2" cellpadding="0" width="100%">
<tr>
	<td colspan="<?php echo $colnum; ?>"><hr /></td>
</tr>
<tr style="font-weight: bold;">
	<td width="20">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td>Name</td>
	<?
	if (in_array("type", $opt["cols"])) echo "<td width=\"150\">Type</td>\n";
	if (in_array("size", $opt["cols"])) echo "<td width=\"50\">Size</td>\n";
	if (in_array("created", $opt["cols"])) echo "<td width=\"100\">Created</td>\n";
	if (in_array("updated", $opt["cols"])) echo "<td width=\"100\">Updated</td>\n";
	if (in_array("perm", $opt["cols"])) echo "<td width=\"50\">Perm</td>\n";
	?>
	<!-- <td width="50">Propriétaire</td> -->
</tr>
<?php

// Set parent path
if ((!$opt["limit"] || !is_numeric(strpos($path_parent, ".."))) && @is_dir("$origin/$path_parent"))
{
?>
<tr>
	<td colspan="<?php echo $colnum; ?>"><hr /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><a href="?path=<?php echo $path_parent; ?>&file_choose_name=<?php echo $opt["file_choose_name"]; ?>&hidden=<?php echo $opt["hidden"]; ?>" style="text-decoration: none;">&lt;=</a></td>
	<td>..</td>
</tr>
<tr>
	<td colspan="<?php echo $colnum; ?>"><hr /></td>
</tr>
<?php
}

// Retrieve folders and files
$folder_list = array();
$file_list = array();
$fp = opendir("$origin/$path");
while($file = readdir($fp)) if ($file != "." && $file != "..")
{
	if (substr($file, 0, 1) == "." && isset($opt["hidden"]) && !$opt["hidden"])
	{
	}
	elseif (@is_dir("$origin/$path/$file"))
		$folder_list[] = $file;
	elseif (@is_file("$origin/$path/$file"))
		$file_list[] = $file;
}

if (!count($folder_list) && !count($file_list))
{
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>No files found</td>
</tr>
<?
}

// Display folders
sort($folder_list);
if (count($folder_list) && preg_match("/^\.\.((\/\.\.)*)$/", $path))
{
	$e = explode("/", $path);
	$f = explode("/", $origin);
	array_pop($e);
}
foreach($folder_list as $file)
{
	if (isset($f) && $file == $f[count($f)-count($e)-1])
	{
		if (count($e))
			$file_path = implode("/", $e);
		else
			$file_path = ".";
	}
	elseif ($path == ".")
		$file_path = $file;
	else
		$file_path = "$path/$file";
	if (substr($file, 0, 1) == ".")
		echo "<tr style=\"color: gray;\">\n";
	else
		echo "<tr>\n";
	$filesize = filesize("$origin/$file_path");
	$filesize_u = 0;
	while ($filesize > 1024)
	{
		$filesize = round($filesize/1024);
		$filesize_u++;
	}
	if ($filesize_u == 1)
		$filesize .= "&nbsp;KO";
	elseif ($filesize_u == 2)
		$filesize .= "&nbsp;MO";
	elseif ($filesize_u == 3)
		$filesize .= "&nbsp;GO";
	echo "<td><a href=\"javascript:;\" onclick=\"if (confirm('Are you sure you want to delete this folder ?')) file_delete('$file');\" style=\"color: red;text-decoration: none;\">X</a></td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td><a href=\"?path=$file_path&hidden=$opt[hidden]&file_choose_name=$opt[file_choose_name]\" style=\"text-decoration: none;\">=&gt;</a></td>\n";
	echo "<td><input value=\"$file\" onchange=\"file_rename(this, '$file');\" /></td>\n";
	if (in_array("type", $opt["cols"])) echo "<td>Folder</td>\n";
	if (in_array("size", $opt["cols"])) echo "<td align=\"right\">$filesize</td>\n";
	if (in_array("created", $opt["cols"])) echo "<td>".date("Y-m-d", filectime("$origin/$file_path"))."</td>\n";
	if (in_array("updated", $opt["cols"])) echo "<td>".date("Y-m-d", filemtime("$origin/$file_path"))."</td>\n";
	if (in_array("perm", $opt["cols"])) echo "<td>".substr(decoct(fileperms("$origin/$file_path")), -3, 3)."</td>\n";
	//echo "<td>".fileowner($file_path)."</td>\n";
	echo "</tr>\n";
}

// Display files
sort($file_list);
$finfo = new finfo(FILEINFO_MIME_TYPE);
foreach($file_list as $file)
{
	if ($path == ".")
		$file_path = $file;
	else
		$file_path = "$path/$file";
	if (substr($file, 0, 1) == ".")
		echo "<tr style=\"color: gray;\">\n";
	else
		echo "<tr>\n";
	$filesize = filesize("$origin/$file_path");
	$filesize_u = 0;
	while ($filesize > 1024)
	{
		$filesize = round($filesize/1024);
		$filesize_u++;
	}
	if ($filesize_u == 1)
		$filesize .= "&nbsp;KO";
	elseif ($filesize_u == 2)
		$filesize .= "&nbsp;MO";
	elseif ($filesize_u == 3)
		$filesize .= "&nbsp;GO";
	echo "<td><a href=\"javascript:;\" onclick=\"if (confirm('Are you sure you want to delete this file ?')) file_delete('$file');\" style=\"color: red;text-decoration: none;\">X</a></td>\n";
	$filetype = $finfo->file("$fullpath/$file");
	if (substr($filetype, 0, 5) == "image" || substr($filetype, 0, 5) == "audio" || substr($filetype, 0, 5) == "video" || substr($filetype, 0, 4) == "text")
		echo "<td><a href=\"javascript:;\" onclick=\"file_view('".urlencode("$fullpath/$file")."')\">V</a></td>\n";
	else
		echo "<td>&nbsp;</td>\n";
	echo "<td>&nbsp;</td>\n";
	echo "<td><input value=\"$file\" onchange=\"file_rename(this, '$file');\" /></td>\n";
	if (in_array("type", $opt["cols"])) echo "<td>$filetype</td>\n";
	if (in_array("size", $opt["cols"])) echo "<td align=\"right\">$filesize</td>\n";
	if (in_array("created", $opt["cols"])) echo "<td>".date("Y-m-d", filectime("$origin/$file_path"))."</td>\n";
	if (in_array("updated", $opt["cols"])) echo "<td>".date("Y-m-d", filemtime("$origin/$file_path"))."</td>\n";
	if (in_array("perm", $opt["cols"])) echo "<td>".substr(decoct(fileperms("$origin/$file_path")), -3, 3)."</td>\n";
	//echo "<td>".fileowner($file_path)."</td>\n";
	echo "</tr>\n";
}

// New folder form
?>
<tr>
	<td colspan="<?php echo $colnum; ?>"><hr /></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><input id="folder_create" onchange="if (this.value) this.name=this.id;" /></td>
	<td colspan="4"><input type="submit" value="Create folder" /></td>
</tr>
</table>
</form>
<form method="post">
</form>
<?php

}

}

?>
