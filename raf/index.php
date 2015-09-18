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
	$announce = getAnnouncementComic();
	//RAFS INDEX PAGE
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11-strict.dtd">
<html>
	<head>
		<title><?php echo "DOGHOUSE | " . $html['content']['title']; ?></title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/dhd.js"></script>
		
		<?php include_once("analyticstracking.php") ?>
		
		<!-- BEGIN JAVASCRIPT FOR TWITTER FOLLOW POPUP DIV -->
		<script type="text/javascript">function showDiv(){document.getElementById('popup-follow').style.display = 'block';}</script>
		<script type="text/javascript">window.addEventListener("mouseup", function(event){var box = document.getElementById('twitter-button'); var box2 = document.getElementById('popup-follow');if (event.target != box){box2.style.display = 'none';}});</script>
		<!-- END JAVASCRIPT FOR TWITTER FOLLOW POPUP DIV -->
		
		<!-- BEGIN SOCIAL MEDIA SHARING BUTTONS -->
			<!-- ADDTHIS BUTTONS -->
				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5209d6d50eb2bafe"></script>
			<!-- TWITTER FOLLOW BUTTON -->	
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		<!-- END SOCIAL MEDIA SHARING BUTTONS -->
		</head>
	<body>
		<?php include "includes/header.php"; ?>
				
		<div id="maincontent" class="comic">
			<?php if ($announce['content']['active'] == 1): ?> 
				<div id="announcements">
					<div id="announcediv" class="textfields">
						<?php echo $announce['content']['text']; ?>
					</div>
				</div>
	 		<?php endif; ?>
 		
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
				
		<div id="signoff-wrapper">
			<div id="title-signoff-share">
				<h2 id="titleheader">
					<?php echo $html['content']['title']; ?>
				</h2>
				<div id="subtext" class="textfields">
					<?php echo $html['content']['subtxt']; ?>
				</div>
				<div class="addthis_native_toolbox">
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php include "includes/socialmedia.php"; ?>
		<?php include "includes/other.php"; ?>
		<?php include "includes/bottomad.php"; ?>
		<?php include "includes/footer.php"; ?>
	</body>
</html>