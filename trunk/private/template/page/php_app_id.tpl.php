<h1>Processus PHP parent : <?php echo $phpapp->name; ?></h1>

<div style="float: right;width: 320px;" class="cadre">
<h3>Error log file</h3>
<div style="border: 1px black solid;padding: 4px;overflow: auto;max-height: 200px;">
<?php echo $phpapp->account()->root_tail($phpapp->errorlog_file()); ?>
</div>
<h3>Processus</h3>
<div style="border: 1px black solid;padding: 4px;overflow: auto;max-height: 200px;">
<?php $phpapp->root_process_list(); ?>
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
