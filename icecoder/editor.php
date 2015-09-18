<?php
include("lib/headers.php");
include("lib/settings.php");
$t = $text['editor'];
?>
<!DOCTYPE html>

<html style="margin: 0" onMouseDown="top.ICEcoder.mouseDown=true" onMouseUp="top.ICEcoder.mouseDown=false; if (!top.ICEcoder.overCloseLink) {top.ICEcoder.tabDragEnd()}" onMouseMove="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'editor');top.ICEcoder.canResizeFilesW()}" onDrop="if(top.ICEcoder) {top.ICEcoder.getMouseXY(event,'editor')}">
<head>
<title>ICEcoder v <?php echo $ICEcoder["versionNo"];?> editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror.css">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/addon/hint/show-hint.css">
<link rel="stylesheet" href="<?php echo $ICEcoder["codeMirrorDir"]; ?>/addon/lint/lint.css">
<!--
codemirror-compressed.js
incls:	codemirror
modes:	clike, coffeescript, css, erlang, go, htmlmixed, javascript, julia, lua, markdown, perl, php, python, ruby, rust, sass, sql, xml, yaml
addon:	brace-fold, closebrackets, closetag, css-hint, html-hint, javascript-hint, javascript-lint, lint, match-highlighter, searchcursor, show-hint, sql-hint, trailingspace, xml-fold, xml-hint
//-->
<script src="<?php echo $ICEcoder["codeMirrorDir"]; ?>/lib/codemirror-compressed.js"></script>
<?php
if (file_exists(dirname(__FILE__)."/plugins/jshint/jshint-2.5.6.min.js")) {
	echo '<script src="plugins/jshint/jshint-2.5.6.min.js"></script>';
};?>
<script src="lib/mmd.js"></script>
<script src="lib/foldcode.js"></script>
<?php
if (file_exists(dirname(__FILE__)."/plugins/emmet/emmet.min.js")) {
	echo '<script src="plugins/emmet/emmet.min.js"></script>';
};?>
<?php
if (file_exists(dirname(__FILE__)."/plugins/pesticide/pesticide.js")) {
	echo '<script src="plugins/pesticide/pesticide.js"></script>';
};?>
<?php
if (file_exists(dirname(__FILE__)."/plugins/stats.js/stats.min.js")) {
	echo '<script src="plugins/stats.js/stats.min.js"></script>';
};?>
<link rel="stylesheet" href="<?php
if ($ICEcoder["theme"]=="default") {echo 'lib/editor.css';} else {echo $ICEcoder["codeMirrorDir"].'/theme/'.$ICEcoder["theme"].'.css';};
$activeLineBG = array_search($ICEcoder["theme"],array("3024-day","base16-light","eclipse","elegant","neat","solarized","xq-light")) !== false ? "#ccc" : "#000";
?>">

<style type="text/css">
/* Make sure this next one remains the 1st item, updated with JS */
.CodeMirror {position: absolute; top: 0; width: 100%; font-size: <?php echo $ICEcoder["fontSize"];?>; z-index: 1}
.CodeMirror-scroll {} /* was: height: auto; overflow: visible */
/* Make sure this next one remains the 3rd item, updated with JS */
.cm-s-activeLine {background: <?php echo $activeLineBG;?> !important}
.cm-matchhighlight, .CodeMirror-focused .cm-matchhighlight {color: #fff !important; background: #06c !important}
/* Make sure this next one remains the 5th item, updated with JS */
.cm-tab {border-left-width: <?php echo $ICEcoder["visibleTabs"] ? "1px" : "0";?>; margin-left: <?php echo $ICEcoder["visibleTabs"] ? "-1px" : "0";?>; border-left-style: solid; border-left-color: rgba(255,255,255,0.15)}
.cm-trailingspace {
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAACCAYAAAB/qH1jAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QUXCToH00Y1UgAAACFJREFUCNdjPMDBUc/AwNDAAAFMTAwMDA0OP34wQgX/AQBYgwYEx4f9lQAAAABJRU5ErkJggg==);
        background-position: bottom left;
        background-repeat: repeat-x;
      }
