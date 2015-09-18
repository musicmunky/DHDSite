#!/usr/bin/php
<?php 
	define('INCLUDE_CHECK',true);
	require 'connect.php';	
	date_default_timezone_set('America/New_York');
	
	$comics =  mysql_query("SELECT ID FROM posts
							WHERE NOW() > post_live_date
								AND post_live_date != '0000-00-00 00:00:00'
									AND post_status='pending'
										AND post_type='post'
											ORDER BY ID;");
	$html = "";
	$ids = array();
	while($row = mysql_fetch_array($comics))
	{
		array_push($ids, $row['ID']);
	}
	
	foreach ($ids as $id)
	{
		mysql_query("UPDATE posts SET post_status='publish' WHERE ID='" . $id . "';");
	}
	exit();
?>
