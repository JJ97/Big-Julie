<?php
function getURL($offset){
	return query('SELECT URL FROM Request ORDER BY `Request`.`reqTime` ASC LIMIT 1 OFFSET '.mysql_real_escape_string($offset),'URL');
}

function getLength($URL){
	return query('SELECT length FROM Request WHERE URL = \''.mysql_real_escape_string($URL).'\'','length');
}

function nextSong($URL,$time){
	mysql_query('UPDATE Request SET ReqTime = '.mysql_real_escape_string($time).' WHERE URL = \''.mysql_real_escape_string($URL).'\'');
}

function votes(){
	for ($i = 0; $i <=3 ; $i++) {
		$URL = getURL($i);
		mysql_query('UPDATE Request SET reqTime = reqTime - ((SELECT SUM(voteWeight) FROM User WHERE username IN ( SELECT username FROM Vote WHERE URL = \''.mysql_real_escape_string($URL).'\' ) )*60000) WHERE URL = \''.mysql_real_escape_string($URL).'\'');
		mysql_query('DELETE FROM Vote WHERE URL = \''.mysql_real_escape_string($URL).'\' AND username != \'julie\'');
	} 
}

function activeDec(){
	mysql_query('UPDATE User SET active = active - 1 WHERE active > 0');
}

function setSkipped(){
	mysql_query('UPDATE User SET skipped = 0');
}

function getSkipped($URL){
	if (getNewReq($URL) == 1){
		return "true";
	}
	// get number of votes to skip current song
	$skipCount = getSkipCount();
	//get number of active users
	$activeUsers = getActiveUsers();
	// if more that 60% of users have voted to skip
	if ($skipCount > ($activeUsers*0.6)){		
		return "true";
	}
	else{
		return "false";
	}
}

function getNewReq($URL){
	$newURL = getURL(0);
	if ($URL != $newURL){
		return 1;
	}
	else {
		return 0;
	}
}

function getSkipCount(){
	return  query('SELECT COUNT(skipped) AS skipCount FROM User WHERE skipped = 1','skipCount');
}

function dropSong($URL){
	mysql_query('DELETE FROM Request WHERE URL = \''.mysql_real_escape_string($URL).'\'');
}

include 'main.php';
switch ($_POST['action']) {
	case "getURL":
		// establish connection with mySQL
		connect();		
		// get URL of next song to play
		echo getURL(0);
		break;
	case "getLength":
		connect();	
		//get length of song
		echo getLength($_POST['PHPURL']);
		break;
	case "nextSong":
		connect();	
		// update request table since song has ended
		nextSong($_POST['PHPURL'],$_POST['PHPtime']);
		votes();
		// decrement active counter of every user
		activeDec();
		// set each user to have not voted to skip
		setSkipped();
		break;
	case "getSkipped":
		connect();		
		// get whether song has met all skip criteria 
		echo getSkipped($_POST['PHPURL']);
		break;
	case "dropSong":
		connect();		
		dropSong($_POST['PHPURL']);
		break;
}
?>

