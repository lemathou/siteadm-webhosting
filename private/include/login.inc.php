<?php

session_start();

if (isset($_POST["_login"]["username"]) && isset($_POST["_login"]["password"]))
{
	login()->connect($_POST["_login"]["username"], $_POST["_login"]["password"]);
}

if (isset($_POST["_login"]["disconnect"]))
{
	login()->disconnect();
}

?>