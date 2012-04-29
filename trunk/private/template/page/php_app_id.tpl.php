<h1>Processus PHP parent : <?php echo $phpapp->name; ?></h1>

<div style="float: right;width: 320px;" class="cadre">
<h3>Error log file</h3>
<div style="border: 1px black solid;padding: 4px;overflow: auto;max-height: 200px;">
<?php $exec = ""; exec("sudo /usr/bin/tail ".$phpapp->errorlog_file(), $exec); echo implode("<hr />\n", $exec); ?>
</div>
<h3>Processus</h3>
<div style="border: 1px black solid;padding: 4px;overflow: auto;max-height: 200px;">
<?php if (file_exists($phpapp->pid())) { $exec = ""; exec("sudo ps -g ".$phpapp->pid(), $exec); echo implode("<br />\n", $exec); } ?>
</div>
</div>

<?php

$form_submit_name = "_phpapp_update";
$form_submit_text = "Mettre à jour";

include "template/form/phpapp.tpl.php";

?>

<div>
<?php
$query_phppool_where = "WHERE t1.phpapp_id=$phpapp->id";
include "template/page/php_pool_list.tpl.php";
?>
</div>

<hr />
<form>
<p><input type="button" value="Recharger PHP-FPM" onclick="php_reload()" /></p>
</form>
