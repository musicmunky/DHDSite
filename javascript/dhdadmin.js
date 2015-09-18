//start with the jQuery stuff...
$(function() {

	$( "#admintabs" ).tabs({
		activate: function(event,ui){
			if(ui.newTab.index() == 1)
			{
				$.ajax({
					type: "POST",
					url: "php/library.php",
					data: { firstload: 	0,
							method:  	'getComicTable', 
							libcheck: 	true},
					success: function(result){
						var response = JSON.parse(result);
						if(response['status'] == "success")
						{
							document.getElementById("comictablebody").innerHTML = response['content'];
						}
						return false;
					},
					error: function(){
						methodFail();
					}
				});
				return false;
			}
			if(ui.newTab.index() == 2)
			{
				$.ajax({
					type: "POST",
					url: "php/library.php",
					data: { method:  	'getAnnouncementForm', 
							libcheck: 	true},
					success: function(result){
						var response = JSON.parse(result);
						if(response['status'] == "success")
						{
							document.getElementById("announcetext").value = response['content']['text'];
							var active = document.getElementsByName("edit_active_announce");
							if(response['content']['active'] == 1)
							{
								active[0].checked = true;
							}
							else
							{
								active[1].checked = true;
							}
						}
						return false;
					},
					error: function(){
						methodFail();
					}
				});
				return false;
			}
		}
	});

	$( "#userform" ).dialog({
		autoOpen: false,
		height: 360,
		width: 550,
		modal: true,
	});

	$( "#passwordform" ).dialog({
		autoOpen: false,
		height: 310,
		width: 550,
		modal: true,
	});

	$( "#golivedate" ).datepicker({ 
						minDate: 0, 
						maxDate: "+1M",
						dateFormat: "yy-mm-dd" 
	});

	$( "#edit_golivedate" ).datepicker({ 
						minDate: 0, 
						maxDate: "+1M",
						dateFormat: "yy-mm-dd" 
		});
});

//begin standard functions
function showCreateUserForm()
{
	clearUserForm();
	document.getElementById("updateuserbtn").style.display = "none";
	document.getElementById("updateuserbtn").style.visibility = "hidden";
	document.getElementById("createuserbtn").style.display = "inline";
	document.getElementById("createuserbtn").style.visibility = "visible";
	$( "#userform" ).dialog( 'option', 'title', 'Create New User' );
	$( "#userform" ).dialog( "open" );
}

function showUpdateUserForm(i)
{
	var id = "";
	if(i)
	{
		id = i;
	}
	else
	{
		alert("Could not retrieve user information!");
		return false;
	}
	clearUserForm();
	document.getElementById("userid").value = id;
	document.getElementById("uname").value  = document.getElementById("unamehdn"+id).value;
	document.getElementById("ufname").value = document.getElementById("firsthdn"+id).value;
	document.getElementById("ulname").value = document.getElementById("lasthdn"+id).value;
	document.getElementById("uemail").value = document.getElementById("emailhdn"+id).value;

	document.getElementById("updateuserbtn").style.display = "inline";
	document.getElementById("updateuserbtn").style.visibility = "visible";
	document.getElementById("createuserbtn").style.display = "none";
	document.getElementById("createuserbtn").style.visibility = "hidden";
	$( "#userform" ).dialog( "option", "title", "Update Your Info" );
	$( "#userform" ).dialog( "open" );
}

function hideUserForm()
{
	clearUserForm();
	$( "#userform" ).dialog( "close" );
}

function clearUserForm()
{
	document.getElementById("uname").value = "";
	document.getElementById("ufname").value = "";
	document.getElementById("ulname").value = "";
	document.getElementById("uemail").value = "";
}

function hidePasswordForm()
{
	clearPasswordForm();
	$( "#passwordform" ).dialog( "close" );
}

function clearPasswordForm()
{
	document.getElementById("currpass").value = "";
	document.getElementById("newpass").value = "";
	document.getElementById("repnewpass").value = "";
}

