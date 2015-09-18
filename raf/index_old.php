<?php
	define('LIBRARY_CHECK',true);
	require 'php/library.php';
	require 'php/checkbrowser.php';
	date_default_timezone_set('America/New_York');

	$browser = get_browser(null, true);
	$b = $browser['browser'];
	$v = $browser['version'];
	$m = $browser['majorver'];

	$use_ajax = useAjax($b,$m);
	
	$t = isset($_GET['t']) ? $_GET['t'] : "";
	$t = urldecode($t);
	$t = mysql_real_escape_string($t);
	$html = "";
	if($t == "" || preg_match("/^\d+$/", $t))
	{
		if($use_ajax)
		{
			$html = getAjaxComic(array("firstload" => 1, "method" => 'getAjaxComic', "libcheck" => true, "value" => $t));
		}
		else
		{
			$html = getComic($t);
		}
	}
	else
	{
		$html = array(
				"status"	=> "NO COMIC",
				"message"	=> "NO COMIC MESSAGE",
				"content"	=> array(
						"comic"	 => "That comic doesn't exist!",
						"title"  => "These aren't the comics you're looking for...",
						"subtxt" => "Move along..."
					)
			);
	}
	//RAF INDEX PAGE
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title><?php echo "DOGHOUSE | " . $html['content']['title']; ?></title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/dhd.js"></script>
		
	<!-- ADDTHIS BUTTONS -->
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5209d6d50eb2bafe"></script>
	<!-- TWITTER FOLLOW BUTTON -->	
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</head>
	<body>
		<div id="bannerTop">
		<div id="logoSpace"><a href="http://198.89.125.200/raf"><img src="logos/stacked_logo_text.png"></a></div>
		<div id="topAd">
				<!-- BEGIN JS TAG - [728x90] < - DO NOT MODIFY --> 
				<script type="text/javascript"> 
					document.write('<SCR'+'IPT SRC="http://ads.sonobi.com/ttj?id=1667503&referrer=thedoghousediaries.com&cb='+(parseInt(Math.random()*100000))+'" TYPE="text/javascript"></SCR'+'IPT>'); 
				</script> 
				<!-- END TAG -->
		</div>
		</div>
		<div id="menu">
			<ul>
				<li><a class ="menuLinks" href="http://198.89.125.200/about">ABOUT US</a></li>
				<li><a class ="menuLinks" href="http://198.89.125.200/archive">ARCHIVE</a></li>
				<li><a class ="menuLinks" href="/mockup_f.html">STORE</a></li>
				<li><a class ="menuLinks" href="/mockup_f.html">CONTACT US</a></li>
				<li><a class ="menuLinks" href="/mockup_f.html">FACEBOOK</a></li>
				<li><a class ="menuLinks" href="/mockup_f.html">TWITTER</a></li>
				<li><a class ="menuLinks" href="/mockup_f.html">RSS</a></li>
			</ul>
		</div>
		<div id="comic">
			<div id="topNav">
				<?php echo isset($html['content']['navbuttons']) ? $html['content']['navbuttons'] : ""; ?>
			</div>
			<div id="imgdiv" style="text-align:center; margin-bottom:0px;">
				<?php echo isset($html['content']['comic']) ? $html['content']['comic'] : ""; ?>
			</div>
			<div id="bottomNav">
				<?php echo isset($html['content']['navbuttons']) ? $html['content']['navbuttons'] : ""; ?>
			</div>
		</div>
		<div id="signOff">
			<h1 id="titleheader">
				<?php echo $html['content']['title']; ?>
			</h1>
			<div id="subtext">
				<?php echo $html['content']['subtxt']; ?>
			</div>
		</div>
		<div id="socialMedia">
			<div id="facebookBox">
				<iframe frameborder="0" allowtransparency="true" style="border:none; overflow:hidden; width:200px; height:65px;" scrolling="no" src="https://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fthedoghousediaries&width&height=62&colorscheme=light&show_faces=false&header=false&stream=false&show_border=false&appId=489068647866484"></iframe>
			</div>	
			<div id="shareButtons">
				<div class="addthis_native_toolbox"></div>
			</div>
			<div id="twitterFollow">
				<a href="https://twitter.com/willrayraf" class="twitter-follow-button" data-show-count="true">Follow @willrayraf</a>
			</div>
 		</div>
		<div id="bottomAd">
			<!-- BEGIN JS TAG - [728x90] < - DO NOT MODIFY --> 
			<script type="text/javascript"> 
				document.write('<SCR'+'IPT SRC="http://ads.sonobi.com/ttj?id=1667503&referrer=thedoghousediaries.com&cb='+(parseInt(Math.random()*100000))+'" TYPE="text/javascript"></SCR'+'IPT>'); 
			</script> 
			<!-- END TAG -->
		</div>
	<!-- TEMPORARILY REMOVE OTHER COMIC AREA
		<div id="otherComics">
			<div class="thumb"><a href="http://thedoghousediaries.com/3198"><img class="thumbLink" src="logos/targetpractice.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/5203"><img class="thumbLink" src="logos/beard.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/3266"><img class="thumbLink" src="logos/toiletpaper.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/3211"><img class="thumbLink" src="logos/ice.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/5414"><img class="thumbLink" src="logos/map.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/5650"><img class="thumbLink" src="logos/cat.png"></a></div>
			<div class="thumb"><a href="http://wondernode.com/fictional-places-map"><img class="thumbLink" src="logos/fictionalplaces.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/5124"><img class="thumbLink" src="logos/tall.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/5053"><img class="thumbLink" src="logos/coffee.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/4491"><img class="thumbLink" src="logos/sheets.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/4639"><img class="thumbLink" src="logos/scienceofdogs.png"></a></div>
			<div class="thumb"><a href="http://thedoghousediaries.com/1127"><img class="thumbLink" src="logos/killingmachine.png"></a></div>			
		</div>
	-->
		<div id="footer">
			<div id="license">
				By Will, Ray, and Raf with special thanks to Tim for building the site!<br />
				Our work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc/3.0/">Creative Commons Attribution-NonCommercial 3.0 Unported License.</a><br />
				Basically, you may share, copy, reprint, or publish our work as long as you provide the source.<br />
			</div>
		</div>
	</body>
</html>