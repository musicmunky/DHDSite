<?php
	define('LIBRARY_CHECK',true);
	require 'library.php';

	if(isset($_POST['comicsubmit']) && $_POST['comicsubmit'] == "Upload Comic")
	{
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["cfile"]["name"]);
		$extension = end($temp);
	
		$filename = $_POST['cname'];
	
		if ((($_FILES["cfile"]["type"] == "image/gif")
				|| ($_FILES["cfile"]["type"] == "image/jpeg")
				|| ($_FILES["cfile"]["type"] == "image/jpg")
				|| ($_FILES["cfile"]["type"] == "image/pjpeg")
				|| ($_FILES["cfile"]["type"] == "image/x-png")
				|| ($_FILES["cfile"]["type"] == "image/png"))
				&& ($_FILES["cfile"]["size"] < 50000000)
				&& in_array($extension, $allowedExts))
		{
			if ($_FILES["cfile"]["error"] > 0)
			{
				echo "<script type='text/javascript'>
						alert('Error uploading file: return code " . $_FILES["cfile"]["error"] . "');
						window.top.window.finishUpload(0,0);
						</script>";
			}
			else
			{
				if(file_exists("../dhdcomics/" . $filename))
				{
					echo "<script type='text/javascript'>
							alert('There is already a comic with the name: " . $filename . "\\nPlease use the \'Edit\' tab to modify an existing comic');
							window.top.window.finishUpload(0,0);
							</script>";
				}
				else
				{
					move_uploaded_file($_FILES["cfile"]["tmp_name"], "../dhdcomics/" . $filename);
					chmod("../dhdcomics/" . $filename, 0744);
						
					/*
					posts table
					post_author = user id
					post_content = subtext
					post_title = comic title / page title
					post_status = "publish"
					post_name = post_title without puncuation or spaces, spaces replaced with hyphens
					post_parent = 0
					post_type = "post"
					post_date = post_modified = TIMESTAMP
					post_date_gmt = post_modified_gmt = TIMESTAMP
					guid = http://thedoghousediaries.com/?p=NEW_COMIC_ID
					post_live_date
					
					postmeta table
					post_id = ID of comic just uploaded
					meta_key =>
							comic_file = file name
							comic_description = alt-text

					FIELDS:
					post_author
					post_date
					post_date_gmt
					post_content
					post_title
					post_status
					post_name
					post_modified
					post_modified_gmt
					post_parent
					guid
					post_type
					post_live_date
					*/
						
					$date = date('Y-m-d H:i:s');
					$gmdate = gmdate('Y-m-d H:i:s');
					$pname = preg_replace('/[\W]+/', '-', $_POST['ctitle']);
					$pname = strtolower($pname);
					$pname = urldecode($pname);
					$pname = mysql_real_escape_string($pname);
						
					$ctitle = $_POST['ctitle'];
					$ctitle = urldecode($ctitle);
					$ctitle = mysql_real_escape_string($ctitle);
						
					$csub = $_POST['csub'];
					$csub = urldecode($csub);
					$csub = mysql_real_escape_string($csub);
						
					$calt = $_POST['calt'];
					$calt = urldecode($calt);
					$calt = mysql_real_escape_string($calt);
	
					$ctags = $_POST['ctags'];
					$ctags = urldecode($ctags);
					$ctags = mysql_real_escape_string($ctags);
						
					$postnow = $_POST['golive'];
					$poststatus = "";
					if($postnow == "immediately")
					{
						$poststatus = "publish";
						$golivedate = $date;
					}
					else
					{
						$d = strtotime($_POST['golivedate']);
						$golivedate  = date('Y-m-d H:i:s', $d);
						$poststatus = "pending";
					}

					$cmcdata = mysql_query("INSERT INTO posts  (post_author, post_date, post_date_gmt, post_content, post_title,
																post_status, post_name, post_modified, post_modified_gmt,
																post_parent, post_type, post_live_date)
														VALUES ('" . $_SESSION['userid'] . "', '" . $date . "', '" . $gmdate . "',
																'" . $csub . "', '" . $ctitle . "',
																'" . $poststatus . "', '" . $pname . "', '" . $date . "', '" . $gmdate . "',
																'0', 'post', '" . $golivedate . "');");
					$comicid = mysql_insert_id();
					$cmcmetaid   = mysql_query("INSERT INTO postmeta (post_id, meta_key, meta_value)
												VALUES ('" . $comicid . "', 'comic_file', '" . $filename . "');");
						
					$cmcmetadesc = mysql_query("INSERT INTO postmeta (post_id, meta_key, meta_value)
												VALUES ('" . $comicid . "', 'comic_description', '" . $calt . "');");
						
					$cmcmetatags = mysql_query("INSERT INTO postmeta (post_id, meta_key, meta_value)
												VALUES ('" . $comicid . "', 'comic_tags', '" . $ctags . "');");
	
					$_POST['cfile'] 	 = "";
					$_POST['cname'] 	 = "";
					$_POST['ctitle'] 	 = "";
					$_POST['calt'] 		 = "";
					$_POST['csub'] 		 = "";
					$_POST['ctags'] 	 = "";
					$_POST['golive'] 	 = "";
					$_POST['golivedate'] = "";
	
					$updateguid = mysql_query("UPDATE posts SET guid='http://thedoghousediaries.com/?p=" . $comicid . "' WHERE ID='" . $comicid . "';");

					echo "<script type='text/javascript'>
							window.top.window.finishUpload(1, " . $comicid . ");
						</script>";
				}
			}
			sleep(1);
		}
		else
		{
			echo "<script type='text/javascript'>
					alert('There was an error during file upload!');
					window.top.window.finishUpload(0,0);
				</script>";
		}
	}
?>