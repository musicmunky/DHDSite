<?php
	define('INCLUDE_CHECK',true);
	
	require 'connect.php';

	$q = strtolower($_GET["q"]);
	$q = mysql_real_escape_string($q);
	
	$orstate = " (post_title like '%" . $q . "%' or post_content like '%" . $q . "%') and";
	if($_GET['ga'] == 1)
	{
		$orstate = "";
	}
	
	$sql = mysql_query("select ID, post_title, post_date, post_status, post_live_date
						from posts 
						where" . $orstate . " post_type='post' and (post_status='publish' or post_status='pending' or post_status='inactive')
								order by post_date desc;");
	$numrows = mysql_num_rows($sql);
	$tablehtml = "";
	$count = 0;
	if($numrows > 0)
	{
		while($row = mysql_fetch_assoc($sql)) 
		{
			$count++;
			$altclass = ($count % 2) ? "mainrow" : "altrow";
			$hidclass = $altclass;
			if($row['post_status'] == "publish")
			{
				$poststat = "published";
				$stattitl = "";
			}
			elseif($row['post_status'] == "pending")
			{
				$altclass = "pendingrow";
				$poststat = "pending";
				$stattitl = " title='Live Date: " . substr($row['post_live_date'], 0, 10) . "'";
			}
			else
			{
				$altclass = "inactiverow";
				$poststat = "inactive";
				$stattitl = " title='Currently not displayed'";
			}
			//$altclass = ($row['post_status'] == "publish") ? $altclass : "pendingrow";
			//$poststat = ($row['post_status'] == "publish") ? "published" : "pending";
			//$stattitl = ($row['post_status'] == "publish") ? "" : " title='Live Date: " . substr($row['post_live_date'], 0, 10) . "'";
			$tablehtml .= "<tr id='" . $row['ID'] . "' class='" . $altclass . "'" . $stattitl . ">
							<td class='comid'><div style='text-align:center;'>
								<span style='cursor:pointer;font-weight:bold;' onclick='fillEditForm(\"" . $row['ID'] . "\");'>" . $row['ID'] . "</span>
								</div></td>
							<td class='comtitle'><div id='title_" . $row['ID'] . "'>" . $row['post_title'] . "</div></td>
							<td class='comdate'><div id='postdate_" . $row['ID'] . "'>" . $row['post_date'] . "</div></td>
							<td class='comstat'><div id='poststat_" . $row['ID'] . "'>" . $poststat . "</div></td>
							<td id='postclass_" . $row['ID'] . "' style='display:none;visibility:hidden;'>" . $hidclass . "</td>
							</tr>";
		}
	}
	else
	{
		$tablehtml = "<tr class='mainrow'><td colspan='3'>No comics found!</td></tr>";
	}

	$message = "";
	$status  = "success";
	$result  = array(
			"status"	=> $status,
			"message"	=> $message,
			"content"	=> array(
					"tablehtml"	 => $tablehtml,
					"count" 	 => $numrows,
			)
	);
	
	echo json_encode($result);
?>