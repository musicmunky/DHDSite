<?php
	define('LIBRARY_CHECK',true);
	require 'library.php';
	date_default_timezone_set('America/New_York');

	$comid = $_GET['id'];
	$rspns = "";

	if($comid == "" || preg_match("/^\d+$/", $comid))
	{
		$rspns = getComicJson($comid);
	}
	else
	{
		$rspns = getErrorJson();
	}

	echo json_encode($rspns, JSON_UNESCAPED_SLASHES);
?>