<?php ?>
<!-- BEGIN TWITTER FOLLOW BUTTON -->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<!-- END TWITTER FOLLOW BUTTON -->
<div id="follow-wrapper">
	<div id="follow">
		<div id="forekast-button" class="button">
			<a href="http://forekast.com" target="_blank"><img id="forekast-logo-white" src="../logos/forekast-logo-white.png"></a>
		</div>
		<div id="fb-button" class="button">
			<iframe frameborder="0" allowtransparency="true" style="border:none; overflow:hidden; width:200px; height:65px;" scrolling="no" src="https://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fthedoghousediaries&width&height=62&colorscheme=light&show_faces=false&header=false&stream=false&show_border=false&appId=489068647866484"></iframe>
		</div>
		<div id="patreon-button-wrapper" class="button">
			<a href="http://www.patreon.com/doghouse" target="_blank"><img id="patreon-button" src="../logos/patreon_big.png"></a>
		</div>
		<!-- <div id="twitter-button-wrapper" class="button">
			<input type="image" id="twitter-button" value="Follow us on Twitter!" src="../logos/twitter-follow.png" onmousedown="showDiv()" />
		</div>
		-->
		<?php include "includes/popup.php";?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
