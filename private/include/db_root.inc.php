<?php

$db = mysql_connect(MYSQL_HOST, MYSQL_ADMIN_USER, MYSQL_ADMIN_PASS);
mysql_select_db(MYSQL_DB);
mysql_query("SET NAMES UTF8");

?>