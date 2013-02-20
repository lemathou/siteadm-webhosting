<?php

if (isset($_SESSION["account_id"]))
{
	$domain_query_where = "WHERE account_id='".$_SESSION["account_id"]."'";
	$website_query_where = array ( "account.account_id='".$_SESSION["account_id"]."'" );
}
else
{
	$domain_query_where = "";
	$website_query_where = array( "1" );
}

?>