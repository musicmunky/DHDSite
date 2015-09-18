
$(document).on("click", ".comicnavlinks", function(e) {
	
	e.preventDefault();
	var id = $(this).attr('href');
	getNextComic(id, true);

	return false;
});

//get the url and page info to set the history for the
//back button when the page is first loaded
var h = window.location.href;
var t = document.title;
var a = h.split("/");
var i = a[a.length-1];
var stateObj = { id: i, title: t, link: h };
history.replaceState(stateObj, t, h);

window.addEventListener('popstate', function(event) {
	var state = event.state;
	if(state){
		getNextComic(state['id'], false);
	}
});

function getNextComic(c, ps)
{
	var cnum = (c && !String(c).match(/^\s*$/) && String(c).match(/^[0-9]+$/)) ? c : "";
	$.ajax({
		type: "POST",
		url: "php/library.php",
		data: { firstload: 0,
				method:  'getAjaxComic', 
				libcheck: true, 
				value:    cnum },
		success: function(result){
			var response = JSON.parse(result);
			if(response['status'] == "success")
			{
				//assigning variables in case we need them later...
				//not necessary NOW...forward thinking! yeah!...
				var topnav = document.getElementById("topNav");
				var botnav = document.getElementById("bottomNav");
				var imgdiv = document.getElementById("imgdiv");
				var tithed = document.getElementById("titleheader");
				var subtxt = document.getElementById("subtext");
				
				topnav.innerHTML = response['content']['navbuttons'];
				botnav.innerHTML = response['content']['navbuttons'];
				imgdiv.innerHTML = response['content']['comic'];
				subtxt.innerHTML = response['content']['subtxt'];
				tithed.innerHTML = response['content']['title'];
				
				var title = response['content']['title'];
				var myhref = "http://" + window.location.host + "/" + response['content']['comicid'];
				var stateObj = { id: response['content']['comicid'], title: title, link: myhref };
				if(ps)
				{
					history.pushState(stateObj, title, myhref);
				}
				document.title = "DOGHOUSE | " + title;
				
				return false;
			}
			else
			{
				methodFail();
			}
		},
		error: function(){
			methodFail();
		}
	});
	return false;
}

function methodFail()
{
	alert("The call to the server failed - please try again");
}