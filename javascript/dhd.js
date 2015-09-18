/*
$(document).ready(function() {
	if (window.addthis) {
		addthis.init();
	}
});*/
/*
$(document).on("click", ".comicnavlinks", function(e) {
	
	e.preventDefault();
	var id = $(this).attr('href');
	getNextComic(id, true);

	return false;
});
*/
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
				var topnav = document.getElementById("topNav");
				var botnav = document.getElementById("bottomNav");
				var imgdiv = document.getElementById("imgdiv");
				var tithed = document.getElementById("titleheader");
				var subtxt = document.getElementById("subtext");
				var alttxt = document.getElementById("alttxt");
				var prmlnk = document.getElementById("permalink");
				var topad  = document.getElementById("topAd");
				var botad  = document.getElementById("bottomAd");

				topnav.innerHTML = response['content']['navbuttons'];
				botnav.innerHTML = response['content']['navbuttons'];
				imgdiv.innerHTML = response['content']['comic'];
				subtxt.innerHTML = response['content']['subtxt'];
				tithed.innerHTML = response['content']['title'];
				alttxt.innerHTML = "Alt-Text: " + response['content']['alttext'];
				prmlnk.innerHTML = response['content']['permlnk'];
				//topad.innerHTML =  response['content']['topad'];
				//botad.innerHTML =  response['content']['botad'];

				var title = response['content']['title'];
				var myhref = "http://" + window.location.host + "/" + response['content']['comicid'];
				var stateObj = { id: response['content']['comicid'], title: title, link: myhref };
				if(ps)
				{
					history.pushState(stateObj, title, myhref);
				}
				document.title = "DOGHOUSE | " + title;
				$('html,body').scrollTop(0);


				/*if (window.addthis) {
					addthis.init();
				}*/
				//addthis.init();

				//$( ".addthis_native_toolbox" ).remove();
				//$( ".addthis_native_toolbox" ).empty();


				/*var tbx = document.getElementById("atnt"),
				svcs = {email: 'Email', print: 'Print', facebook: 'Facebook', expanded: 'More'};

				for (var s in svcs) {
				    tbx.innerHTML += '<a class="addthis_button_'+s+'">'+svcs[s]+'</a>';
				}*/


				//addthis.toolbox('.addthis_native_toolbox');
				//addthis.toolbox('.addthis_native_toolbox', null, { 'title': "DOGHOUSE | " + title, 'url': myhref });
				//addthis.ready();


				/*var jstag = document.getElementById("addthissrc");
				var tlbx = document.getElementById("atnt");
				//tlbx.innerHTML = "";
				tlbx.setAttribute("data-url", myhref);
				tlbx.setAttribute("data-title", "DOGHOUSE | " + title)

				if (window.addthis) {
				    window.addthis = null;
				    window._adr = null;
				    window._atc = null;
				    window._atd = null;
				    window._ate = null;
				    window._atr = null;
				    window._atw = null;
				}*/

				//var addthis_config = addthis_config||{};
				//addthis_config.pubid = 'ra-5209d6d50eb2bafe';
				//window['addthis_share'].url = myhref;
				//window['addthis_share'].title = "DOGHOUSE | " + title;
				//$.getScript( "https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5209d6d50eb2bafe&async=1");
				//addthis.toolbox('.addthis_native_toolbox');

				//jstag.setAttribute("src", "http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5209d6d50eb2bafe&async=1");
				/*
				tlbx.setAttribute("data-url", myhref);
				tlbx.setAttribute("data-title", "DOGHOUSE | " + title)
				addthis.update('share', 'title', "DOGHOUSE | " + title);
				addthis.update('share', 'url', myhref);
				*/

				/*
				window['addthis_share'].url = myhref;
				window['addthis_share'].title = "DOGHOUSE | " + title;

				addthis.toolbox('.addthis_native_toolbox');
				addthis.ready();
				addthis.init();
				*/

				/*
				if (!window.addthis) {
					// Load addThis, if it hasn't already been loaded.
					window['addthis_config'] = { 'data_track_addressbar' : false };
					$('body').append('<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5209d6d50eb2bafe&async=1"></script>');
				} else {


					window['addthis_config'] = { 'data_track_addressbar' : false };
					$('body').append('<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5209d6d50eb2bafe&async=1"></script>');

					// Already loaded? Then re-attach it to the newly rendered set of social icons.
					// And reset share url/title, so they don't carry-over from the previous page.
					window['addthis_share'].url = myhref;
					window['addthis_share'].title = "DOGHOUSE | " + title;
					window.addthis.toolbox('.addthis_toolbox', {}, {'url':myhref});
				}
				*/

				//addthis.update('share', 'url', myhref); 
				//addthis.url = myhref;                
				//addthis.toolbox(".addthis_native_toolbox");
				//addthis.toolbox('.addthis_native_toolbox');

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
