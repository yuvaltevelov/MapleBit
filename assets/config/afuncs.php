<?php 
if(basename($_SERVER["PHP_SELF"]) == "afuncs.php"){
	die("403 - Access Forbidden");
}

include_once('database.php');
/* Logged in time Handler - Do not touch unless you know what you're doing */
if(isset($_SESSION['id'])){
	global $mysqli, $prefix;
	$logouttime = 300;
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$query = $mysqli->query("UPDATE `accounts` SET `sitelogged` = '".$timenow."' WHERE `id`='".$_SESSION['id']."'") or die(mysql_error());
	$retrieve = $mysqli->query("SELECT * FROM `accounts` WHERE `sitelogged` >= '".$loggedtime."'") or die(mysql_error());
	$online = $retrieve->fetch_assoc();
}

# If logged in, fetch IP
if(isset($_SESSION['id'])){
	global $mysqli, $prefix;
	$IP = $_SERVER['REMOTE_ADDR'];
	$sesid = $_SESSION['id'];
	$getn = $mysqli->query("SELECT * FROM accounts WHERE id=$sesid");
	$getn2 = $getn->fetch_assoc();
	$getname = $getn2['name'];
	$mysqli->query("UPDATE accounts SET ip='$IP' WHERE name='$getname'") or die();
	$q = $mysqli->query("SELECT * FROM accounts WHERE ip='$IP'");
	$get = $q->fetch_assoc();
	$id = $get['name'];
}

/* Functions for Cype */

