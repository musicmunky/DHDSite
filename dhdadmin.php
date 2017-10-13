<?php
	define('LIBRARY_CHECK',true);
	require 'php/library.php';

    session_save_path ("/var/www/doghousediaries/session_data/");

	if(isset($_GET['logout']))
	{
		session_destroy();
		$_SESSION = array();
		header("Location: dhdlogin");
		exit;
	}

	if(!isset($_SESSION))
	{
		session_name('dhdmaintenance');
		session_start();
	}

	$disclaimer = "";
	$html = "";
	if(!isset($_SESSION['username']) || !isset($_SESSION['userid']))
	{
		header('Location: dhdlogin');
	}
	else
	{
		/*
		$disclaimer = "<div style='width:100%;font-style:italic;font-size:10px;padding-bottom:10px;color:red;'>
							THIS PAGE IS CURRENTLY UNDER DEVELOPMENT - YOU MAY NOW PROCEED TO TRY AND BREAK IT
							BUT PLEASE, IF YOU DO FIND BUGS, LET TIM KNOW.  HE KINDA CARES ABOUT THE SITE A LITTE
							AND MIGHT ACTUALLY START CRYING IF YOU BREAK IT AND DON'T TELL HIM.  NO ONE WANTS THAT,
							DO THEY?  AND RAY, BEFORE YOU SAY ANYTHING, THE ANSWER IS NO.  YOU DO NOT WANT THAT.
						</div>";
		*/
		$disclaimer = "";

        $my_link = connectToDb();
		$userquery = mysqli_query($my_link, "SELECT * FROM dhdadmins ORDER BY ID ASC;");
		$html = "<table style='border-collapse:collapse;width:100%;'>
					<tr class='headerrow'>
						<td>username</td>
						<td>first name</td>
						<td>last name</td><td>email</td>
						<td></td><td></td>
					</tr>";
		$count = 0;
		while($row = mysqli_fetch_assoc($userquery))
		{
			$count++;
			$altclass = ($count % 2) ? "" : "altrow";
			$btnhtml  = ($row['ID'] == $_SESSION['userid']) ? "<input type='button' class='updateuserbtn' value='Update' onclick='showUpdateUserForm(" . $row['ID'] . ")' />" : "";
			$passhtml = ($row['ID'] == $_SESSION['userid']) ? "<input type='button' class='passbtn' value='Change Password' onclick='showUpdatePasswordForm(" . $row['ID'] . ")' />" : "";
			$html .= "  <input type='hidden' id='unamehdn" . $row['ID'] . "' value='" . $row['dhduser'] . "' />
						<input type='hidden' id='firsthdn" . $row['ID'] . "' value='" . $row['dhdfirst'] . "' />
						<input type='hidden' id='lasthdn" . $row['ID'] . "' value='" . $row['dhdlast'] . "' />
						<input type='hidden' id='emailhdn" . $row['ID'] . "' value='" . $row['dhdemail'] . "' />
						<tr class='tablerow " . $altclass . "'>
							<td id='tduname" . $row['ID'] . "'>" . $row['dhduser'] . "</td>
							<td id='tdfname" . $row['ID'] . "'>" . $row['dhdfirst'] . "</td>
							<td id='tdlname" . $row['ID'] . "'>" . $row['dhdlast'] . "</td>
							<td id='tdemail" . $row['ID'] . "'>" . $row['dhdemail'] . "</td>
							<td>" . $btnhtml . "</td>
							<td>" . $passhtml . "</td></tr>";
		}
		$html .= "</table>";
		//currently not loading until tab is clicked...faster initial load that way...
		//$comictablehtml = getComicTable(array("firstload" => 1, "method" => 'getComicTable', "libcheck" => true));
        mysqli_close($my_link);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title>DHD Admin Page</title>
		<link rel="stylesheet" href="css/jquery-ui-1.10.4.custom.min.css" type="text/css" media="screen" charset="utf-8"></link>
		<link rel="stylesheet" href="css/dhdadmin.css" type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery-ui-1.10.4.custom.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/dhd.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/dhdadmin.js"></script>
	</head>
	<body>
		<div class="header">
			DHD Administration
			<span style="cursor:pointer;float:right;font-family:Monaco,Consolas,'Lucida Console',monospace;font-size:16px;margin-top:12px;">
				<a id="logout" name="logout" style="text-decoration:none;" href="dhdadmin.php?logout">logout</a>
			</span>
		</div>
		<div style="margin-left:50px;margin-bottom: 50px;width:1000px;float:left;">
			<div id="admintabs">
				<ul>
					<li><a href="#newtab">Upload New</a></li>
					<li><a href="#edittab">Edit Existing</a></li>
					<li><a href="#announcementstab">Announcements</a></li>
					<li><a href="#usertab">Add/Update Users</a></li>
				</ul>
				<div id="newtab">
					<div style="width:100%;margin-top:20px;">
						<?php echo $disclaimer; ?>
						<form action="php/comupload.php" method="post" id="uploadcomicform" target="comupload_target" enctype="multipart/form-data" onsubmit="return validateUpload();">
							<div class="fielddivs">
								<div class="labeldivs">File:<span style="color:red;">*</span></div>
								<input name="cfile" type="file" id="cfile" class="fieldinputs" accept="image/*" value="" onchange="fillEditFileName('');" />
							</div>
							<div class="fielddivs" id="loadingdiv" name="loadingdiv" style="text-align:left;visibility:hidden;display:none;">
								<p id="upload_process" style="margin-left:280px;text-align:center;width:223px;">
									Uploading file...<br/>
									<img src="logos/loader.gif" /><br/>
								</p>
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Filename:<span style="color:red;">*</span></div>
								<input name="cname" type="text" id="cname" class="fieldinputs" value="" title="Defaults to the actual file name unless changed by the user" />
								<span style="color:#FF0000; font-family:courier new; font-size:12px; font-style:italic; margin-left:10px;">
									You must include the file extension!
								</span>
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Title:<span style="color:red;">*</span></div>
								<input name="ctitle" type="text" id="ctitle" class="fieldinputs" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Alt-Text:<span style="color:red;">*</span></div>
								<input name="calt" type="text" id="calt" class="fieldinputs" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Sub-Text:<span style="color:red;">*</span></div>
								<textarea name="csub" id="csub" style="width:500px;height:100px;resize:none;"></textarea>
								<div style="float: right; width: 280px; text-align: left">
									<span style="color:#FF0000; font-family:courier new; font-size:12px; font-style:italic;">
										Feel free to include any HTML you would like in this space!
									</span>
								</div>
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Tags: </div>
								<input name="ctags" type="text" id="ctags" class="fieldinputs" value="<?php echo isset($_POST['ctags']) ? $_POST['ctags'] : ""; ?>" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Go Live:<span style="color:red;">*</span></div>
								<input type="radio" name="golive" style="margin-top: 7px;" onchange="showHideDatePicker('')" value="immediately">Immediately&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="golive" style="margin-top: 7px;" onchange="showHideDatePicker('')" value="future">Future date
							</div>
							<div class="fielddivs" id="postdatediv" style="visibility:hidden;display:none;">
								<div class="labeldivs">Post Date:<span style="color:red;">*</span></div>
								<input type="text" id="golivedate" name="golivedate" />
							</div>
							<div class="fielddivs" style="text-align: center;">
								<input type="submit" class="uploadbtn" id="comicsubmit" name="comicsubmit" value="Upload" style="margin-right:40px;" />
								<input type="button" class="uploadbtn" value="Clear Form" onclick="clearUploadForm(0);" />
							</div>
							<iframe id="comupload_target" name="comupload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
						</form>
					</div>
				</div>
				<div id="edittab">
					<div style="width:100%;margin-top:20px;">
						<?php echo $disclaimer; ?>
							<div class="fielddivs">
								<div class="labeldivs">Filter by Title: </div>
								<input type="text" id="comicquery" name="comicquery" onkeyup="comicQuery();" class="fieldinputs" value="" />
							</div>
							<div class="scrollableContainer">
								<div class="scrollingArea">
									<table class="allcomics scrollable">
										<thead>
											<tr>
												<th class="comid"><div>ID</div></th>
												<th class="comtitle"><div>Title</div></th>
												<th class="comdate"><div>Post Date</div></th>
												<th class="comstat"><div>Post Status</div></th>
											</tr>
										</thead>
										<tbody id="comictablebody" name="comictablebody">
											<tr class='mainrow'>
												<td colspan='3' style="background-image:url(logos/loader.gif);height:30px;background-repeat:no-repeat;background-position:center;"></td>
											</tr>
											<?php //echo $comichtmltable; ?>
										</tbody>
									</table>
								</div>
							</div>
						<form action="php/comupdate.php" method="post" id="updatecomicform" target="comupdate_target" enctype="multipart/form-data" onsubmit="return validateUpdate();">
							<div class="fielddivs">
								<div class="labeldivs">Current Comic: </div>
								<input name="edit_curr" type="text" id="edit_curr" class="fieldinputs" value="" disabled />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">New Comic: </div>
								<input name="edit_cfile" type="file" id="edit_cfile" class="fieldinputs" accept="image/*" value="" onchange="fillEditFileName('edit_');" />
							</div>
							<div class="fielddivs" id="updatingdiv" name="updatingdiv" style="text-align:left;visibility:hidden;display:none;">
								<p id="upload_process" style="margin-left:280px;text-align:center;width:223px;">
									Updating comic...<br/>
									<img src="logos/loader.gif" /><br/>
								</p>
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Filename:<span style="color:red;">*</span></div>
								<input name="edit_cname" type="text" id="edit_cname" class="fieldinputs" value="" />
								<span style="color:#FF0000; font-family:courier new; font-size:12px; font-style:italic; margin-left:10px;">
									You must include the file extension!
								</span>
								<input name="h_edit_cname" type="hidden" id="h_edit_cname" value="" />
								<input name="edit_comicid" id="edit_comicid" value="" type="hidden" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Title:<span style="color:red;">*</span></div>
								<input name="edit_ctitle" type="text" id="edit_ctitle" class="fieldinputs" value="" />
								<input name="h_edit_ctitle" type="hidden" id="h_edit_ctitle" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Alt-Text:<span style="color:red;">*</span></div>
								<input name="edit_calt" type="text" id="edit_calt" class="fieldinputs" value="" />
								<input name="h_edit_calt" type="hidden" id="h_edit_calt" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Sub-Text:<span style="color:red;">*</span></div>
								<textarea name="edit_csub" id="edit_csub" style="width:500px;height:100px;resize:none;"></textarea>
								<div style="float: right; width: 280px; text-align: left">
									<span style="color:#FF0000; font-family:courier new; font-size:12px; font-style:italic;">
										Feel free to include any HTML you would like in this space!
									</span>
								</div>
								<input name="h_edit_csub" type="hidden" id="h_edit_csub" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Tags: </div>
								<input name="edit_ctags" type="text" id="edit_ctags" class="fieldinputs" value="" />
								<input name="h_edit_ctags" type="hidden" id="h_edit_ctags" value="" />
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Comic Status:<span style="color:red;">*</span></div>
								<input type="radio" name="edit_active" style="margin-top: 7px;" value="active" checked>Active&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="edit_active" style="margin-top: 7px;" value="inactive">Inactive
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Go Live:<span style="color:red;">*</span></div>
								<input type="radio" name="edit_golive" style="margin-top: 7px;" onchange="showHideDatePicker('edit_')" value="immediately">Immediately&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="edit_golive" style="margin-top: 7px;" onchange="showHideDatePicker('edit_')" value="future">Future date
							</div>
							<div class="fielddivs" id="edit_postdatediv" style="visibility:hidden;display:none;">
								<div class="labeldivs">Post Date:<span style="color:red;">*</span></div>
								<input type="text" id="edit_golivedate" name="edit_golivedate" />
							</div>
							<div class="fielddivs" style="text-align: center;">
								<input type="submit" class="uploadbtn" id="comicupdate" name="comicupdate" value="Update" style="margin-right:40px;" />
								<input type="button" class="uploadbtn" value="Clear Form" onclick="clearUpdateForm(0);" />
							</div>
							<iframe id="comupdate_target" name="comupdate_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
						</form>
					</div>
				</div>
				<div id="announcementstab">
					<div style="width:100%;margin-top:20px;">
						<?php echo $disclaimer; ?>
						<form id="announcementform">
							<div class="fielddivs">
								<div class="labeldivs">Content:<span style="color:red;">*</span></div>
								<textarea name="announcetext" id="announcetext" style="width:500px;height:100px;resize:none;"></textarea>
								<div style="float: right; width: 280px; text-align: left">
									<span style="color:#FF0000; font-family:courier new; font-size:12px; font-style:italic;">
										Feel free to include any HTML you would like in this space!
									</span>
								</div>
							</div>
							<div class="fielddivs">
								<div class="labeldivs">Status:<span style="color:red;">*</span></div>
								<input type="radio" name="edit_active_announce" style="margin-top: 7px;" value="active" checked>Active&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="edit_active_announce" style="margin-top: 7px;" value="inactive">Inactive
							</div>
							<div class="fielddivs" style="text-align: center;">
								<input type="button" class="uploadbtn" value="Update" onclick="updateAnnouncement();" style="margin-right:40px;" />
								<input type="button" class="uploadbtn" value="Clear Form" onclick="clearAnnounceForm(0);" />
							</div>
						</form>
					</div>
				</div>
				<div id="usertab">
					<div style="width:100%;margin-top:20px;">
						<div id="tablediv" style="width:100%;padding-top:20px;">
							<?php echo $html; ?>
						</div>
						<div class="fielddivs" style="text-align: center;padding-top:20px;">
							<input type="button" id="adduserbtn" name="adduserbtn" class="uploadbtn" value="Add User" onclick="showCreateUserForm();" />
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="userform">
			<p class="validateTips"><i>All fields are required.</i></p>
			<form>
				<fieldset>
					<input type="hidden" id="userid" name="userid" value="" />
					<div class="fielddivs" style="padding-top:15px;">
						<div class="userdivs">Username: </div>
						<input name="uname" type="text" id="uname" class="userinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="userdivs">First Name: </div>
						<input name="ufname" type="text" id="ufname" class="userinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="userdivs">Last Name: </div>
						<input name="ulname" type="text" id="ulname" class="userinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="userdivs">Email: </div>
						<input name="uemail" type="text" id="uemail" class="userinputs" value="" />
					</div>
					<div class="fielddivs" style="text-align: center;">
						<input type="button" id="createuserbtn" class="createuserbtn" value="Create User" onclick="createUser()" />
						<input type="button" id="updateuserbtn" class="createuserbtn" value="Update User" onclick="updateUser()" />
						<input type="button" class="createuserbtn" value="Cancel" onclick="hideUserForm()" />
					</div>
				</fieldset>
			</form>
		</div>

		<div id="passwordform" title="Update Your Password">
			<p class="validateTips"><i>All fields are required.</i></p>
			<form>
				<fieldset>
					<div class="fielddivs" style="padding-top:15px;">
						<div class="passworddivs">Current Password: </div>
						<input name="currpass" type="password" id="currpass" class="passwordinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="passworddivs">New Password: </div>
						<input name="newpass" type="password" id="newpass" class="passwordinputs" value="" />
					</div>
					<div class="fielddivs">
						<div class="passworddivs">Repeat New Password: </div>
						<input name="repnewpass" type="password" id="repnewpass" class="passwordinputs" value="" />
					</div>
					<div class="fielddivs" style="text-align: center;">
						<input type="button" id="passwordbtn" class="createuserbtn" value="Update Password" onclick="updatePassword()" />
						<input type="button" class="createuserbtn" value="Cancel" onclick="hidePasswordForm()" />
					</div>
				</fieldset>
			</form>
		</div>
	</body>
</html>