.CodeMirror-foldmarker {font-family: arial; line-height: .3; color: #b00; cursor: pointer;
	text-shadow: #fff 1px 1px 2px, #fff -1px -1px 2px, #fff 1px -1px 2px, #fff -1px 1px 2px;
}
.folds {display: inline-block; width: 13px}
.fold {position: absolute; display: inline-block; width: 13px; height: 13px; font-size: 14px; text-align: center; cursor: pointer}
.foldOn {background: #800; color: #ddd}
.foldOff {background: rgba(255,255,255,0.04); color: #666}
.demoArrow {position: absolute; display: inline-block; width: 99px; height: 50px; top: 0; right: 30px; background: url('images/big-arrow.gif') 0 -10px no-repeat; text-align: center; font-family: arial; font-size: 10px; padding-top: 60px}
h2 {color: rgba(0,198,255,0.7)}
.heading {color:#888}
.cm-s-diff {left: 50%}
.diffGreen {background: #0b0 !important; color: #000 !important}
.diffRed {background: #800 !important; color: #fff !important}
.diffGrey {background: #444 !important; color: #fff !important}
.diffGreyLighter {background: #888 !important; color: #222 !important}
.diffNone {}
</style>
<link rel="stylesheet" href="lib/file-types.css">
<link rel="stylesheet" href="lib/file-type-icons.css">
</head>

<body style="color: #fff; margin: 0" onKeyDown="return top.ICEcoder.interceptKeys('content', event);" onKeyUp="top.ICEcoder.resetKeys(event);" onBlur="parent.ICEcoder.resetKeys(event);">

<?php if ($ICEcoder['demoMode']) {?>
<div class="demoArrow"><?php echo $t['Click icons for...'];?></div>
<?php ;}; ?>

<div style="display: none; margin: 32px 43px 0 43px; padding: 10px; width: 500px; font-family: arial; font-size: 10px; color: #ddd; background: #333" id="dataMessage"></div>

<div style="margin: 20px 43px 32px 43px; font-family: arial; font-size: 10px; color: #ddd">
	<div style="float: left; width: 300px; margin-right: 50px">
		<h2><?php echo $t['server'];?></h2>
		<span class="heading"><?php echo $t['Server name, OS...'];?></span><br>
		<?php echo $_SERVER['SERVER_NAME']." &nbsp;&nbsp ".$_SERVER['SERVER_SOFTWARE']." &nbsp;&nbsp ".(isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:"Unknown");?><br><br>
		<span class="heading"><?php echo $t['Root'];?></span><br>
		<?php echo $docRoot;?><br><br>
		<span class="heading"><?php echo $t['ICEcoder root'];?></span><br>
		<?php echo $docRoot.$iceRoot;?><br><br>
		<span class="heading"><?php echo $t['PHP version'];?></span><br>
		<?php echo phpversion();?><br><br>
		<span class="heading"><?php echo $t['Date & time'];?></span><br>
		<span id="serverDT"></span><br><br>
		<h2><?php echo $t['your device'];?></h2>
		<span class="heading"><?php echo $t['Browser'];?></span><br>
		<?php echo xssClean($_SERVER['HTTP_USER_AGENT'],"html");?><br><br>
		<span class="heading"><?php echo $t['Your IP'];?></span><br>
		<?php echo $_SERVER['REMOTE_ADDR'];?><br><br>
	</div>

	<div style="float: left">
		<h2><?php echo $t['files'];?></h2>
		<span class="heading"><?php echo $t['Last 10 files...'];?></span><br>
		<ul class="fileManager" style="margin-left: 0; line-height: 20px">
		<?php
			$last10FilesArray = explode(",",$ICEcoder["last10Files"]);
			for ($i=0;$i<count($last10FilesArray);$i++) {
				if ($ICEcoder["last10Files"]=="") {
					echo '<div style="display: inline-block; margin-left: -39px; margin-top: -4px">'.$t['none'].'</div><br><br>';
				} else {
					$fileFolderName = str_replace("\\","/",$last10FilesArray[$i]);
					// Get extension (prefix 'ext-' to prevent invalid classes from extensions that begin with numbers)
					$ext = "ext-".pathinfo($docRoot.$iceRoot.$fileFolderName, PATHINFO_EXTENSION);
					echo '<li class="pft-file '.strtolower($ext).'" style="margin-left: -21px">';
					echo '<a style="cursor:pointer" onClick="top.ICEcoder.openFile(\''.str_replace("|","/",$last10FilesArray[$i]).'\')">';
					echo str_replace($docRoot,"",str_replace("|","/",$last10FilesArray[$i]));
					echo '</a></li>'.PHP_EOL;
					if ($i==count($last10FilesArray)-1) {echo '<br>'.PHP_EOL;};
				}
			}
		;?>
		</ul>
	</div>

	<div style="clear: both"></div>
	<script>
	var nDT=<?php echo time()*1000;?>;
	setInterval(function(){
		var s=(new Date(nDT+=1e3)+'').split(' '),
		d=s[2]*1,
		t=s[4].split(':'),
		p=t[0]>11?'pm':'am',
		e=d%20==1|d>30?'st':d%20==2?'nd':d%20==3?'rd':'th';
		t[0]=--t[0]%12+1;
		if (document.getElementById('serverDT')) {
			document.getElementById('serverDT').innerHTML=[s[0],d+e,s[1],s[3],t.join(':')+p].join(' ');
		}
	},1000);
	</script>
	<?php if(is_dir('test') && !$ICEcoder['demoMode']) {?>
	<div style="float: left; margin-right: 50px">
		<h2><?php echo $t['test suite'];?></h2>
		<span class="heading"><?php echo $t['Run unit tests'];?></span><br>
		<a nohref onclick="top.ICEcoder.filesFrame.contentWindow.frames['testControl'].location.href = 'test'" style="color: #fff; cursor: pointer"><?php echo $t['Run unit tests'];?></a><div id="unitTestResults"></div>
	</div>
	<?php
	;};
	?>
	<div style="float: left">
		<h2><?php echo $t['dev mode'];?> <?php echo $ICEcoder['devMode'] ? "on" : "off";?></h2>
		<span class="heading"><?php echo $t['Status'];?>:</span><br>
		<?php echo $t['Using']?> <?php echo $ICEcoder['devMode'] ? "ice-coder.js" : "ice-coder.min.js";?> <a title="<?php echo $t['You can switch...'];?>" style="cursor: pointer">[?]</a>
	</div>
	<div style="clear: both"></div>
</div>

<script>
CodeMirror.keyMap.ICEcoder = {
	"Tab": function(cm) {
		return cm.somethingSelected() ? cm.execCommand("indentAuto") : CodeMirror.Pass // Falls through to default or Emmet plugin
	},
	"Shift-Tab": "indentLess",
	"Ctrl-Space": "autocomplete",
	"Ctrl-Up" : false,
	"Ctrl-Down" : false,
	"Esc" : false,
	fallthrough: ["default"]
};

function createNewCMInstance(num) {
	// Establish the filename for the tab
	var fileName = top.ICEcoder.openFiles[top.ICEcoder.selectedTab-1];

	// Define our CodeMirror options
	var cMOptions = {
		mode: "application/x-httpd-php",
		lineNumbers: true,
		gutters: ["folds","CodeMirror-lint-markers","CodeMirror-linenumbers"],
		lineWrapping: top.ICEcoder.lineWrapping,
		indentWithTabs: top.ICEcoder.indentWithTabs,
		indentUnit: top.ICEcoder.indentSize,
		tabSize: top.ICEcoder.indentSize,
		electricChars: false,
		autoCloseTags: true,
		autoCloseBrackets: true,
		highlightSelectionMatches: true,
		showTrailingSpace: true,
		lint: false,
		keyMap: "ICEcoder"
	};

	// Start editor instances, main and diff
	window['cM'+num]	= CodeMirror(document.body, cMOptions);
	window['cM'+num+'diff']	= CodeMirror(document.body, cMOptions);

	// Define actions for those...

	// Focus
	window['cM'+num]	.on("focus", function(thisCM) {top.ICEcoder.cMonFocus(thisCM,'cM'+num)});
	window['cM'+num+'diff']	.on("focus", function(thisCM) {top.ICEcoder.cMonFocus(thisCM,'cM'+num+'diff')});

	// Blur
	window['cM'+num]	.on("blur", function(thisCM) {top.ICEcoder.cMonBlur(thisCM,'cM'+num)});
	window['cM'+num+'diff']	.on("blur", function(thisCM) {top.ICEcoder.cMonBlur(thisCM,'cM'+num+'diff')});

	// Keyup
	window['cM'+num]	.on("keyup", function(thisCM) {top.ICEcoder.cMonKeyUp(thisCM,'cM'+num)});
	window['cM'+num+'diff']	.on("keyup", function(thisCM) {top.ICEcoder.cMonKeyUp(thisCM,'cM'+num+'diff')});
	
	// Cursor activity
	window['cM'+num]	.on("cursorActivity", function(thisCM) {top.ICEcoder.cMonCursorActivity(thisCM,'cM'+num)});
	window['cM'+num+'diff']	.on("cursorActivity", function(thisCM) {top.ICEcoder.cMonCursorActivity(thisCM,'cM'+num+'diff')});

	// Before selection change
	window['cM'+num]	.on("beforeSelectionChange", function(thisCM, changeObj) {top.ICEcoder.prevLine = thisCM.getCursor().line;});
	window['cM'+num+'diff']	.on("beforeSelectionChange", function(thisCM, changeObj) {top.ICEcoder.prevLineDiff = thisCM.getCursor().line;});

	// Change
	window['cM'+num]	.on("change", function(thisCM, changeObj) {top.ICEcoder.cMonChange(thisCM,'cM'+num,changeObj)});
	window['cM'+num+'diff']	.on("change", function(thisCM, changeObj) {top.ICEcoder.cMonChange(thisCM,'cM'+num+'diff',changeObj)});

	// Scroll
	window['cM'+num]	.on("scroll", function(thisCM) {top.ICEcoder.cMonScroll(thisCM,'cM'+num)});
	window['cM'+num+'diff']	.on("scroll", function(thisCM) {top.ICEcoder.cMonScroll(thisCM,'cM'+num+'diff')});

	// Gutter click
	window['cM'+num]	.on("gutterClick", function(thisCM, line, gutter, clickEvent) {CodeMirror.doFold(thisCM.getLine(line).indexOf("{")>-1 ? "brace" : "xml",null,"+","-",false)(thisCM, line);});
	window['cM'+num+'diff']	.on("gutterClick", function(thisCM, line, gutter, clickEvent) {CodeMirror.doFold(thisCM.getLine(line).indexOf("{")>-1 ? "brace" : "xml",null,"+","-",false)(thisCM, line);});

	// Input read
	window['cM'+num]	.on("inputRead", function(thisCM) {top.ICEcoder.cMonInputRead(thisCM,'cM'+num)});
	window['cM'+num+'diff']	.on("inputRead", function(thisCM) {top.ICEcoder.cMonInputRead(thisCM,'cM'+num+'diff')});

	// Render line
	window['cM'+num]	.on("renderLine", function(thisCM, line, element) {top.ICEcoder.cMonRenderLine(thisCM,'cM'+num,line,element)});
	window['cM'+num+'diff']	.on("renderLine", function(thisCM, line, element) {top.ICEcoder.cMonRenderLine(thisCM,'cM'+num+'diff',line,element)});

	// Now create the active lines for them
	top.ICEcoder['cMActiveLinecM'+num] = window['cM'+num].addLineClass(0, "background", "cm-s-activeLine");
	top.ICEcoder['cMActiveLinecM'+num+'diff'] = window['cM'+num+'diff'].addLineClass(0, "background", "cm-s-activeLine");
};
</script>

<div style="position: absolute; display: none; width: 5px; height: 100%; top: 0; right: 0; background: rgba(255,255,255,0.1); overflow: hidden; z-index: 2" id="resultsBar"></div>

<?php include_once("processes/on-editor-load.php"); ?>

</body>

</html>