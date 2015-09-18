<?php
	if(!defined('INCLUDE_CHECK')) die ('You are not allowed to execute this file directly');
	
	/* Database config */
	$db_host		= 'localhost:3306';
	$db_user		= 'doghouse';
	$db_pass		= 'aibah5Ie';
	$db_database	= 'dbdoghouse'; 
	/* End config */
	
	$link = mysql_connect($db_host,$db_user,$db_pass) or die('Unable to establish a DB connection');
	
	mysql_select_db($db_database,$link);
	//$mysqli = new mysqli("localhost", "scduser", "Password99", "elementsdb");
	mysql_query("SET names UTF8");
?>