function checkUserForm()
{
	var un = document.getElementById("uname").value;
	var fn = document.getElementById("ufname").value;
	var ln = document.getElementById("ulname").value;
	var ue = document.getElementById("uemail").value;

	var error = "";
	var missing = "";
	if(un.match(/^\s*$/))
	{
		missing = missing + "\nUsername";
	}
	if(fn.match(/^\s*$/))
	{
		missing = missing + "\nFirst Name";
	}
	if(ln.match(/^\s*$/))
	{
		missing = missing + "\nLast Name";
	}
	if(ue.match(/^\s*$/))
	{
		missing = missing + "\nEmail Address";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
	}

	if(error){
		alert(error);
		return false;
	}
	else
	{
		return true;
	}
}

function updateUser()
{
	var id = document.getElementById("userid").value;
	var un = document.getElementById("uname").value;
	var fn = document.getElementById("ufname").value;
	var ln = document.getElementById("ulname").value;
	var ue = document.getElementById("uemail").value;

	if(checkUserForm())
	{
		$.ajax({
			type: "POST",
			url: "php/library.php",
			data: { method:  'updateUser', 
				libcheck: true, 
				userid:		id,
				username:	un,
				firstname:	fn,
				lastname:	ln,
				useremail:	ue},
			success: function(result){
				var response = JSON.parse(result);
				alert(response['message']);
				if(response['status'] == "success")
				{
					document.getElementById("unamehdn" + id).value = un;
					document.getElementById("firsthdn" + id).value = fn;
					document.getElementById("lasthdn" + id).value = ln;
					document.getElementById("emailhdn" + id).value = ue;
					document.getElementById("tduname" + id).innerHTML = un;
					document.getElementById("tdfname" + id).innerHTML = fn;
					document.getElementById("tdlname" + id).innerHTML = ln;
					document.getElementById("tdemail" + id).innerHTML = ue;
					hideUserForm();
					clearUserForm();
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function createUser()
{
	var un = document.getElementById("uname").value;
	var fn = document.getElementById("ufname").value;
	var ln = document.getElementById("ulname").value;
	var ue = document.getElementById("uemail").value;

	if(checkUserForm())
	{
		$.ajax({
			type: "POST",
			url: "php/library.php",
			data: { method:  'createUser', 
					libcheck: true, 
					username:	un,
					firstname:	fn,
					lastname:	ln,
					useremail:	ue},
			success: function(result){
				var response = JSON.parse(result);
				alert(response['message']);
				if(response['status'] == "success")
				{
					document.getElementById("tablediv").innerHTML = response['content'];
					hideUserForm();
					clearUserForm();
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function showUpdatePasswordForm(i)
{
	var id = "";
	if(i)
	{
		id = i;
	}
	else
	{
		alert("Could not retrieve user information!");
		return false;
	}
	document.getElementById("userid").value = id;
	clearPasswordForm();
	$( "#passwordform" ).dialog( "open" );
}

function updatePassword()
{
	var id = document.getElementById("userid").value;
	var cp  = document.getElementById("currpass").value;
	var np1 = document.getElementById("newpass").value;
	var np2 = document.getElementById("repnewpass").value;

	var error = "";
	var missing = "";
	if(cp.match(/^\s*$/))
	{
		missing = missing + "\nCurrent Password";
	}
	if(np1.match(/^\s*$/))
	{
		missing = missing + "\nNew Password";
	}
	if(np2.match(/^\s*$/))
	{
		missing = missing + "\nRepeat New Password";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
	}
	if(np1 != np2)
	{
		error = error + "\nThe two new password fields must match!";
	}
	if(error){
		alert(error);
		return false;
	}
	$.ajax({
		type: "POST",
		url: "php/library.php",
		data: { method:  'updatePassword',
				libcheck: true,
				userid:		id,
				currpass:	cp,
				newpass:	np1},
		success: function(result){
			var response = JSON.parse(result);
			alert(response['message']);
			if(response['status'] == "success")
			{
				hidePasswordForm();
				clearPasswordForm();
			}
			return false;
		},
		error: function(){
			methodFail();
		}
	});
	return false;
}

function clearUploadForm(s)
{
	var yn = s ? 1 : confirm("Are you sure you want to clear this form?");
	if(yn)
	{
		document.getElementById("cfile").value = "";
		document.getElementById("cname").value = "";
		document.getElementById("ctitle").value = "";
		document.getElementById("calt").value = "";
		document.getElementById("csub").value = "";
		document.getElementById("ctags").value = "";
		document.getElementById("golivedate").value = "";

		var rdio = document.getElementsByName("golive");
		for(var i = 0; i < rdio.length; i++)
			rdio[i].checked = false;

		document.getElementById("postdatediv").style.visibility = "hidden";
		document.getElementById("postdatediv").style.display = "none";
	}
}

function clearUpdateForm(s)
{
	var yn = s ? 1 : confirm("Are you sure you want to clear this form?");
	if(yn)
	{
		document.getElementById("edit_comicid").value = "";
		document.getElementById("edit_curr").value = "";
		document.getElementById("edit_cfile").value = "";
		document.getElementById("edit_cname").value = "";
		document.getElementById("edit_ctitle").value = "";
		document.getElementById("edit_calt").value = "";
		document.getElementById("edit_csub").value = "";
		document.getElementById("edit_ctags").value = "";
		document.getElementById("edit_golivedate").value = "";

		var rdio = document.getElementsByName("edit_golive");
		for(var i = 0; i < rdio.length; i++)
			rdio[i].checked = false;

		var active = document.getElementsByName("edit_active");
		active[0].checked = true;

		document.getElementById("edit_postdatediv").style.visibility = "hidden";
		document.getElementById("edit_postdatediv").style.display = "none";
	}
}

function validateUpload()
{
	var file = document.getElementById("cfile").value;
	var name = document.getElementById("cname").value;
	var cttl = document.getElementById("ctitle").value;
	var calt = document.getElementById("calt").value;
	var csub = document.getElementById("csub").value;
	var golv = document.getElementById("golivedate").value;
	var rdio = document.getElementsByName("golive");

	var chck = true;
	var rdbt = "";
	for(var elem in rdio)
	{
		if(rdio[elem].checked)
		{
			rdbt = rdio[elem].value;
			chck = false;
		}
	}

	var error = "";
	var missing = "";
	if(file.match(/^\s*$/))
	{
		missing = missing + "\nFile";
	}
	if(name.match(/^\s*$/))
	{
		missing = missing + "\nFile name";
	}
	if(cttl.match(/^\s*$/))
	{
		missing = missing + "\nComic Title";
	}
	if(calt.match(/^\s*$/))
	{
		missing = missing + "\nAlt-text";
	}
	if(csub.match(/^\s*$/))
	{
		missing = missing + "\nSub-text";
	}
	if(chck)
	{
		missing = missing + "\nWhen to post";
	}
	if(!chck && rdbt == "future" && golv.match(/^\s*$/))
	{
		missing = missing + "\nPost date";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
		alert(error);
		return false;
	}
	document.getElementById('loadingdiv').style.visibility = 'visible';
	document.getElementById('loadingdiv').style.display = 'block';
	return true;
}

function validateUpdate()
{
	//var file = document.getElementById("edit_cfile").value;
	var cid  = document.getElementById("edit_comicid").value;
	var name = document.getElementById("edit_cname").value;
	var cttl = document.getElementById("edit_ctitle").value;
	var calt = document.getElementById("edit_calt").value;
	var csub = document.getElementById("edit_csub").value;
	var golv = document.getElementById("edit_golivedate").value;
	var rdio = document.getElementsByName("edit_golive");

	var chck = true;
	var rdbt = "";
	for(var elem in rdio)
	{
		if(rdio[elem].checked)
		{
			rdbt = rdio[elem].value;
			chck = false;
		}
	}

	var error = "";
	var missing = "";
	if(name.match(/^\s*$/))
	{
		missing = missing + "\nFile name";
	}
	if(cid.match(/^\s*$/))
	{
		missing = missing + "\nComic ID";
	}
	if(cttl.match(/^\s*$/))
	{
		missing = missing + "\nComic Title";
	}
	if(calt.match(/^\s*$/))
	{
		missing = missing + "\nAlt-text";
	}
	if(csub.match(/^\s*$/))
	{
		missing = missing + "\nSub-text";
	}
	if(chck)
	{
		missing = missing + "\nWhen to post";
	}
	if(!chck && rdbt == "future" && golv.match(/^\s*$/))
	{
		missing = missing + "\nPost date";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
		alert(error);
		return false;
	}
	document.getElementById('updatingdiv').style.visibility = 'visible';
	document.getElementById('updatingdiv').style.display = 'block';
	return true;
}

function showHideDatePicker(p)
{
	var prefix = p;
	var rdio = document.getElementsByName(prefix + "golive");
	for(var elem in rdio)
	{
		if(rdio[elem].checked)
		{
			if(rdio[elem].value == "future")
			{
				document.getElementById(prefix + "postdatediv").style.display = "block";
				document.getElementById(prefix + "postdatediv").style.visibility = "visible";
			}
			else if(rdio[elem].value == "immediately")
			{
				document.getElementById(prefix + "postdatediv").style.display = "none";
				document.getElementById(prefix + "postdatediv").style.visibility = "hidden";
			}
		}
	}
}

function comicQuery()
{
	var txt = document.getElementById("comicquery");
	var val = txt.value;
	var getall = 0;
	if(val.match(/^\s*$/))
	{
		getall = 1;
	}

	if(!getall && val.length < 2)
	{}
	else
	{
		$.ajax({
			type: "GET",
			url: "php/comicquery.php",
			data: { q: val, ga: getall },
			success: function(result){
				var response = JSON.parse(result);
				if(response['status'] == "success")
				{
					document.getElementById("comictablebody").innerHTML = response['content']['tablehtml'];
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function fillEditForm(i)
{
	var id = i ? i : 0;
	if(!id)
	{
		alert("Please enter a valid comic id!");
		return false;
	}
	else
	{
		$.ajax({
			type: "POST",
			url: "php/library.php",
			data: { method:  'getComicInfo',
					libcheck: true,
					comicid:  id },
			success: function(result){
				var response = JSON.parse(result);
				if(response['status'] == "success")
				{
					var rdio = document.getElementsByName("edit_golive");
					rdio[0].checked = false;
					rdio[1].checked = false;
					var pstat = response['content']['poststat'];
					if(pstat == "publish" || pstat == "inactive")
					{
						document.getElementById("edit_postdatediv").style.display = "none";
						document.getElementById("edit_postdatediv").style.visibility = "hidden";
						rdio[0].checked = true;
					}
					else
					{
						document.getElementById("edit_postdatediv").style.display = "block";
						document.getElementById("edit_postdatediv").style.visibility = "visible";
						rdio[1].checked = true;
					}

					var active = document.getElementsByName("edit_active");
					if(pstat != "inactive")
					{
						active[0].checked = true;
					}
					else
					{
						active[1].checked = true;
					}

					document.getElementById("edit_golivedate").value = response['content']['postdate'];
					document.getElementById("edit_comicid").value = id;
					document.getElementById("edit_curr").value 	 = response['content']['path'];	  //DISABLED - not editable, just show path of file

					document.getElementById("edit_cname").value  = response['content']['name'];	  //comic file name
					document.getElementById("edit_ctitle").value = response['content']['title'];  //comic title
					document.getElementById("edit_calt").value 	 = response['content']['alttxt']; //comic alt-text
					document.getElementById("edit_csub").value 	 = response['content']['subtxt']; //comic sub-text
					document.getElementById("edit_ctags").value  = response['content']['tags'];	  //comic tags (if exist)

					document.getElementById("h_edit_cname").value  = response['content']['name'];	//comic file name
					document.getElementById("h_edit_ctitle").value = response['content']['title'];  //comic title
					document.getElementById("h_edit_calt").value   = response['content']['alttxt']; //comic alt-text
					document.getElementById("h_edit_csub").value   = response['content']['subtxt']; //comic sub-text
					document.getElementById("h_edit_ctags").value  = response['content']['tags'];	//comic tags (if exist)
				}
				return false;
			},
			error: function(){
				methodFail();
			}
		});
		return false;
	}
}

function methodFail()
{
	alert("The call to the server failed - please try again");
}

function tabSelect(t)
{
	var s = t ? t : 0;
	$("#admintabs").tabs("option", "active", s);
}

function finishUpload(r,id)
{
	var message = "";
	if (r == 1 && id != 0)
	{
		alert("The file was uploaded successfully!\n\nHere is the link: http://thedoghousediaries.com/?p=" + id);
		clearUploadForm(1);
	}
	document.getElementById('loadingdiv').style.visibility = 'hidden';
	document.getElementById('loadingdiv').style.display = 'none';
	return true;
}

function finishUpdate(r,id)
{
	var message = "";
	if (r == 1 && id != 0)
	{
		document.getElementById("title_" + id).innerHTML = document.getElementById("edit_ctitle").value;
		var aori = $('input[name=edit_active]:checked').val();
		var liveval = $('input[name=edit_golive]:checked').val();
		var livedat = document.getElementById("edit_golivedate").value;
		if(aori == "active")
		{
			if(liveval == "future")
			{
				document.getElementById("poststat_" + id).innerHTML = "pending";
				document.getElementById(id).className = "pendingrow";
				document.getElementById(id).setAttribute("title", "Live Date: " + livedat);
			}
			else
			{
				document.getElementById("poststat_" + id).innerHTML = "published";
				document.getElementById(id).className = document.getElementById("postclass_" + id).innerHTML;
				document.getElementById(id).removeAttribute("title");
			}
		}
		else
		{
			document.getElementById("poststat_" + id).innerHTML = "inactive";
			document.getElementById(id).className = "inactiverow";
			document.getElementById(id).setAttribute("title", "Currently not displayed");
		}
		clearUpdateForm(1);
	}
	document.getElementById('updatingdiv').style.visibility = 'hidden';
	document.getElementById('updatingdiv').style.display = 'none';
	return true;
}

function fillEditFileName(p)
{
	var str = document.getElementById(p + "cfile").value;
	document.getElementById(p + "cname").value = str.split(/(\\|\/)/g).pop()
}

function clearAnnounceForm(s)
{
	var yn = s ? 1 : confirm("Are you sure you want to clear this form?");
	if(yn)
	{
		document.getElementById("announcetext").value = "";
		var active = document.getElementsByName("edit_active_announce");
		active[0].checked = true;
	}
}

function updateAnnouncement()
{
	var txt = document.getElementById("announcetext").value;
	var aori = $('input[name="edit_active_announce"]:checked').val();
	var error = "";
	var missing = "";
	if(txt.match(/^\s*$/))
	{
		missing = missing + "\nAnnouncement Text";
	}
	if(missing)
	{
		error = "The following fields are required:\n" + missing;
	}
	if(error){
		alert(error);
		return false;
	}

	$.ajax({
		type: "POST",
		url: "php/library.php",
		data: { method:  'updateAnnouncement',
				libcheck: true,
				text:		txt,
				active:		aori
		},
		success: function(result){
			var response = JSON.parse(result);
			alert(response['message']);
			return false;
		},
		error: function(){
			methodFail();
		}
	});
	return false;
}