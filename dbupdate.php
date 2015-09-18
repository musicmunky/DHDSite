<?php 
	define('INCLUDE_CHECK',true);
	require 'tim/php/connect.php';

	//$d = strtotime("2014-05-12");
	//$golivedate  = date('Y-m-d H:i:s', $d);
	
	//echo "GOLIVE IS: " . $golivedate;
	
	//$np = "Password99";
	//$updatepass = mysql_query("UPDATE dhdadmins SET dhdpass='" . md5($np) . "' WHERE ID=6;");
/*
	$password = md5("FusionTech");
	$username = "pmeckel";
	$first = "Phil";
	$last = "Meckel";
	$email = "philmeckel@gmail.com";
	$newuser = mysql_query("INSERT INTO eventadmin (USER, FIRST, LAST, EMAIL, PASSWORD) 
							VALUES ('" . $username . "', '" . $first . "', '" . $last . "', '" . $email . "', '" . $password . "');");
*/
/*	
	$password = md5("Password99");
	$username = "ranvari";
	$first = "Rafaan";
	$last = "Anvari";
	$email = "ranvari@gmail.com";

	$currpass = "Password99";
	$currpass = urldecode($currpass);
	$currpass = mysql_real_escape_string($currpass);
	$hcpass = md5($currpass);
	$userid = 1;
	$checkpass = mysql_fetch_assoc(mysql_query("SELECT ID FROM dhdadmins WHERE ID='" . $userid . "' AND dhdpass='" . md5($currpass) . "';"));
	echo "ID IS: " . $checkpass['ID'] . "  encoded pass is: " . $currpass . "  hashed pass is: " . $hcpass;

	
*/
	
	
	//$newuser = mysql_query("INSERT INTO dhdadmins (dhduser, dhdfirst, dhdlast, dhdemail, dhdpass) VALUES ('" . $username . "', '" . $first . "', '" . $last . "', '" . $email . "', '" . $password . "');");

