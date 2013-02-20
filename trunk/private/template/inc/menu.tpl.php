<div class="login">
<form method="post">
<p><?php echo login()->name; ?> <input type="submit" name="_login[disconnect]" value="Disconnect" /></p>
</form>
</div>

<div class="menu">
<p>
<?php
$t = array();
foreach ($menu_list as $i=>$j)
	if ($menu == $i)
		$t[] = "<span class=\"selected\"><a href=\"$i.php\">$j</a></span>";
	else
		$t[] = "<span><a href=\"$i.php\">$j</a></span>";
echo implode(" | ", $t);
?>
</p>
</div>
