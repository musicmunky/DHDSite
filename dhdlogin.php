<?php 
	define('LIBRARY_CHECK',true);
	require 'php/library.php';
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

	if(!empty($_POST))
	{
		$user = isset($_POST['txtusername']) ? $_POST['txtusername'] : "";
		$pass = isset($_POST['txtpassword']) ? $_POST['txtpassword'] : "";
	
		$user = urldecode($user);
		$pass = urldecode($pass);
		$user = mysql_real_escape_string($user);
		$pass = mysql_real_escape_string($pass);
		$hashedpassword = md5($pass);

		$checkpass = mysql_fetch_assoc(mysql_query("SELECT ID, dhduser
													FROM dhdadmins
													WHERE dhduser='" . $user . "'
													AND dhdpass='" . $hashedpassword . "';"));
		
		if(isset($checkpass['ID']) && $checkpass['ID'] != "")
		{
			ini_set('session.gc_maxlifetime', 24*60*60);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',100);
			if(!isset($_SESSION))
			{
				session_name('dhdmaintenance');
				session_start();
			}
			
			$_SESSION['userid'] = $checkpass['ID'];
			$_SESSION['username'] = $checkpass['dhduser'];
		}
		else
		{
			echo ("<script language='javascript'>
					window.alert('Incorrect Username/Password combo!');
					document.location.href = 'dhdlogin';
					</script>");
			//header('Location: dhdlogin');
		}
	}

	if(isset($_SESSION['username']) && isset($_SESSION['userid']))
	{
		header('Location: dhdadmin');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>DHD Login Page</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/dhd.js"></script>
		<script type="text/javascript">
			function validateForm()
			{
				var user = document.getElementById("txtusername").value;
				var pass = document.getElementById("txtpassword").value;
				if(user.match(/^\s*$/))
				{
					alert("Please enter a username!");
					return false;
				}
				if(pass.match(/^\s*$/))
				{
					alert("Please enter a password!");
					return false;
				}
				return true;
			}
		</script>
	</head>
	<body>
		<div class="mainloginclass">
		   <form id='loginform' action='dhdlogin.php' method='post' accept-charset='UTF-8' class="login" onsubmit="return validateForm();">
				<div class="inputdivs">
					<input class="logintxt" type="text" id="txtusername" name="txtusername" placeholder="username" />
				</div>
				<div class="inputdivs">
					<input class="logintxt" type="password" id="txtpassword" name="txtpassword" placeholder="password" />
				</div>
				<div class="inputdivs">
					<input class="loginbtn" type="submit" value="login" />
				</div>
			</div>
		 </div>
	</body>
</html>