<?php
if(!defined('VALID_SITE')) exit('No direct access! ');
### Basic functions for use ###

function redirect($pagePath){
	header("Location: " . $pagePath);
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$pagePath.'">';
	echo "<script type='text/javascript'>window.location = '".$pagePath."'</script>";
}

function word_strip($str){
	return preg_replace('/[^A-Za-z0-9_-]/','',$str);
}

function file_strip($str){
	return preg_replace('/[^A-Za-z0-9.\/_-]/','',$str);
}

function numbers_only($str){
	return !preg_match('[\D]',$str);
}

function invalid_ident_char($name, $strip = false){ //Checks if an identifier is valid. 
	$pattern = '/[^A-Za-z0-9_-]/';
	if($strip)
		return preg_replace($pattern,'',$name);
	return preg_match($pattern,$name);
}

 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
 
function ucwords2($str, $delimiter){
	$delimiter_space = '- ';
	return str_replace($delimiter_space, $delimiter, 
		ucwords(str_replace($delimiter, $delimiter_space, $str)));
}

function array2string($array, $default = false){ //Takes either strings or table subclass objects
	if(!is_array($array))
		return $default; 
	$str = ''; 
	$len = count($array);
	
	$j = $len; 
	if($len >= 1 || ($len == 0 && $default == false)){
		foreach($array as $obj){
			if(is_object($obj))
				$class = get_class($obj);
			else 
				$class = '';
			$isTable = false; 
			if($class)
				$isTable = is_subclass_of($obj, 'Table');
			
			$j--; 
			
			if($j > 0)
				$str = $str . ($isTable ? $obj->link() : $obj) . ($len > 2 ? ', ' : ' ');
			else
				$str = $str . ($len > 1 ? 'and ' : '') . ($isTable ? $obj->link() : $obj);
			
		}
	}else{
		$str = $default; 
	}
	
	return $str; 
 }
 
//Month stuff
function getMonth($id){
	$months = Array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 
		7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
	$id = intval($id); 
	if($id && $id <= 12) 
		return $months[$id];
	return 'Month';
}
 
function relative_time($date) {
	if(!numbers_only($date))
		$date = strtotime($date);
	$diff = time() - $date;
	if ($diff<60)
		return plural("second", $diff) . " ago";
	$diff = round($diff/60);
	if ($diff<60)
		return plural("minute", $diff) . " ago";
	$diff = round($diff/60);
	if ($diff<24)
		return plural("hour", $diff) . " ago";
	$diff = round($diff/24);
	if ($diff<7)
		return plural("day", $diff) . " ago";
	$diff = round($diff/7);
	if ($diff<4)
		return plural("week", $diff) . " ago";
	return date("F j, Y", $date);
}

### Sanitization functions ###
function sanitize_string($string){ //Basic sanitization with escaping.  
	return escape($string); 
}

function prepare_string($string){
	return htmlentities(stripslashes($string));
}
?>