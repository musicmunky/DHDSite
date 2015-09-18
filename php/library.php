<?php
	if(isset($_POST['libcheck']) && !empty($_POST['libcheck'])){
		define('LIBRARY_CHECK', true);
	}
	if(!defined('LIBRARY_CHECK')){
		die ('<div style="width:100%;height:100%;text-align:center;">
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:100px;">
					Sorry, this isn\'t a real page, so I have nothing to show you :-(
				</div>
				<div style="width:100%;font-family:Georgia;font-size:2em;margin-top:30px;margin-bottom:30px;">Wait, here\'s a funny cat!</div>
				<div style="background-repeat:no-repeat;margin-left:auto;margin-right:auto;width:500px;height:280px;background:url(../logos/cat.gif)"></div>
			</div>');
	}

	define('INCLUDE_CHECK',true);
	require 'connect.php';
	date_default_timezone_set('America/New_York');
/*
	ini_set('session.gc_maxlifetime', 24*60*60);
	ini_set('session.gc_probability',1);
	ini_set('session.gc_divisor',100);
	if(!isset($_SESSION))
	{
		session_name('dhdmaintenance');
		session_start();
	}
*/

	$webaddress = "http://thedoghousediaries.com/";

	if(isset($_POST['method']) && !empty($_POST['method']))
	{
		$method = $_POST['method'];
		$method = urldecode($method);
		$method = mysql_real_escape_string($method);

		switch($method)
		{
			case 'getAjaxComic': getAjaxComic($_POST);
				break;
			case 'updateUser': updateUser($_POST);
				break;
			case 'createUser': createUser($_POST);
				break;
			case 'updatePassword': updatePassword($_POST);
				break;
			case 'getComicInfo': getComicInfo($_POST);
				break;
			case 'getComicTable': getComicTable($_POST);
				break;
			case 'updateAnnouncement': updateAnnouncement($_POST);
				break;
			case 'getAnnouncementForm': getAnnouncementForm($_POST);
				break;
			default: noFunction($_POST);
				break;
		}
		mysql_close($link);
	}

	function noFunction()
	{
		$func = $_POST['method'];
		$result = array(
				"status"	=> "failure",
				"message"	=> "User attempted to call function: " . $func . " which does not exist",
				"content"	=> "You seem to have encountered an error - Contact the DHD web admin if this keeps happening!"
		);
		echo json_encode($result);
	}

	function getAjaxComic($P)
	{
		$currid = $P['value'];
		$currid = urldecode($currid);
		$currid = mysql_real_escape_string($currid);

		$result  = "";
		$content = "";
		$message = "";
		$status  = "";
		$permalink = "";

		$firstload = (isset($P['firstload']) && !empty($P['firstload']) && $P['firstload'] == 1) ? 1 : 0;

		//echo gethostname();
		global $webaddress;

		$first = getFirst();
		$firsthoverlink = isset($first['ID']) ? buildHover($first['ID'],$first['post_title']) : "";

		$rand = getRandom();
		$randhoverlink = isset($rand['ID']) ? buildHover($rand['ID'],$rand['post_title']) : "";

		$latest = getLatest();
		$latesthoverlink = isset($latest['ID']) ? buildHover($latest['ID'],$latest['post_title']) : "";

		$currid = !$currid ? $latest['ID'] : $currid;

		$prev = getPrevious($currid);
		$prevhoverlink = isset($prev['ID']) ? buildHover($prev['ID'],$prev['post_title']) : "";

		$next = getNext($currid);
		$nexthoverlink = isset($next['ID']) ? buildHover($next['ID'],$next['post_title']) : "";

		$firstlink  = "<a id='firstlink' href='" .  $first['ID'] . "' class='comicnavlinks' title='" . htmlentities($firsthoverlink, ENT_QUOTES) . "'>" . htmlspecialchars("<<", ENT_HTML401) . "</a>";
		$latestlink = "<a id='latestlink' href='" . $latest['ID'] . "' class='comicnavlinks' title='" . htmlentities($latesthoverlink, ENT_QUOTES) . "'>" . htmlspecialchars(">>", ENT_HTML401) . "</a>";

		$prevlink = isset($prev['ID']) ?
						"<a id='previouslink' href='" . $prev['ID'] . "' class='comicnavlinks' title='" . htmlentities($prevhoverlink, ENT_QUOTES) . "'>" . htmlspecialchars("<", ENT_HTML401) . "</a>" :
							"";
		$nextlink = isset($next['ID']) ?
						"<a id='nextlink' href='" . $next['ID'] . "' class='comicnavlinks' title='" . htmlentities($nexthoverlink, ENT_QUOTES) . "'>" . htmlspecialchars(">", ENT_HTML401) . "</a>" :
							"";
		$randlink = isset($rand['ID']) ?
						"<a id='randomlink' href='" . $rand['ID'] . "' class='comicnavlinks' title='" . htmlentities($randhoverlink, ENT_QUOTES) . "'>" . htmlspecialchars("?", ENT_HTML401) . "</a>" :
							"";

		$navbuttons = "<ul><li>" . $firstlink . "</li><li>" . $prevlink . "</li>" .
							"<li>" . $randlink . "</li><li>" . $nextlink . "</li>" .
							"<li>" . $latestlink; "</li></ul>";

		$comic = getComicMeta($currid);
		$hover = getHover($currid);

		$comichtml = "";
		$name = "";
		$title = "";
		$subtext = "";
		$titletext = "";

		$globstring = $firstload ? "dhdcomics/" : "../dhdcomics/";

		if(isset($comic['ID']) && isset($comic['post_status']) && $comic['post_status'] == "publish")
		{
/*
			$comfile = mysql_fetch_assoc(mysql_query("SELECT p.ID, p.post_date, p.post_date_gmt, pm.meta_value
													  FROM posts p
													  LEFT JOIN (
														  SELECT pm.post_id, pm.meta_value
														  FROM postmeta pm
														  WHERE pm.meta_key='comic_file'
													  ) pm on p.ID=pm.post_id
													  WHERE p.ID=" . $comic['ID'] . " AND p.post_type='post' AND p.post_status != 'trash';"));
*/
			$name = "";
			$comfile = mysql_fetch_assoc(mysql_query("SELECT meta_value FROM dbdoghouse.postmeta WHERE post_id=" . $comic['ID'] . " AND meta_key='comic_file';"));

			//print_r("COMFILE: " . $comfile['meta_value']);
			if(isset($comfile['meta_value']) && $comfile['meta_value'] != "")
			{
				$ls = shell_exec('ls /var/www/html/dhdcomics | grep "' . $comfile['meta_value'] . '"');
				if($ls != "")
				{
					$name = $ls;
				}
			}

			//print_r("NAMEIS: " . $name);
			if(!isset($comfile['meta_value']) || $comfile['meta_value'] == "" || $name == "")
			{
				$comdate = $comic['post_date'];
				$comdgmt = $comic['post_date_gmt'];
				$cdate = substr($comic['post_date'], 0, 10);
				$cdgmt = substr($comic['post_date_gmt'], 0, 10);
				$ls = shell_exec('ls /var/www/html/dhdcomics | grep ' . $cdate);
				if($ls != "")
				{
					$name = $ls;
				}
				else
				{
					$ls = shell_exec('ls /var/www/html/dhdcomics | grep ' . $cdgmt);
					$name = $ls;
				}
			}

			$name = "dhdcomics/" . $name;
			$titletext = isset($hover['meta_value']) ? htmlentities($hover['meta_value'], ENT_QUOTES) : htmlentities($comic['post_title'], ENT_QUOTES);
			$comichtml = "<img id='comicimg' src='" . $name . "' title='" . $titletext . "' class=''>";
			$title = isset($comic['post_title']) ? $comic['post_title'] : "";
			$subtext = isset($comic['post_content']) ? $comic['post_content'] : "";
			$permalink = "Permalink: <a target='_blank' href='" . $webaddress . $comic['ID'] . "'>" . $webaddress . $comic['ID'] . "</a>";

		}
		else
		{
			$comichtml = "<img id='comicimg' src='dhdcomics/obiwan.jpg' title='Hokey religions and ancient weapons are no match for a good webcomic in your browser...'>";
			$title = "Move along...";
			$subtext = "Without precise calculations we could fly right through a popup or bounce too close to a supernova, and that'd end your trip real quick, wouldn't it?";
			$name = "";
		}

		$topad = "";
		$botad = "";
		$status = "success";
		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> array(
						"title"	 	 => $title,
						"alttext"	 => $titletext,
						"subtxt" 	 => $subtext,
						"navbuttons" => $navbuttons,
						"comic"		 => $comichtml,
						"comicid"	 => $comic['ID'],
						"comicdate"	 => $comic['post_date'],
						"name"		 => $name,
						"permlnk"	 => $permalink,
						"topad"		 => $topad,
						"botad"		 => $botad,
						"comic"		 => $comic['ID'] //just matching the function below...
				)
		);

		if($firstload){
			return $result;
		}
		else{
			echo json_encode($result);
		}
	}

	function getComic($p)
	{
		$currid = $p;
		$currid = urldecode($currid);
		$currid = mysql_real_escape_string($currid);

		$result  = "";
		$content = "";
		$message = "";
		$status  = "";
		$permalink = "";

		global $webaddress;

		$first = getFirst();
		$firsthoverlink = isset($first['ID']) ? buildHover($first['ID'],$first['post_title']) : "";

		$rand = getRandom();
		$randhoverlink = isset($rand['ID']) ? buildHover($rand['ID'],$rand['post_title']) : "";

		$latest = getLatest();
		$latesthoverlink = isset($latest['ID']) ? buildHover($latest['ID'],$latest['post_title']) : "";

		$currid = !$currid ? $latest['ID'] : $currid;

		$prev = getPrevious($currid);
		$prevhoverlink = isset($prev['ID']) ? buildHover($prev['ID'],$prev['post_title']) : "";

		$next = getNext($currid);
		$nexthoverlink = isset($next['ID']) ? buildHover($next['ID'],$next['post_title']) : "";

		$firstlink  = "<a id='firstlink' href='" . $webaddress . $first['ID']  . "' class='comicnavlinks' title='" . htmlentities($firsthoverlink, ENT_QUOTES) . "'>" . htmlspecialchars("<<", ENT_HTML401) . "</a>";
		$latestlink = "<a id='latestlink' href='" . $webaddress . $latest['ID'] . "' class='comicnavlinks' title='" . htmlentities($latesthoverlink, ENT_QUOTES) . "'>" . htmlspecialchars(">>", ENT_HTML401) . "</a>";

		$prevlink = isset($prev['ID']) ? 
						"<a id='previouslink' href='" . $webaddress . $prev['ID'] . "' class='comicnavlinks' title='" . htmlentities($prevhoverlink, ENT_QUOTES) . "'>" . htmlspecialchars("<", ENT_HTML401) . "</a>" :
							"";
		$nextlink = isset($next['ID']) ?
						"<a id='nextlink' href='" . $webaddress . $next['ID'] . "' class='comicnavlinks' title='" . htmlentities($nexthoverlink, ENT_QUOTES) . "'>" . htmlspecialchars(">", ENT_HTML401) . "</a>" :
							"";
		$randlink = isset($rand['ID']) ?
						"<a id='randomlink' href='" . $webaddress . $rand['ID'] . "' class='comicnavlinks' title='" . htmlentities($randhoverlink, ENT_QUOTES) . "'>" . htmlspecialchars("?", ENT_HTML401) . "</a>" :
							"";

		$navbuttons = "<ul><li>" . $firstlink . "</li><li>" . $prevlink . "</li>" .
							"<li>" . $randlink . "</li><li>" . $nextlink . "</li>" .
							"<li>" . $latestlink; "</li></ul>";

		$comic = getComicMeta($currid);
		$hover = getHover($currid);

		$comichtml = "";
		$name = "";
		$title = "";
		$subtext = "";
		$titletext = "";

		//while($row = mysql_fetch_assoc($allcomp))
		if(isset($comic['ID']))
		{
			$name = "";
			$comfile = mysql_fetch_assoc(mysql_query("SELECT meta_value FROM dbdoghouse.postmeta WHERE post_id=" . $comic['ID'] . " AND meta_key='comic_file';"));

			if(isset($comfile['meta_value']) && $comfile['meta_value'] != "")
			{
				$ls = shell_exec('ls /var/www/html/dhdcomics | grep "' . $comfile['meta_value'] . '"');
				if($ls != "")
				{
					$name = $ls;
				}
			}

			if(!isset($comfile['meta_value']) || $comfile['meta_value'] == "" || $name == "")
			{
				$comdate = $comic['post_date'];
				$comdgmt = $comic['post_date_gmt'];
				$cdate = substr($comic['post_date'], 0, 10);
				$cdgmt = substr($comic['post_date_gmt'], 0, 10);
				$ls = shell_exec('ls /var/www/html/dhdcomics | grep ' . $cdate);
				if($ls != "")
				{
					$name = $ls;
				}
				else
				{
					$ls = shell_exec('ls /var/www/html/dhdcomics | grep ' . $cdgmt);
					$name = $ls;
				}
			}

			$name = "dhdcomics/" . $name;
			$titletext = isset($hover['meta_value']) ? htmlentities($hover['meta_value'], ENT_QUOTES) : htmlentities($comic['post_title'], ENT_QUOTES);
			$comichtml = "<img src='" . $name . "' title='" . $titletext . "'>";
			$title = isset($comic['post_title']) ? $comic['post_title'] : "";
			$subtext = isset($comic['post_content']) ? $comic['post_content'] : "";
			$permalink = "Permalink: <a target='_blank' href='" . $webaddress . $comic['ID'] . "'>" . $webaddress . $comic['ID'] . "</a>";

			/*
			$name = substr($comic['post_date'], 0, 10);
			foreach (glob("dhdcomics/" . $name . "*") as $c)
			{
				$titletext = isset($hover['meta_value']) ? htmlentities($hover['meta_value'], ENT_QUOTES) : $comic['post_title'];
				$comichtml = "<img src='" . $c . "' title='" . $titletext . "'>";
				$title = isset($comic['post_title']) ? $comic['post_title'] : "";
				$subtext = isset($comic['post_content']) ? $comic['post_content'] : "";
			}
			*/
		}
		else
		{
			$comichtml = "<img id='comicimg' src='dhdcomics/obiwan.jpg' title='Hokey religions and ancient weapons are no match for a good webcomic in your browser...'>";
			$title = "Move along...";
			$subtext = "Without precise calculations we could fly right through a popup or bounce too close to a supernova, and that'd end your trip real quick, wouldn't it?";
			$name = "";
		}

		$result = array(
					"status"	=> $status,
					"message"	=> $message,
					"content"	=> array(
									"comic"	 	 => $comichtml,
									"title"	 	 => $title,
									"alttext"	 => $titletext,
									"subtxt"	 => $subtext,
									"navbuttons" => $navbuttons,
									"name"		 => $name,
									"permlnk"	 => $permalink,
									"comid"		 => $currid
								)
		);
		return $result;
	}

	function getComicJson($c)
	{
		$currid = $c;
		$currid = urldecode($currid);
		$currid = mysql_real_escape_string($currid);

		$status = "";
		$message = "";

		$frst = getFirst();
		$last = getLatest();
		$rand = getRandom();

		$currid = !$currid ? $last['ID'] : $currid;
		$prev = getPrevious($currid);
		$next = getNext($currid);

		$frstid = $frst['ID'];
		$lastid = $last['ID'];
		$previd = $prev['ID'];
		$nextid = $next['ID'];
		$randid = $rand['ID'];

		$comic = getComicMeta($currid);
		$hover = getHover($currid);
		$title = "";
		$subtext = "";
		$titletext = "";
		$filename = "";

		if(isset($comic['ID']))
		{
			$comfile = mysql_fetch_assoc(mysql_query("SELECT meta_value FROM dbdoghouse.postmeta WHERE post_id=" . $comic['ID'] . " AND meta_key='comic_file';"));
			$filename = $comfile['meta_value'];
			$titletext = isset($hover['meta_value']) ? htmlentities($hover['meta_value'], ENT_QUOTES) : htmlentities($comic['post_title'], ENT_QUOTES);
			$title = isset($comic['post_title']) ? $comic['post_title'] : "";
			$subtext = isset($comic['post_content']) ? $comic['post_content'] : "";
			$status = "success";
			$message = "valid comic";

			$result = array(
					"title"	 	 => $title,
					"alttext"	 => $titletext,
					"subtxt"	 => $subtext,
					"name"		 => $filename,
					"comid"		 => $currid,
					"first"		 => $frstid,
					"last"		 => $lastid,
					"next"		 => $nextid,
					"previous"	 => $previd,
					"random"	 => $randid,
					"status"	 => $status,
					"message"	 => $message
			);
		}
		else
		{
			$result = getErrorJson();
		}
		return $result;
	}

	function getErrorJson()
	{
		$frst = getFirst();
		$last = getLatest();
		$rand = getRandom();
		$currid = $last['ID'];

		$prev = getPrevious($currid);
		$next = getNext($currid);

		$frstid = $frst['ID'];
		$lastid = $last['ID'];
		$previd = $prev['ID'];
		$nextid = $next['ID'];
		$randid = $rand['ID'];

		$result = array(
				"title"  	=> "Move along...",
				"alttext"  	=> "Hokey religions and ancient weapons are no match for a good webcomic in your browser...",
				"subtxt" 	=> "Without precise calculations we could fly right through a popup or bounce too close to a supernova, and that'd end your trip real quick, wouldn't it?",
				"name"	 	=> "obiwan.jpg",
				"comid"		=> $currid,
				"first"		=> $frstid,
				"last"		=> $lastid,
				"next"		=> $nextid,
				"previous"	=> $previd,
				"random"	=> $randid,
				"status"	=> "failure",
				"message"	=> "no comic found"
		);
		return $result;
	}

	function getComicMeta($c)
	{
		$cmc = mysql_fetch_assoc(mysql_query("SELECT ID, post_date, post_date_gmt, post_content, post_title, post_status, post_live_date FROM posts WHERE ID=" . $c . " AND post_type='post' AND post_name !='';"));
		return $cmc;
	}

	function getHover($c)
	{
		$hvr = mysql_fetch_assoc(mysql_query("SELECT meta_value FROM postmeta WHERE post_id=" . $c . " AND meta_key='comic_description';"));
		return $hvr;
	}

	function buildHover($id,$pt)
	{
		$t_hover = mysql_fetch_assoc(mysql_query("SELECT meta_value FROM postmeta WHERE post_id=" . $id . " AND meta_key='comic_description';"));
		$t_hoverlink = isset($t_hover['meta_value']) ? $t_hover['meta_value'] : $pt;
		return $t_hoverlink;
	}

	function getTags($c)
	{
		$tags = mysql_fetch_assoc(mysql_query("SELECT meta_value FROM postmeta WHERE post_id=" . $c . " AND meta_key='comic_tags';"));
		return $tags;
	}

	function getPrevious($c)
	{
		$prv = mysql_fetch_assoc(mysql_query("SELECT ID, post_title FROM posts WHERE post_type='post' AND post_name != '' and post_status='publish' and post_date < (SELECT post_date FROM posts WHERE ID=" . $c . ") ORDER BY post_date DESC LIMIT 0,1;"));
		if(!isset($prv['ID']) || $prv['ID'] == "")
		{
			$prv = getFirst();
		}
		return $prv;
	}

	function getNext($c)
	{
		$nxt = mysql_fetch_assoc(mysql_query("SELECT ID, post_title FROM posts WHERE post_type='post' AND post_name != '' and post_status='publish' and post_date > (SELECT post_date FROM posts WHERE ID=" . $c . ") ORDER BY post_date ASC LIMIT 0,1;"));
		if(!isset($nxt['ID']) || $nxt['ID'] == "")
		{
			$nxt = getLatest();
		}
		return $nxt;
	}

	function getFirst()
	{
		$frst = mysql_fetch_assoc(mysql_query("SELECT ID, post_title FROM posts WHERE post_type='post' AND post_name != '' and post_status = 'publish' ORDER BY post_date ASC LIMIT 0,1;"));
		return $frst;
	}

	function getLatest()
	{
		$ltst = mysql_fetch_assoc(mysql_query("SELECT ID, post_title FROM posts WHERE post_type='post' AND post_name != '' and post_status = 'publish' ORDER BY post_date DESC LIMIT 0,1;"));
		return $ltst;
	}

	function getRandom()
	{
		$rnd = mysql_fetch_assoc(mysql_query("SELECT ID, post_title FROM posts WHERE post_type='post' AND post_name != '' and post_status = 'publish' ORDER BY RAND() LIMIT 0,1;"));
		return $rnd;
	}

	function getArchive()
	{
		$html = "";
		$yearqry = mysql_query("SELECT DISTINCT LEFT(post_date, 4) AS yr FROM posts ORDER BY yr DESC;");
		$yeararray = array();
		while($row = mysql_fetch_assoc($yearqry))
		{
			array_push($yeararray, $row['yr']);
		}

		$yrlnkhtml = "";
		$yrlnkarry = array();
		$format = "Y-m-d H:i:s";
		foreach ($yeararray as $year) {

			array_push($yrlnkarry, "<a style='outline:none;margin-left:10px;margin-right:10px;display:inline-block;' href='#" . $year . "'>" . $year . "</a>");

			$html .= "<div id='" . $year . "'>
						<span style='font-size:16px;font-weight:bold;color:#78C8C8;'>" . $year . "</span>
					</div>
					<table>";

			$qry = mysql_query("SELECT ID, post_date, post_title, guid FROM posts
								WHERE post_type='post'
									AND post_date like '" . $year . "%'
										AND post_name != ''
											ORDER BY post_date DESC;");
			while($row = mysql_fetch_assoc($qry))
			{
				$date = DateTime::createFromFormat($format, $row['post_date']);
				$newdate = $date->format("F jS");
				$title = str_replace("'", "&#39;", $row['post_title']);
				$html .= "<tr>
							<td>" . $newdate . "</td>
							<td>
								<a title='Permanent Link to " . $title . "' href='" . $row['guid'] . "' target='_blank'>" . $row['post_title'] . "</a>
							</td>
						</tr>";
			}
			$html .= "</table>";
		}

		$html = "<div style='text-align:center;width:100%;padding-top:20px;'>" . implode(" - ", $yrlnkarry) . "</div>" . $html;

		$result = array(
				"status"	=> "success",
				"message"	=> "message",
				"content"	=> $html
		);
		return $result;
	}

	function createUser($P)
	{
		global $webaddress;

		foreach($P as $key => $val)
		{
			$val = urldecode($val);
			$val = mysql_real_escape_string($val);
			$P[$key] = $val;
		}

		$uname  = $P['username'];
		$fname  = $P['firstname'];
		$lname  = $P['lastname'];
		$email  = $P['useremail'];

		$status  = "success";
		$message = "";
		$content = "";

		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$count = mb_strlen($chars);
		$password = "";
		$length = 8;
		for ($i = 0, $password = ''; $i < $length; $i++)
		{
			$index = rand(0, $count - 1);
			$password .= mb_substr($chars, $index, 1);
		}
		$hashedpassword = md5($password);

		$insertuser = mysql_query( "INSERT INTO
									dhdadmins (dhduser, dhdfirst, dhdlast, dhdemail, dhdpass)
									VALUES ('" . $uname . "', '" . $fname . "', '" . $lname . "', '" . $email . "', '" . $hashedpassword . "');");
		$userid = mysql_insert_id();
		if(mysql_errno())
		{
			$status = "error";
			$message = "There was a problem with the database - please call your administrator";
			//$message = "MySQL error " . mysql_errno() . ": " . mysql_error();
		}
		else
		{
			$message = "New user created!";
			$to      =  $email;
			$subject =  "New Account Created";
			$emailmessage =  "Hello,\r\n\r\nYour account has been created!\r\n\r\nYour login information is:\r\n" .
					"username: " . $uname . "\r\npassword: " . $password . "\r\n\r\n" .
					"Please go here to login and change your password:\r\n" .
					$webaddress . "dhdlogin";
			$headers =  "From: admins@doghousediaries.com" . "\r\n" .
					"Reply-To: admins@doghousediaries.com" . "\r\n" .
					"X-Mailer: PHP/" . phpversion();
			mail($to, $subject, $emailmessage, $headers);

			$content = "";
			$userquery = mysql_query("SELECT * FROM dhdadmins ORDER BY ID ASC;");
			$content = "<table style='border-collapse:collapse;width:100%;'><tr class='headerrow'><td>username</td><td>first name</td><td>last name</td><td>email</td><td></td><td></td></tr>";
			$count = 0;
			while($row = mysql_fetch_assoc($userquery))
			{
				$count++;
				$altclass = ($count % 2) ? "" : "altrow";
				$btnhtml  = ($row['ID'] == $_SESSION['userid']) ? "<input type='button' class='updateuserbtn' value='Update' onclick='showUpdateUserForm(" . $row['ID'] . ")' />" : "";
				$passhtml = ($row['ID'] == $_SESSION['userid']) ? "<input type='button' class='passbtn' value='Change Password' onclick='showUpdatePasswordForm(" . $row['ID'] . ")' />" : "";
				$content .= "   <input type='hidden' id='unamehdn" . $row['ID'] . "' value='" . $row['dhduser'] . "' />
								<input type='hidden' id='firsthdn" . $row['ID'] . "' value='" . $row['dhdfirst'] . "' />
								<input type='hidden' id='lasthdn" . $row['ID'] . "' value='" . $row['dhdlast'] . "' />
								<input type='hidden' id='emailhdn" . $row['ID'] . "' value='" . $row['dhdemail'] . "' />
								<tr class='tablerow'" . $altclass . ">
									<td id='tduname" . $row['ID'] . "'>" . $row['dhduser'] . "</td>
									<td id='tdfname" . $row['ID'] . "'>" . $row['dhdfirst'] . "</td>
									<td id='tdlname" . $row['ID'] . "'>" . $row['dhdlast'] . "</td>
									<td id='tdemail" . $row['ID'] . "'>" . $row['dhdemail'] . "</td>
									<td>" . $btnhtml . "</td>
									<td>" . $passhtml . "</td></tr>";
			}
			$content .= "</table>";
		}

		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> $content
		);

		echo json_encode($result);
	}

	function updateUser($P)
	{
		foreach($P as $key => $val)
		{
			$val = urldecode($val);
			$val = mysql_real_escape_string($val);
			$P[$key] = $val;
		}

		$userid = $P['userid'];
		$uname  = $P['username'];
		$fname  = $P['firstname'];
		$lname  = $P['lastname'];
		$email  = $P['useremail'];

		$status  = "success";
		$message = "";
		$content = "";

		$update = mysql_query( "UPDATE dhdadmins
								SET dhduser='"  . $uname . "',
									dhdfirst='" . $fname . "',
									dhdlast='"  . $lname . "',
									dhdemail='" . $email . "'
								WHERE ID=" . $userid . ";");
		if(mysql_errno())
		{
			$status = "error";
			$message = "There was a problem with the database - please call your administrator";
			//$message = "MySQL error " . mysql_errno() . ": " . mysql_error();
		}
		else
		{
			$message = "Your information has been updated!";
			$_SESSION['username'] = $uname;
		}

		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> $content
		);

		echo json_encode($result);
	}

	function updatePassword($P)
	{
		foreach($P as $key => $val)
		{
			$val = urldecode($val);
			$val = mysql_real_escape_string($val);
			$P[$key] = $val;
		}

		$userid 	= $P['userid'];
		$currpass  	= $P['currpass'];
		$newpass  	= $P['newpass'];

		$status  = "success";
		$message = "";
		$content = "";

		$checkpass = mysql_fetch_assoc(mysql_query("SELECT ID FROM dhdadmins WHERE ID='" . $userid . "' AND dhdpass='" . md5($currpass) . "';"));

		if(isset($checkpass['ID']) && $checkpass['ID'] != "")
		{
			$update = mysql_query( "UPDATE dhdadmins
									SET dhdpass='"  . md5($newpass) . "'
									WHERE ID=" . $userid . ";");
			if(mysql_errno())
			{
				$status = "error";
				$message = "There was a problem with the database - please call your administrator";
				//$message = "MySQL error " . mysql_errno() . ": " . mysql_error();
			}
			else
			{
				$message = "Your password has been updated!";
			}
		}
		else
		{
			$status = "error";
			$message = "Please check your current password!";
		}

		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> $content
		);

		echo json_encode($result);
	}


	function getComicInfo($P)
	{
		$comicid = $P['comicid'];
		$comicid = urldecode($comicid);
		$comicid = mysql_real_escape_string($comicid);

		$comic = getComicMeta($comicid);
		$hover = getHover($comicid);

		$cpath = "dhdcomics/";
		$cname = "";
		$ctitle = "";
		$calttxt = "";
		$csubtxt = "";

		$name = "";
		$comfile = mysql_fetch_assoc(mysql_query("SELECT meta_value FROM postmeta WHERE post_id=" . $comic['ID'] . " AND meta_key='comic_file';"));

		if(isset($comfile['meta_value']) && $comfile['meta_value'] != "")
		{
			$ls = shell_exec('ls /var/www/html/dhdcomics | grep "' . $comfile['meta_value'] . '"');
			if($ls != "")
			{
				$name = $ls;
			}
		}

		if(!isset($comfile['meta_value']) || $comfile['meta_value'] == "" || $name == "")
		{
			$comdate = $comic['post_date'];
			$comdgmt = $comic['post_date_gmt'];
			$cdate = substr($comic['post_date'], 0, 10);
			$cdgmt = substr($comic['post_date_gmt'], 0, 10);
			$ls = shell_exec('ls /var/www/html/dhdcomics | grep ' . $cdate);
			if($ls != "")
			{
				$name = $ls;
			}
			else
			{
				$ls = shell_exec('ls /var/www/html/dhdcomics | grep ' . $cdgmt);
				$name = $ls;
			}
		}

		//$name = "dhdcomics/" . $name;
		$cname = $name;
		$cpath .= $name;
		$ctags = getTags($comicid);
		$comictags = isset($ctags['meta_value']) ? $ctags['meta_value'] : "";
		$calttxt = isset($hover['meta_value']) ? htmlentities($hover['meta_value'], ENT_QUOTES) : $comic['post_title'];
		$ctitle = isset($comic['post_title']) ? $comic['post_title'] : "";
		$csubtxt = isset($comic['post_content']) ? $comic['post_content'] : "";
		$poststt = $comic['post_status'];
		$postdte  = ($poststt == "publish" || $poststt == "inactive") ? "" : substr($comic['post_live_date'], 0, 10);

		$status = "success";
		$message = "";
		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> array(
						"title"	 => $ctitle,
						"path"	 => $cpath,
						"subtxt" => $csubtxt,
						"alttxt" => $calttxt,
						"name"	 => $cname,
						"tags"	 => $comictags,
						"poststat" => $poststt,
						"postdate" => $postdte
				)
		);

		echo json_encode($result);

		/*
		* == posts table ==
		* post_author = user id
		* post_content = subtext
		* post_title = comic title / page title
		* post_status = "publish"
		* post_name = post_title without puncuation or spaces, spaces replaced with hyphens
		* post_parent = 0
		* post_type = "post"
		* post_date = post_modified = TIMESTAMP
		* post_date_gmt = post_modified_gmt = TIMESTAMP
		* guid = http://thedoghousediaries.com/?p=NEW_COMIC_ID
		*
		* == postmeta table ==
		* post_id = ID of comic just uploaded
		* meta_key =>
		* 		comic_file = file name
		* 		comic_description = alt-text
		*/
	}

	function getComicTable($P)
	{
		$firstload = (isset($P['firstload']) && !empty($P['firstload']) && $P['firstload'] == 1) ? 1 : 0;

		$coms = mysql_query("SELECT ID, post_title, post_date, post_status, post_live_date
							FROM posts
							WHERE post_type='post' 
								AND (post_status='publish' OR post_status='pending' OR post_status='inactive') 
							ORDER BY post_date DESC;");
		$numrows = mysql_num_rows($coms);
		$comictablehtml = "";
		$count = 0;
		if($numrows > 0)
		{
			while($row = mysql_fetch_assoc($coms))
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
				$comictablehtml .= "<tr id='" . $row['ID'] . "' class='" . $altclass . "'" . $stattitl . ">
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
			$comictablehtml = "<tr class='mainrow'><td colspan='3'>No comics found!</td></tr>";
		}

		$status = "success";
		$message = "";
		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> $comictablehtml
		);

		if($firstload){
			return $comictablehtml;
		}
		else{
			echo json_encode($result);
		}
	}

	function updateAnnouncement($P)
	{
		$text = $P['text'];
		$text = urldecode($text);
		$text = mysql_real_escape_string($text);

		$aori = $P['active'];
		$aori = urldecode($aori);
		$aori = mysql_real_escape_string($aori);

		$aori = ($aori == "active") ? 1 : 0;

		$update_query = mysql_query("UPDATE dhdannouncements
									 SET announcement='" . $text . "', active='" . $aori . "'
									 WHERE ID=1;");
		$status = "success";
		$message = "Announcement updated successfully!";
		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> ""
		);

		echo json_encode($result);
	}

	function getAnnouncementForm($P)
	{
		$announce = mysql_fetch_assoc(mysql_query("SELECT * FROM dhdannouncements;"));
		$status = "success";
		$message = "";
		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> array(
						"text" 	 => $announce['announcement'],
						"active" => $announce['active']
					)
				);

		echo json_encode($result);
	}

	function getAnnouncementComic()
	{
		$announce = mysql_fetch_assoc(mysql_query("SELECT * FROM dhdannouncements;"));
		$status = "success";
		$message = "";
		$result = array(
				"status"	=> $status,
				"message"	=> $message,
				"content"	=> array(
						"text" 	 => $announce['announcement'],
						"active" => $announce['active']
				)
		);

		return $result;
	}
?>