/*	
	
	$useridandpass = mysql_query("SELECT ID, PASSWORD FROM USER WHERE ID != 493 AND ID != 495 ORDER BY ID");
	
	$hashedarray = array();
	
	while($row = mysql_fetch_assoc($useridandpass))
	{
		$hashedarray[$row['ID']] = md5($row['PASSWORD']);
	}
	
	foreach($hashedarray as $key => $val)
	{
		echo "ID IS: " . $key . " HASHED PASSWORD IS: " . $val . "<br />";
		//mysql_query("UPDATE USER SET PASSWORD='" . $val . "' WHERE ID='" . $key . "';");
	}
	



/*
	require 'php/DBI.php';
	
	$DBI = new DBI();
	
	$userid = 1;
	
	$t = strtotime('tomorrow') - time();
	echo "T IS: " . $t;
	
*/
	
	/*
	date_default_timezone_set('America/New_York');

	$currtime = time();
	$microtime = microtime(true);
	list($big, $small) = explode(".", $microtime);
	$newtime = $big . $small;
	
	//$microfloat = $_SERVER["REQUEST_TIME_FLOAT"];
	
	echo "CURRENT: " . $currtime . "<br />";
	echo "MICRO: " . $microtime . "<br />";
	echo "MICROFLOAT: " . $newtime . "<br />";
			  //  h  m  s  mh  dy  year 
	//echo mktime(0, 0, 0, 12, 32, 2012);
	
	//$sitelist = mysql_fetch_assoc(mysql_query("SELECT USER_ID FROM USER_USER_TYPE WHERE USER_TYPE_ID=3 AND USER_ID != 1;"));
	
	//echo "ID IS: " . $sitelist['USER_ID'];
	*/
	/*
	int mktime ([ int $hour = date("H") 
				[ int $minute = date("i") 
				[ int $second = date("s") 
				[ int $month = date("n") 
				[ int $day = date("j") 
				[ int $year = date("Y") 
				[ int $is_dst = -1 ]]]]]]] )
	*/

	//insert example
	/*
	$ins = $DBI->insert(array(
				"tables"	=> "TEST_TABLE",
				"fields"	=> "NAME, AGE, EYE_COLOR, SHOE_SIZE, WAIST, HEIGHT, WEIGHT, DOB",
				"values"	=> "'Foo Bar1', 34, 'brown', 11, 32, 5.75, 200, '1979-03-15'",
			));
	
	echo "RESULT IS: " . $ins['result'] . "<br />";
	if(isset($ins['result']) && $ins['result'] == "success")
	{
		echo "NEW ID IS: " . $ins['ID'] . "<br />";
	}
	else
	{
		echo "NUMBER OF ERRORS: " . $ins['errcount'] . "<br />";
		echo "ERROR IS: " . $ins['error'] . "<br />";		
	}
	*/
	
	//delete example
	/*
	$del = $DBI->delete(array(
			"tables"	=> "TEST_TABLE",
			"where"		=> "ID=10",
	));
	
	echo "RESULT IS: " . $del['result'] . "<br />";
	if(isset($del['result']) && $del['result'] == "success")
	{
		echo "DELETE SUCCESSFUL<br />";
	}
	else
	{
		echo "NUMBER OF ERRORS: " . $del['errcount'] . "<br />";
		echo "ERROR IS: " . $del['error'] . "<br />";
	}
	*/
	//2D array example
	/*
	$r1 = $DBI->select(array(
					'fields'	=> "USER.ID AS USERID, FIRST_NAME, LAST_NAME, EMAIL, USER_USER_TYPE.USER_TYPE_ID AS USERTYPEID",
					'tables'	=> "USER, USER_USER_TYPE, USER_TYPE",
					'where'	 	=> "USER.ACTIVE='1' 
									AND USER.ID=USER_USER_TYPE.USER_ID
										AND USER_USER_TYPE.USER_TYPE_ID=USER_TYPE.TYPE
											AND USER.ID != '" . $userid . "'",
					'orderby'	=> "USER.ID",
				));

	 for($i = 0; $i < count($r1); $i++)
	 {
	$f1 = $r1[$i]['USERID'];
	$f2 = $r1[$i]['FIRST_NAME'];
	$f3 = $r1[$i]['LAST_NAME'];
	$f4 = $r1[$i]['EMAIL'];
	echo "RESULT IS: " . $f1 . " " . $f2 . " " . $f3 . " " . $f4 . "<br />";
	}
	
	echo "<br /><br />";
	
	//single array example
	$r2 = $DBI->select(array(
					'fields'	=> "ID",
					'tables'	=> "USER",
					'singlearray' => 1,
				));
	
	for($i = 0; $i < count($r2); $i++) 
	{
		$f1 = $r2[$i];
		echo "RESULT IS: " . $f1 . "<br />";
	}
	*/

	/*
	$t1 = $r[0][0];
	echo "T IS: <br />";
	echo var_dump($t1);
	echo "<br /><br /><br /><br />";
	
	echo "R IS: <br />";
	echo var_dump($r);
	*/
	//echo "COUNT IS: " . $r . " <br /> " . $test;

	
	
	
	/*
	$companyid = 163;
	$checkpoc = mysql_fetch_assoc(mysql_query("SELECT NAME, POC_PRIMARY
												FROM COMPANY
												WHERE ID='" . $companyid . "';"));
	$companyname = $checkpoc['NAME'];
	
	$getjobs = mysql_query("SELECT COMPANY.ID AS COMPANYID, COMPANY.NAME AS COMPANYNAME, POC_PRIMARY, FIRST_NAME, LAST_NAME, EMAIL, ALL_JOBS.ID AS JOBID, JOB_AVAILABLE.ID AS ALLJOBID, JOB_AVAILABLE.COMPANY_ID, JOB_ID, JOB_AVAILABLE.DESCRIPTION, EXPERIENCE, SALARY, JOB_AVAILABLE.CLEARANCE, JOB 
							FROM COMPANY, JOB_AVAILABLE, ALL_JOBS, USER 
							WHERE JOB_AVAILABLE.COMPANY_ID=COMPANY.ID 
									AND JOB_ID=ALL_JOBS.ID 
										AND USER.ID=POC_PRIMARY 
											AND JOB_AVAILABLE.ACTIVE='1' ORDER BY COMPANY.NAME;");
	
	$html = "<table>";
	while($row = mysql_fetch_assoc($getjobs))
	{
		$html .= "<tr><td>" . $row['COMPANYID'] . "</td>
					  <td>" . $row['COMPANYNAME'] . "</td>
					  <td>" . $row['ALLJOBID'] . "</td>
					  <td>" . $row['JOB'] . "</td>
					  <td>" . $row['JOBID'] . "</td>
					  <td>" . $row['SALARY'] . "</td>
					  <td>" . ucfirst($row['CLEARANCE']) . "</td>
					  <td>" . $row['FIRST_NAME'] . "</td>
					  <td>" . $row['LAST_NAME'] . "</td>
					  <td>" . $row['EMAIL'] . "</td>
					  </tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	*/
	
	/*
	
	$file = "php/careers.txt";
	
	$jobs = split("\r\n", file_get_contents($file));
	$c = 0;
	for($i = 0; $i < count($jobs); $i++)
	{
		$jobs[$i] = mysql_real_escape_string($jobs[$i]);
		//echo $jobs[$i] . "<br />";
		$results = mysql_query("INSERT INTO ALL_JOBS (JOB) VALUES ('" . $jobs[$i] . "');");
		$c++;
	}
	
	echo "INSERTED " . $c . " RECORDS!";
*/
	//$states = array("AK","AL","AR","AZ","CA","CO","CT","DC","DE","FL","GA","HI","IA","ID","IL","IN","KS","KY","LA","MA","MD","ME","MI","MN","MO","MS","MT","NC","ND","NE","NH","NJ","NM","NV","NY","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VA","VT","WA","WI","WV","WY");
	//foreach ($states as $state) {
	//	$results = mysql_query("INSERT INTO STATES (STATE) VALUES ('" . $state . "');");
	//}
	/*$countries = array("United States","Canada","Afghanistan","Aland Islands","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia","Botswana","Brazil","Brunei","Bulgaria","Burundi","Cambodia","Cameroon","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Colombia","Comoros","Congo","Cook Islands","Costa Rica","Cote d'Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","North Korea","Kuwait","Kyrgyzstan","Lao","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macao","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russian Federation","Rwanda","Saint Barthelemy","Saint Helena","Saint Kitts and Nevis","Saint Lucia","Saint Martin","Saint Pierre and Miquelon","Saint Vincent","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia","South Korea","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syrian Arab Republic","Taiwan","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Venezuela","Viet Nam","Virgin Islands, British","Virgin Islands, U.S.","Wallis and Futuna","Western Sahara","Yemen","Zambia","Zimbabwe");
	foreach ($countries as $country) {
		$results = mysql_query("INSERT INTO COUNTRIES (COUNTRY) VALUES ('" . $country . "');");
	}*/
	/*$count = 0;
	foreach (glob("img/userimages/user_1_avatar.*") as $filename) 
	{
		$count++;
		echo "$filename size " . filesize($filename) . "\n";
	}
	if($count)
	{
		echo "<script>alert('TESTING');</script>";
	}
	else
	{
		echo "<script>alert('NOPE');</script>";
	}*/
	
	/***********************************************************/
	//CODE FOR UPLOADING RESUMES TO DATABASE: DO NOT REMOVE!!!!!
	/***********************************************************/
	/*$allowedExts = array("PDF", "pdf");
	$extension = end(explode(".", $_FILES["resumename"]["name"]));
	if (($_FILES["resumename"]["type"] == "application/pdf") && in_array($extension, $allowedExts))
	{
		if ($_FILES["resumename"]["error"] > 0)
		{
			echo "Error: " . $_FILES["resumename"]["error"] . "<br />";
		}
		else
		{
			$resexist = mysql_fetch_assoc(mysql_query("SELECT ID, USER_ID
					FROM USER_RESUME
					WHERE USER_ID='" . $userid . "';"));
			$tmpName  = $_FILES['resumename']['tmp_name'];
			$fp     = fopen($tmpName, 'r');
			$data 	= fread($fp, filesize($tmpName));
			$data 	= addslashes($data);
			fclose($fp);
	
			$today = getdate();
			$year = $today['year'];
			$mnth = sprintf("%02s", $today['mon']);
			$day = sprintf("%02s", $today['mday']);
			$datestamp = $year . "-" . $mnth . "-" . $day;
	
			$notification = "";
			if($resexist['USER_ID'])
			{
				$results = mysql_query("UPDATE USER_RESUME
						SET RESUME='" . $data . "',
						DATE_UPDATED='" . $datestamp . "'
						WHERE USER_ID='" . $userid . "' AND ID='" . $resexist['ID'] . "';");
				if(mysql_errno())
				{
					$notification = "MySQL error " . mysql_errno() . ": " . mysql_error();
				}
				else
				{
					$notification = "Your resume has been updated!";
				}
				$log->logInfo("HERE IS NOTIFICATION: " . $notification);
			}
			else
			{
				$results = mysql_query("INSERT INTO USER_RESUME (USER_ID, RESUME, DATE_ADDED) VALUES ('" . $userid . "', '" . $data . "', '" . $datestamp . "');");
				$notification = "Your resume has been uploaded!";
			}
	
			echo ("<script language='javascript'>
					window.alert('" . $notification . "');
					window.location.href='edit_profile.php';
					</script>");
		}
	}
	else
	{
		echo ("<script language='javascript'>
				window.alert('There was a problem uploading your resume - please try again');
				window.location.href='edit_profile.php';
				</script>");
	}*/
	
	/*foreach (glob("*.txt") as $filename) {
		echo "$filename size " . filesize($filename) . "\n";
	}*/
	
	
	/*$sm_form_fields = array(
	 "facebook" 		=> $_POST['facebook'],
			"twitter" 		=> $_POST['twitter'],
			"googleplus" 	=> $_POST['googleplus'],
			"linkedin" 		=> $_POST['linkedin'],
			"blog" 			=> $_POST['blog'],
			"skype" 		=> $_POST['skype'],
	);*/
	/*$sm_type_array = array(
	 "facebook" 		=> '1',
			"twitter" 		=> '2',
			"googleplus" 	=> '3',
			"linkedin" 		=> '4',
			"blog" 			=> '5',
			"skype" 		=> '6',
	);*/

	
	/*$filename = "img/avatars/missing.jpg";
	
	if(file_exists($filename))
	{
		echo ("<script language='javascript'>
				window.alert('THE FILE EXISTS!');
				</script>");
	}
	else
	{
		echo ("<script language='javascript'>
				window.alert('THE FILE DOES NOT EXIST!');
				</script>");
	}*/
