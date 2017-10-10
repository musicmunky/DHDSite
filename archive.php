<?php
	define('LIBRARY_CHECK',true);
	require 'php/library.php';
	require 'php/checkbrowser.php';
	date_default_timezone_set('America/New_York');

	$html = getArchive();
?>
<html>
	<head>
		<title>DOGHOUSE | Archive</title>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8">
		<script language="javascript" type="text/javascript" src="javascript/jquery-1.11.0.min.js"></script>
		<script language="javascript" type="text/javascript" src="javascript/jquery.scrollToTop.js"></script>
		<script type="text/javascript">
            $(function() {
                $("#toTop").scrollToTop(1000);
            });
        </script>
	</head>
	<body>

		<?php include "includes/header.php"; ?>
		<a href="#top" id="toTop"></a>
		<div id="maincontent" class="archiveTable">
			<h1 class="pageTitle">Archive</h1>
			<?php echo $html['content']; ?>
		</div>
	</body>
</html>