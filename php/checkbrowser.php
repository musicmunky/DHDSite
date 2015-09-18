<?php 
function useAjax($b, $m)
{
	$use_ajax = 0;
	switch ($b)
	{
		case "IE":
			if ($m > 9) {
				$use_ajax = 1;
			}
			break;
	
		case "Firefox":
		case "Mozilla":
			if ($m > 4) {
				$use_ajax = 1;
			}
			break;
	
		case "Chrome":
			if ($m > 18) {
				$use_ajax = 1;
			}
			break;
	
		case "Safari":
			if ($m > 5) {
				$use_ajax = 1;
			}
			break;
	
		case "Opera":
			if ($m > 11) {
				$use_ajax = 1;
			}
			break;
			//default:
	}
	//echo "USE AJAX IS: " . $use_ajax . "<br />B IS: " . $b . "<br />M IS: " . $m;
	return $use_ajax;
}
?>