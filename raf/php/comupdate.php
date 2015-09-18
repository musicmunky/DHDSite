<?php

	define('LIBRARY_CHECK',true);
	require 'library.php';
/*
	FIELD NAMES:
	edit_curr 		=> path to current comic (can't update)
	edit_cfile 		=> new file for comic
	edit_cname 		=> current comic name
	h_edit_cname 	=> old comic name
	edit_comicid 	=> comic id
	edit_ctitle 	=> current comic title
	h_edit_ctitle 	=> old comic title
	edit_calt 		=> current alt text
	h_edit_calt 	=> old alt text
	edit_csub 		=> current sub text
	h_edit_csub 	=> old sub text
	edit_ctags 		=> current tags
	h_edit_ctags 	=> old tags
	edit_golive		=> set new go live status
	edit_golivedate	=> set new go live date
*/
	
	if(isset($_POST['comicupdate']) && $_POST['comicupdate'] == "Update Comic")
	{
		$COMICID = $_POST['edit_comicid'];
		$COMICID = urldecode($COMICID);
		$COMICID = mysql_real_escape_string($COMICID);

		if(isset($_FILES['edit_cfile']['name']) && $_FILES['edit_cfile']['name'] != "")
		{
			//upload a new file and update the database
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $_FILES["edit_cfile"]["name"]);
			$extension = end($temp);
			
			$filename = $_POST['edit_cname'];
			
			if ((($_FILES["edit_cfile"]["type"] == "image/gif")
					|| ($_FILES["edit_cfile"]["type"] == "image/jpeg")
					|| ($_FILES["edit_cfile"]["type"] == "image/jpg")
					|| ($_FILES["edit_cfile"]["type"] == "image/pjpeg")
					|| ($_FILES["edit_cfile"]["type"] == "image/x-png")
					|| ($_FILES["edit_cfile"]["type"] == "image/png"))
					&& ($_FILES["edit_cfile"]["size"] < 50000000)
					&& in_array($extension, $allowedExts))
			{
				if ($_FILES["edit_cfile"]["error"] > 0)
				{
					exit("<script type='text/javascript'>alert('Error uploading file: return code " . $_FILES["edit_cfile"]["error"] . "');</script>");
				}
				else
				{
					if(file_exists("../dhdcomics/" . $filename))
					{
						exit("<script type='text/javascript'>alert('There is already a comic with the name: " . $filename . "');</script>");
					}
					else
					{
						//exit("<script type='text/javascript'>alert('I GOT HERE2');</script>");
						$oldname = trim($_POST['h_edit_cname']);
						$ul = unlink("../dhdcomics/" . $oldname);
						if($ul)
						{				
							move_uploaded_file($_FILES["edit_cfile"]["tmp_name"], "../dhdcomics/" . $filename);
							chmod("../dhdcomics/" . $filename, 0744);
							$checkfn = mysql_fetch_assoc(mysql_query("SELECT post_id FROM postmeta WHERE meta_key='comic_file' AND post_id='" . $COMICID . "';"));
							if(isset($checkfn['post_id']))
							{
								$update_query = mysql_query("UPDATE postmeta
															 SET meta_value='" . $filename . "'
															 WHERE meta_key='comic_file'
															 	AND post_id='" . $COMICID . "';");
							}
							else
							{
								$cmcmetatags = mysql_query("INSERT INTO postmeta (post_id, meta_key, meta_value)
												VALUES ('" . $COMICID . "', 'comic_file', '" . $filename . "');");
							}
						}
						else
						{
							exit("<script type='text/javascript'>alert('There was an issue removing the old file - please contact your administrator');</script>");
						}
					}
				}
			}
		}

		//update the file name in the system and on the database (assuming no new file)
		if(isset($_POST['edit_cname']) && $_POST['edit_cname'] != "" && $_POST['edit_cname'] != $_POST['h_edit_cname'] && (!isset($_FILES['edit_cfile']['name']) || $_FILES['edit_cfile']['name'] == ""))
		{
			$cname = urldecode($_POST['edit_cname']);
			$cname = mysql_real_escape_string($cname);
			$oldname = trim($_POST['h_edit_cname']);
			$rn = rename ("../dhdcomics/" . $oldname, "../dhdcomics/" . $cname);
			if($rn)
			{
				$checkfn = mysql_fetch_assoc(mysql_query("SELECT post_id FROM postmeta WHERE meta_key='comic_file' AND post_id='" . $COMICID . "';"));
				if(isset($checkfn['post_id']))
				{
					$update_query = mysql_query("UPDATE postmeta
												 SET meta_value='" . $cname . "'
												 WHERE meta_key='comic_file'
												 	AND post_id='" . $COMICID . "';");
				}
				else
				{
					$cmcmetatags = mysql_query("INSERT INTO postmeta (post_id, meta_key, meta_value)
												VALUES ('" . $COMICID . "', 'comic_file', '" . $cname . "');");
				}
			}
			else
			{
				exit("Could not rename comic - please try again, or contact your administrator!");
			}
		}

		if($_POST['edit_ctitle'] != $_POST['h_edit_ctitle'])
		{
			//update comic title and 'post_name' field in db
			$ctitle = urldecode($_POST['edit_ctitle']);
			$ctitle = mysql_real_escape_string($ctitle);

			$pname = preg_replace('/[\W]+/', '-', $ctitle);
			$pname = strtolower($pname);
			$pname = urldecode($pname);
			$pname = mysql_real_escape_string($pname);
			
			$update_query = mysql_query("UPDATE posts
										 SET post_title='" . $ctitle . "', post_name='" . $pname . "'
										 WHERE ID='" . $COMICID . "'
										 	AND (post_status='publish' OR post_status='pending')
												AND post_type='post';");
		}

		if($_POST['edit_calt'] != $_POST['h_edit_calt'])
		{
			//update comic alt text
			$calt = urldecode($_POST['edit_calt']);
			$calt = mysql_real_escape_string($calt);
			$checkalt = mysql_fetch_assoc(mysql_query("SELECT post_id FROM postmeta WHERE meta_key='comic_description' AND post_id='" . $COMICID . "';"));
			if(isset($checkalt['post_id']))
			{
				$update_query = mysql_query("UPDATE postmeta 
											 SET meta_value='" . $calt . "' 
											 WHERE meta_key='comic_description'
											 	AND post_id='" . $COMICID . "';");
			}
			else
			{
				$cmcmetatags = mysql_query("INSERT INTO postmeta (post_id, meta_key, meta_value)
											VALUES ('" . $COMICID . "', 'comic_description', '" . $calt . "');");
			}
		}

		if($_POST['edit_csub'] != $_POST['h_edit_csub'])
		{
			//update comic sub text
			$csub = urldecode($_POST['edit_csub']);
			$csub = mysql_real_escape_string($csub);
			$update_query = mysql_query("UPDATE posts
										 SET post_content='" . $csub . "'
										 WHERE ID='" . $COMICID . "'
										 	AND (post_status='publish' OR post_status='pending')
												AND post_type='post';");
		}
		
		if($_POST['edit_ctags'] != $_POST['h_edit_ctags'])
		{
			//update comic tags
			$ctags = urldecode($_POST['edit_ctags']);
			$ctags = mysql_real_escape_string($ctags);
			
			$checktag = mysql_fetch_assoc(mysql_query("SELECT post_id FROM postmeta WHERE meta_key='comic_tags' AND post_id='" . $COMICID . "';"));
			if(isset($checktag['post_id']))
			{
				$update_query = mysql_query("UPDATE postmeta 
											 SET meta_value='" . $ctags . "' 
											 WHERE meta_key='comic_tags'
											 	AND post_id='" . $COMICID . "';");
			}
			else
			{
				$cmcmetatags = mysql_query("INSERT INTO postmeta (post_id, meta_key, meta_value)
											VALUES ('" . $COMICID . "', 'comic_tags', '" . $ctags . "');");
			}
		}

		//update comic date info
		$date = date('Y-m-d H:i:s');
		$gmdate = gmdate('Y-m-d H:i:s');
		$postnow = urldecode($_POST['edit_golive']);
		$postnow = mysql_real_escape_string($postnow);
		$poststatus = "";
		if($postnow == "immediately")
		{
			$poststatus = "publish";
			$golivedate = $date;
		}
		else
		{
			$d = strtotime($_POST['edit_golivedate']);
			$golivedate  = date('Y-m-d H:i:s', $d);
			$poststatus = "pending";
		}
		$update_query = mysql_query("UPDATE posts
									 SET post_status='" . $poststatus . "', post_live_date='" . $golivedate . "'
									 WHERE ID='" . $COMICID . "'
									 	AND (post_status='publish' OR post_status='pending')
											AND post_type='post';");

		sleep(1);
		echo "<script type='text/javascript'>
					alert('The comic was updated successfully!');
					window.top.window.finishUpdate(1," . $COMICID . ");
				</script>";
	}
?>