<?php

$db = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
mysql_select_db(MYSQL_DB);
mysql_query("SET NAMES UTF8");

