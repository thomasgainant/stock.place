<?php
session_start();

$GLOBALS["bdd_server"] = 'xxxxxxx';
$GLOBALS['bdd_dsn'] = 'xxxxxxxx';
$GLOBALS["bdd_db"] = 'xxxxxxxx';
$GLOBALS["bdd_lg"] = 'xxxxxxx';
$GLOBALS["bdd_pw"] = 'xxxxxxxxxx';
$GLOBALS['bdd'] = null;

$GLOBALS['bdd_users'] = 'twothirds_moccus_users';
$GLOBALS['bdd_warehouses'] = 'twothirds_moccus_warehouses';
$GLOBALS['bdd_item_definition'] = 'twothirds_moccus_items_definition';
$GLOBALS['bdd_items'] = 'twothirds_moccus_items';
$GLOBALS['bdd_logs'] = 'twothirds_moccus_logs';

$GLOBALS['lemotdepassesiouplait'] = "ddddddddddddddddd";

$GLOBALS['loc'] = array (
	"en"  => array(
		"" => ""
	),
	"fr" => array()
);

$GLOBALS['lang'] = "en";
	
refreshLanguage();

include_once('user.class.php');
include_once('warehouse.class.php');
include_once('itemdefinition.class.php');
include_once('item.class.php');
include_once('log.class.php');

$GLOBALS['user'] = null;
$GLOBALS['warehouse'] = null;
$GLOBALS['definition'] = null;

$GLOBALS['error'] = null;
$GLOBALS['info'] = null;

function connectToDB(){
	try{
		// On se connecte à MySQL
		//$GLOBALS['bdd'] = new PDO($GLOBALS['bdd_dsn'], $GLOBALS['bdd_lg'], $GLOBALS['bdd_pw']);
		$GLOBALS['bdd'] = new PDO($GLOBALS['bdd_dsn'], $GLOBALS['bdd_lg'], $GLOBALS['bdd_pw'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch(Exception $e){
		// En cas d'erreur, on affiche un message et on arrête tout
		die('Erreur type '.$e->getCode().' : '.$e->getMessage());
	}
}

function disconnectFromDB(){
	$GLOBALS['bdd'] = null;
}

function refreshLanguage(){
	if(isset($_GET['lang']) && !empty($_GET['lang'])){
		if($_GET['lang'] == "fr"){
			$GLOBALS['lang'] = "fr";
			$_SESSION['lang'] = "fr";
		}
		else{
			$GLOBALS['lang'] = "en";
			$_SESSION['lang'] = "en";
		}
	}
	else if(isset($_SESSION['lang']) && !empty($_SESSION['lang'])){
		if($_SESSION['lang'] == "fr"){
			$GLOBALS['lang'] = "fr";
		}
		else{
			$GLOBALS['lang'] = "en";
		}
	}
	else{
		if(strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], "fr") !== FALSE
			|| strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], "fr-FR") !== FALSE){
			$GLOBALS['lang'] = "fr";
		}
		else{
			$GLOBALS['lang'] = "en";
		}
	}
	
	//$GLOBALS['lang'] = "en";
}

function getMultiLocalisedString($string){
	$strings = explode(":|:", $string);
	
	if(count($strings) <= 1){
		return $string;
	}
	
	if($GLOBALS['lang'] == "fr"){
		return $strings[0];
	}
	else{
		return $strings[1];
	}
}

