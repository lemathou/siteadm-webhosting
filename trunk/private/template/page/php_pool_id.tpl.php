<h1>
	Pool Applicatif :
	<?php echo $phppool->name; ?>
</h1>

<div style="float: right; width: 320px;" class="cadre">
	<h3>FPM STATUS</h3>
	<h3>FPM PING</h3>
	<h3>Error log file</h3>
	<div style="border: 1px black solid; padding: 4px;">
		<?php echo $account->root_tail($phppool->errorlog_file()); ?>
	</div>
	<h3>Slow log file</h3>
	<div style="border: 1px black solid; padding: 4px;">
		<?php echo $account->root_tail($phppool->slowlog_file()); ?>
	</div>
	<h3>Mail log file</h3>
	<div style="border: 1px black solid; padding: 4px;">
		<?php echo $account->root_tail($phppool->maillog_file()); ?>
	</div>
	<div>
		<h3>PHP INFO</h3>
		<iframe
			src="php_info.php?id=<?php echo $phppool->phpapp()->language_bin_id; ?>"></iframe>
	</div>
	<h3>Compile log file</h3>
	<div style="border: 1px black solid; padding: 4px;">
		<?php //echo $account->root_tail("/home/siteadm_admin/sources/compile-php-5.3.6-test.log"); ?>
	</div>
	<h3>Compile error log file</h3>
	<div style="border: 1px black solid; padding: 4px;">
		<?php  ?>
	</div>
</div>

<?php

$form_submit_name = "_phppool_update";
$form_submit_text = "Mettre à jour";

include "template/form/phppool.tpl.php";

?>

<div>
	<?php
	$query_website_where = "WHERE t1.phppool_id='$phppool->id'";
	include "template/page/website_list.tpl.php";
?>
</div>