function getOnline(){
	global $mysqli, $prefix;
	$logouttime = 300;
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$a = $mysqli->query("SELECT * FROM `accounts` WHERE `sitelogged` >= '".$loggedtime."'") or die();
	$b = $a->num_rows;
	return $b;
}
function onlineCheck($string){
	global $mysqli, $prefix;
	$logouttime = 300;
	$timenow = time();
	$loggedtime = $timenow - $logouttime;
	$a = $mysqli->query("SELECT * FROM `accounts` WHERE `sitelogged` >= '".$loggedtime."' AND `id`='".$string."'") or die();
	$b = $a->fetch_assoc();
	if($b['sitelogged'] >= $loggedtime){
		$check = "<img src=\"assets/img/online.png\" alt=\"online\" />";
	}else{
		$check = "<img src=\"assets/img/offline.png\" alt=\"offline\" />";
	}
	return $check;
}
function isProfile( $type, $string ){
	global $mysqli, $prefix;
	if ($type == 'charname') {
		$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
		$b = $a->fetch_assoc();
		$c = $b['accountid'];
		$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
		$e = $d->num_rows;
		if ($e > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else if ($type == 'charid') {
		$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
		$b = $a->fetch_assoc();
		$c = $b['accountid'];
		$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
		$e = $d->num_rows;
		if ($e > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else if ($type == 'accname') {
		$a = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$string."'") or die();
		$b = $a->fetch_assoc();
		$c = $b['id'];
		$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
		$e = $d->num_rows;
		if ($e > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else if ($type == 'accid') {
		$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$string."'") or die();
		$b = $a->num_rows;
		if ($a > 0) {
			return 1;
		} else {
			return 0;
		}
	}
	else {
		$a = "Cype Error: Parameters for isProfile() are incorrect";
		return $a;
	}
}
function getInfo( $type, $string, $how ){
	global $mysqli, $prefix;
	if ($type == 'charname') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'accid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'accname') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'accid') {
			if ($string = '".$prefix."session') {
				$a = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$_SESSION['id']."'") or die();
				$b = $a->fetch_assoc();
				return $b['name'];
			} else {
				$a = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$string."'") or die();
				$b = $a->fetch_assoc();
				return $b['name'];
			}
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'charid') {
		if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		else if ($how == 'accid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['id'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'accid') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else if ($how == 'accname') {
			$a = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['id'];
		}
		else if ($how == 'profilename') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['accountid'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else if ($type == 'profilename') {
		if ($how == 'charid') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'charname') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'accname') {
			$a = $mysqli->query("SELECT * FROM `accounts` WHERE `name`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['id'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else if ($how == 'accname') {
			$a = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$string."'") or die();
			$b = $a->fetch_assoc();
			return $b['name'];
		}
		else if ($how == 'rank') {
			$a = $mysqli->query("SELECT * FROM `characters` WHERE `rank`='".$string."'") or die();
			$b = $a->fetch_assoc();
			$c = $b['accountid'];
			$d = $mysqli->query("SELECT * FROM `".$prefix."profile` WHERE `accountid`='".$c."'") or die();
			$e = $d->fetch_assoc();
			return $e['name'];
		}
		else {
			$a = "Cype Error: Parameters for getInfo() are incorrect";
			return $a;
		}
	}
	else {
		$a = "Cype Error: Parameters for getInfo() are incorrect";
		return $a;
	}
}
function showBirth( $type ) {
	if ($type == 'month') {
		echo "
			<select name=\"month\" class=\"input-small\">
				<option value=\"1\">
					January
				</option>	
				<option value=\"2\">
					February
				</option>	
				<option value=\"3\">
					March
				</option>	
				<option value=\"4\">
					April
				</option>	
				<option value=\"5\">
					May
				</option>	
				<option value=\"6\">
					June
				</option>	
				<option value=\"7\">
					July
				</option>
				<option value=\"8\">
					August
				</option>
				<option value=\"9\">
					September
				</option>
				<option value=\"10\">
					October
				</option>
				<option value=\"11\">
					November
				</option>
				<option value=\"12\">
					December
				</option>
			</select>
	";
	}
	if ($type == 'day') {
		echo "<select name=\"day\" class=\"input-mini\">";
		$maxdy = 31;
		for ($i = 1; $i <= $maxdy; $i++)
		{
			echo "<option value=\"$i\">$i</option>";
		}
		echo "</select>";
	}
	if ($type == 'year') {
		echo "&nbsp;<select name=\"year\" class=\"input-small\">";
		
		for ($i = date('Y'); $i >= 1970; $i--)
		{
			echo "<option value=\"$i\">$i</option>";
		}	 
		
		echo "</select><br/>";
	}
}
function shortTitle($title){
	$maxlength = 30;
	$title = $title." ";
	$title = substr($title, 0, $maxlength);
	$title = substr($title, 0, strrpos($title,' '));
	$title = $title."...";
	return $title;
}
function mysql_escape($string) {
	global $mysqli, $prefix;
	$string = $mysqli->real_escape_string($string);
	return $string;
}
function sql_sanitize( $sCode ) {
	global $mysqli, $prefix;
	$escapedCode = $mysqli->real_escape_string( $sCode );
	$sCode = preg_replace("/[^a-zA-Z0-9]+/", "", $escapedCode);	
	return $sCode;							
}
function sanitize_space($string) {
	global $mysqli, $prefix;
	$string = $mysqli->real_escape_string($string);
	return preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
}

function unSolved($type){
	global $mysqli, $prefix;
	if($type == "ticket"){
		$GrabTickets = $mysqli->query("SELECT * FROM ".$prefix."tickets WHERE status = 1");
		$counttick = $GrabTickets->num_rows;
		if($counttick == 1){
			$tickquant = "is";
			$tickplural = "";
		}else{
			$tickquant = "are";
			$tickplural = "s";
		}
		return "There ".$tickquant." <a href=\"?cype=admin&amp;page=ticket\"><u><b>".$counttick." unsolved ticket".$tickplural."</b></u></a>.";
	}
	elseif($type == "mail"){
		$GrabReportedpm = $mysqli->query("SELECT * FROM `".$prefix."mail` WHERE `status` = '10'");
		$countpm = $GrabReportedpm->num_rows;
		if($countpm == 1){
			$pmquant = "is";
			$pmplural = "";
		}else{
			$pmquant = "are";
			$pmplural = "'s";
		}
		return "There ".$pmquant." <a href=\"?cype=admin&page=mailreport&s=10\"><u><b> ".$countpm." reported PM".$pmplural."</b></u></a>.";
	}
}

//This function is for the "BuyNX" page in the UCP
function buyNX($char, $info, $pack){
	global $mysqli, $prefix;
	//If the character is not yet selected to pay for NX
	if($char == "" && $info == ""){
		$getchars = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$_SESSION['id']."'") or die();
		if($numchars = $getchars->num_rows <= 0){
			return "You have not created any characters.";
		}
		else{
			while($chars = $getchars->fetch_assoc()){
				echo "
					<tr>
						<td class='regtext'>
							- <a href=\"?cype=ucp&amp;page=buynx&c=".$chars['id']."\">".$chars['name']."</a>
						</td>
					</tr>
				";
			}
		}
	}
	//If the value comes as a real number, it goes here
	elseif($char && $info == ""){
		$character = $mysqli->real_escape_string($_GET['c']);
		$checkid = $mysqli->query("SELECT * FROM `characters` WHERE `accountid`='".$_SESSION['id']."' AND `id`='".$character."'") or die();
		$c = $checkid->fetch_assoc();
			//Check to see if the Character ID is the same as the id of your account's character ID
		if($character == $c['id']){
			return $character = "yes";
		}
		else{
			return $character = "no";
		}
	}
	elseif($char == "info"){
		$character = $mysqli->real_escape_string($_GET['c']);
		$mesos = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$character."'") or die();
		$rmesos = $mesos->fetch_assoc();
		$getnx = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$rmesos['accountid']."'") or die();
		$rnx = $getnx->fetch_assoc();
		if($info == "char"){
			//return the characters name
			return $rmesos['name'];
		}
		elseif($info == "meso"){
			//return the amount of Mesos
			return number_format($rmesos['meso']);
		}
		elseif($info == "nx"){
			//return the amount of NX
			return number_format($rnx['paypalNX']);
		}
	}
	
	//When a Package is selected, this will activate
	
	elseif($char && $info == "package"){
	
		$m = $mysqli->query("SELECT * FROM `characters` WHERE `id`='".$char."'");
		$rm = $m->fetch_assoc();
		$nx = $mysqli->query("SELECT * FROM `accounts` WHERE `id`='".$rm['accountid']."'") or die();
		$rx = $nx->fetch_assoc();
		$package = $mysqli->real_escape_string($_POST['nx']);
		
		//If the the user is logged in, It will execute this.
		if($rx['loggedin'] > 0){
			return "You cannot continue because you are already logged on to the game. Please log off and try again.";
		}
		//This is what package the user has selected
		elseif($pack == "1"){
			if($rm['meso'] > $pack1){
				$NXreset = $mysqli->query("UPDATE `accounts` SET `paypalNX`= paypalNX + 5000 WHERE `id`='".$rm['accountid']."'") or die();
				$MesoReset = $mysqli->query("UPDATE `characters` SET `meso`= meso - '".$pack1."' WHERE `id`='".$char."'") or die();
				return "Transaction complete! You can now spend your NX in the Cash Shop.";
			}else{
				return "You do not have enough mesos for this package.";
			}
		}elseif($pack == "2"){
			if($rm['meso'] > $pack2){
				$NXreset = $mysqli->query("UPDATE `accounts` SET `paypalNX`= paypalNX + 10000 WHERE `id`='".$rm['accountid']."'") or die();
				$MesoReset = $mysqli->query("UPDATE `characters` SET `meso`= meso - '".$pack2."' WHERE `id`='".$char."'") or die();
				echo "Transaction complete! You can now spend your NX in the Cash Shop.";
			}else{
				return "You do not have enough mesos for this package.";
			}
		}elseif($pack == "3"){
			if($rm['meso'] > $pack3){
				$NXreset = $mysqli->query("UPDATE `accounts` SET `paypalNX`= paypalNX + 30000 WHERE `id`='".$rm['accountid']."'") or die();
				$MesoReset = $mysqli->query("UPDATE `characters` SET `meso`= meso - '".$pack3."' WHERE `id`='".$char."'") or die();
				echo "Transaction complete! You can now spend your NX in the Cash Shop.";
			}else{
				return "You do not have enough mesos for this package.";
			}
		}else{
			return "An error has occured! Please try again.";
		}
	}
}