/*	
	$compinfo = mysql_query("SELECT ID, NAME FROM COMPANY;");
	//$userinfo = 
	
	while($row = mysql_fetch_assoc($compinfo))
	{
		$id = $row['ID'];
		$userinfo = mysql_fetch_assoc(mysql_query("SELECT USER_ID 
													FROM USER_COMPANY
													WHERE COMPANY_ID='" . $id . "';"));
		
		
		$userresult = mysql_query("UPDATE COMPANY
								   SET POC_PRIMARY='" . $userinfo['USER_ID'] . "'
								   WHERE ID='" . $id . "';");
	}
*/
	
	/*
	 $allowedExts = array("JPG", "JPEG", "GIF", "PNG", "jpg", "jpeg", "gif", "png");
	$extension = end(explode(".", $_FILES["filename"]["name"]));
	if ((($_FILES["filename"]["type"] == "image/gif") || ($_FILES["filename"]["type"] == "image/jpeg") ||
			($_FILES["filename"]["type"] == "image/png") || ($_FILES["filename"]["type"] == "image/pjpeg")) &&
			($_FILES["filename"]["size"] < 500000) && in_array($extension, $allowedExts))
	{
	if ($_FILES["filename"]["error"] > 0)
	{
	echo "Error: " . $_FILES["filename"]["error"] . "<br />";
	}
	else
	{
	$tmpName  = $_FILES['filename']['tmp_name'];
	$fp     = fopen($tmpName, 'r');
	$data 	= fread($fp, filesize($tmpName));
	$data 	= addslashes($data);
	fclose($fp);
		
	$userid = 1;
		
	$checkexist = mysql_fetch_assoc(mysql_query("SELECT ID, USER_ID FROM USER_PICTURE WHERE USER_ID='" . $userid . "'"));
		
	if($checkexist['USER_ID'])
	{
	$results = mysql_query("UPDATE USER_PICTURE
			SET PICTURE='" . $data . "',
			PICTURE_NAME='" . $_FILES["filename"]["name"] . "',
			PICTURE_TYPE='" . $_FILES["filename"]["type"] . "',
			PICTURE_SIZE='" . $_FILES["filename"]["size"] . "'
			WHERE USER_ID='" . $userid . "' AND ID='" . $checkexist['ID'] . "';");
	}
	else
	{
	$results = mysql_query("INSERT INTO USER_PICTURE (USER_ID, PICTURE) VALUES ('1', '" . $data . "')");
	}
	echo ("<script language='javascript'>
			window.alert('Your picture has been uploaded!');
			window.location.href='edit_profile.php';
			</script>");
	//echo "Thank you, your file has been uploaded: " . $results;
	}
	}
	else
	{
	echo ("<script language='javascript'>
			window.alert('There was a problem uploading your image - please try again');
			window.location.href='edit_profile.php';
			</script>");
	}*/
	
	
	
?>