function loc($string){
	$strings = explode(":|:", $string);
	
	if(count($strings) <= 1){
		echo $string;
	}
	
	if($GLOBALS['lang'] == "fr"){
		echo $strings[0];
	}
	else{
		echo $strings[1];
	}
}

	/*MISC*/
	
	function url_origin( $s, $use_forwarded_host = false )
	{
		$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
		$sp       = strtolower( $s['SERVER_PROTOCOL'] );
		$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
		$port     = $s['SERVER_PORT'];
		$port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
		$host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
		$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
		return $protocol . '://' . $host;
	}

	function full_url( $s, $use_forwarded_host = false )
	{
		return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
	}
	
		/**
	 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
	 *
	 * @param string $text String to truncate.
	 * @param integer $length Length of returned string, including ellipsis.
	 * @param string $ending Ending to be appended to the trimmed string.
	 * @param boolean $exact If false, $text will not be cut mid-word
	 * @param boolean $considerHtml If true, HTML tags would be handled correctly
	 *
	 * @return string Trimmed string.
	 */
	function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
		if ($considerHtml) {
			// if the plain text is shorter than the maximum length, return the whole text
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = '';
			foreach ($lines as $line_matchings) {
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (!empty($line_matchings[1])) {
					// if it's an "empty element" with or without xhtml-conform closing slash
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
						// do nothing
					// if tag is a closing tag
					} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
						unset($open_tags[$pos]);
						}
					// if tag is an opening tag
					} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length+$content_length> $length) {
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
						// calculate the real length of all entities in the legal range
						foreach ($entities[0] as $entity) {
							if ($entity[1]+1-$entities_length <= $left) {
								$left--;
								$entities_length += strlen($entity[0]);
							} else {
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if($total_length>= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}
		// if the words shouldn't be cut in the middle...
		if (!$exact) {
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if($considerHtml) {
			// close all unclosed html-tags
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
	
		/*ARRAYS*/
function array_splice_assoc(&$input, $offset, $length, $replacement) {
        $replacement = (array) $replacement;
        $key_indices = array_flip(array_keys($input));
        if (isset($input[$offset]) && is_string($offset)) {
                $offset = $key_indices[$offset];
        }
        if (isset($input[$length]) && is_string($length)) {
                $length = $key_indices[$length] - $offset;
        }

        $input = array_slice($input, 0, $offset, TRUE)
                + $replacement
                + array_slice($input, $offset + $length, NULL, TRUE);
}

function array_move($which, $where, $array)
{
    $tmpWhich = $which;
    $j=0;
    $keys = array_keys($array);

    for($i=0;$i<count($array);$i++)
    {
        if($keys[$i]==$tmpWhich)
            $tmpWhich = $j;
        else
            $j++;
    }
    $tmp  = array_splice($array, $tmpWhich, 1);
    array_splice_assoc($array, $where, 0, $tmp);
    return $array;
}
/*$array = array('fruits' => 'apple','vegetables' => 'garlic','nuts' => 'cashew','meat' => 'beaf');
$res = array_move('vegetables',2,$array);
var_dump($res);*/

//TODO
/*function array_associative_sort($array, $order){
	if($order == "DESC"){
		if(count($array) == 1){
			return $array;
		}
		else{
			for($i = 1; $i < count($array); $i++){
				if($array[$i] > $array[$i-1]){
					
				}
			}
		}
	}
	else{
	
	}
}*/

		/*TIME*/

function timestampToDatetimeUS($timestamp){
  $datetime = date('Y-m-d H:i:s', $timestamp);
  return $datetime;
}

function dateUSToDateFR($date){
  $date = explode('-', $date);
  $date = array_reverse($date);
  $date = implode('/', $date);
  return $date;
}

function dateFRToDateUS($date){
  $date = explode('/', $date);
  $date = array_reverse($date);
  $date = implode('-', $date);
  return $date;
}

function dateUSToTimestamp($date){
  list($year, $month, $day) = explode('-', $date);
  $timestamp = mktime(0, 0, 0, $month, $day, $year);
  return $timestamp;
}

function dateFRToTimestamp($date){
  list($day, $month, $year) = explode('/', $date);
  $timestamp = mktime(0, 0, 0, $month, $day, $year);
  return $timestamp;
}	

/*
* @param integer $year Année (AAAA)
*/

function maxDaysInMonth($month, $year){
  $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
  return $days;
}

/**
* Fonction de verification si une année est bissextile.
* @param integer $year Année (AAAA)
* @return boolean Vrai ou Faux	*/

function isLeapYear($year){
  return (cal_days_in_month(CAL_GREGORIAN, 2, $year) === 29) ? true : false;
}

		/*STRINGS*/	

function startsWith($haystack, $needle){
	return $needle === "" || strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle){
	return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}	

		/*SECURITY*/	

function antispamCrypt($textToCrypt){
	$result = "";
	for($i = 0; $i < strlen($textToCrypt); $i++){
		$rand = rand(0, 9);
		if($rand < 5){
			$arr = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
			$arrCrypt = array("&#97;", "&#98;", "&#99;", "&#100;", "&#101;", "&#102;", "&#103;", "&#104;", "&#105;", "&#106;", "&#107;", "&#108;", "&#109;", "&#110;", "&#111;", "&#112;", "&#113;", "&#114;", "&#115;", "&#116;", "&#117;", "&#118;", "&#119;", "&#120;", "&#121;", "&#122;", "&#123;", "&#124;", "&#125;", "&#126;");
			$index = array_search($textToCrypt[$i], $arr);
			if($index != FALSE){
				$res = $arrCrypt[$index];
			}
			else{
				$res = $textToCrypt[$i];
			}
			$result = $result . $res;
		}
		else{
			$result = $result . $textToCrypt[$i];
		}
	}
	return $result;
}

function antiInjectionCrypt($string){
	$string = htmlentities($string);
	// On regarde si le type de string est un nombre entier (int)
	if(ctype_digit($string)){
		$string = intval($string);

	}
	// Pour tous les autres types
	else{
		//$dbh = mysql_connect($GLOBALS['bdd_server'], $GLOBALS['bdd_lg'], $GLOBALS['bdd_pw']);
		//$string = mysql_real_escape_string($string); //mysql_real_escape_string is obsolete in php7. Use PDO->quote but different behaviour, handle it
		//$string = $GLOBALS['bdd']->quote($string);
		$string = addcslashes($string, '%_');
	}
	
	return $string;
}

function displaySecuredText($securedText){
	$result = $securedText;
	
	$corresponding = array(
		"&lt;h3&gt;" => "<h3>",
		"&lt;/h3&gt;" => "</h3>",
		"&lt;h4&gt;" => "<h4>",
		"&lt;/h4&gt;" => "</h4>",
		"&lt;h5&gt;" => "<h5>",
		"&lt;/h5&gt;" => "</h5>",
		"\'" => "'",
		"\r" => "<br/>",
		"\n" => "<br/>"
	);
	
	foreach($corresponding as $key => $element){
		//echo '<p>'.$key.'/'.$element.'</p>';
		$result = str_replace($key, $element, $result);
	}
	
	return $result;
}
?>