function mailStats($s) {
	global $mysqli, $prefix;
	if($s == 4) {
		$show = "from";
	} else {
		$show = "to";
	}
	if($s != 1){
		$mailCount = $mysqli->query("SELECT * FROM `".$prefix."mail` WHERE `".$show."`='".$_SESSION['pname']."' AND `status`='".$s."'");
	} else{
		$mailCount = $mysqli->query("SELECT * FROM `".$prefix."mail` WHERE `".$show."`='".$_SESSION['pname']."'");
	}
	echo $mailCount->num_rows;
}
function getNav() {
	global $mysqli, $prefix;
	$query = $mysqli->query("SELECT nav FROM ".$prefix."properties");
	$navtype = $query->fetch_assoc();
	$nav = "";
		if ($navtype['nav'] == "0"){
			$nav = "navbar navbar-default";
		}
		if ($navtype['nav'] == "1"){
			$nav = "navbar navbar-default navbar-inverse";
		}
	return $nav;
}

function countOnline() {
	global $mysqli, $prefix;
	$conline = $mysqli->query("SELECT * FROM accounts where loggedin = 2");
	return intval($conline);
}
function bbcodeParser($bbcode){
/*
Commands include
* bold
* italics
* underline
* typewriter text
* strikethough
* images
* urls
* quotations
* code (pre)
* colour
* size
*/

/* Matching codes */
$urlmatch = "([a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+)";

/* Basically remove HTML tag's functionality */
$bbcode = htmlspecialchars($bbcode);

/* Replace "special character" with it's unicode equivilant */
$match["special"] = "/\?/s";
$replace["special"] = '&amp;#65533;';

/* Bold text */
$match["b"] = "/\[b\](.*?)\[\/b\]/is";
$replace["b"] = "<b>$1</b>";

/* Italics */
$match["i"] = "/\[i\](.*?)\[\/i\]/is";
$replace["i"] = "<i>$1</i>";

/* Underline */
$match["u"] = "/\[u\](.*?)\[\/u\]/is";
$replace["u"] = "<span style=\"text-decoration: underline\">$1</span>";

/* Typewriter text */
$match["tt"] = "/\[tt\](.*?)\[\/tt\]/is";
$replace["tt"] = "<span style=\"font-family:monospace;\">$1</span>";

$match["ttext"] = "/\[ttext\](.*?)\[\/ttext\]/is";
$replace["ttext"] = "<span style=\"font-family:monospace;\">$1</span>";

/* Strikethrough text */
$match["s"] = "/\[s\](.*?)\[\/s\]/is";
$replace["s"] = "<span style=\"text-decoration: line-through;\">$1</span>";

/* Color (or Colour) */
$match["color"] = "/\[color=([a-zA-Z]+|#[a-fA-F0-9]{3}[a-fA-F0-9]{0,3})\](.*?)\[\/color\]/is";
$replace["color"] = "<span style=\"color: $1\">$2</span>";

$match["colour"] = "/\[colour=([a-zA-Z]+|#[a-fA-F0-9]{3}[a-fA-F0-9]{0,3})\](.*?)\[\/colour\]/is";
$replace["colour"] = $replace["color"];

/* Size */
$match["size"] = "/\[size=([0-9]+(%|px|em)?)\](.*?)\[\/size\]/is";
$replace["size"] = "<span style=\"font-size: $1;\">$3</span>";

/* Images */
$match["img"] = "/\[img\]".$urlmatch."\[\/img\]/is";
$replace["img"] = "<img src=\"$1\" />";

/* Links */
$match["url"] = "/\[url=".$urlmatch."\](.*?)\[\/url\]/is";
$replace["url"] = "<a href=\"$1\">$2</a>";

$match["surl"] = "/\[url\]".$urlmatch."\[\/url\]/is";
$replace["surl"] = "<a href=\"$1\">$1</a>";

/* Quotes */
$match["quote"] = "/\[quote\](.*?)\[\/quote\]/ism";
$replace["quote"] = "<div class=\"bbcode-quote\">?$1?</div>";

$match["quote"] = "/\[quote=(.*?)\](.*?)\[\/quote\]/ism";
$replace["quote"] = "<div class=\"bbcode-quote\"><span class=\"bbcode-quote-user\" style=\"font-weight:bold;\">$1 said:</span><br />?$2?</div>";

/* Parse */
$bbcode = preg_replace($match, $replace, $bbcode);

/* New line to <br> tag */
$bbcode=nl2br($bbcode);

/* Code blocks - Need to specially remove breaks */
function pre_special($matches)
{
	$prep = preg_replace("/\<br \/\>/","",$matches[1]);
	return "?<pre>$prep</pre>?";
}
$bbcode = preg_replace_callback("/\[code\](.*?)\[\/code\]/ism","pre_special",$bbcode);


/* Remove <br> tags before quotes and code blocks */
$bbcode=str_replace("?<br />","",$bbcode);
$bbcode=str_replace("?","",$bbcode); //Clean up any special characters that got misplaced...

/* Return parsed contents */
return $bbcode;
}

function getRealIpAddr() //for Registration
{
  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  //check ip from share internet
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  //to check ip is pass from proxy
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}
function redirect_wait5($url) {
	echo '<meta http-equiv="refresh" content="5;url='.$url.'" />';
	exit;
}

function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=identicon&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}
?>
