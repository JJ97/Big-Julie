<?php
function validateSongReq($username,$currentTime,$URL){
	// get if user logged in
	$loggedIn = userLogged($username);	
		//if user logged in
		if( $loggedIn == 1){			
			// get time of last request by user
			$lastReqTime = getLastReqTime($username);
			// get number of active users
			$activeUsers = (getActiveUsers());
			// calculate cooldown time for request
			$reqCooldown = 20000*($activeUsers)^0.50;
			// if cooldown is over
			if (($currentTime - $lastReqTime) > $reqCooldown){	
				// if song not already requested
				if ((checkPresent($URL)) == 0 ){
					// signal to push request to database
					echo ("p".$reqCooldown);
				}
				// if song already requested
				else{
					echo "already requested";
				}				
			}
			// if cooldown still active
			else{
				echo ($reqCooldown);
			}
		}
		// if user not logged in
		else{ 
			echo "not logged in";
		}
}

function getLastReqTime($username){
	return(query('SELECT lastReqTime FROM User WHERE username = \''.mysql_real_escape_string($username).'\'','lastReqTime'));
}

function checkPresent($URL){
	return (query('SELECT COUNT(URL) AS present FROM Request WHERE URL = \''.mysql_real_escape_string($URL).'\'','present'));
}

function pushSongReq($URL,$name,$artist,$art,$length,$reqTime,$skipCount){	
	// prevents request for default song values
	if ($name != "Title"){
	mysql_query('INSERT INTO `Big_Julie`.`Request` (`URL`, `name`, `artist`, `art`, `length`, `reqTime`, `skipCount`) VALUES (\''.mysql_real_escape_string($URL).'\', \''.mysql_real_escape_string($name).'\', \''.mysql_real_escape_string($artist).'\', \''.mysql_real_escape_string($art).'\', \''.mysql_real_escape_string($length).'\', \''.mysql_real_escape_string($reqTime).'\', \''.mysql_real_escape_string($skipCount).'\')');
	mysql_query('INSERT INTO `Big_Julie`.`Vote` (`URL`, `username`) VALUES (\''.mysql_real_escape_string($URL).'\', \'julie\')');
	}
}

function updateLastReqTime($username,$currentTime){
	mysql_query('UPDATE User SET lastReqTime = \''.$currentTime.'\'WHERE username = \''.mysql_real_escape_string($username).'\'');
}

include 'main.php';
// user 'action' from login.js to branch to different logic
switch ($_POST['action']) {
	// validate login details, set user.loggedIn as 1 if validation passed
	case "validateSongReq":
		// establish connection with mySQL
		connect();
		// reset user active counter
		updateActive($_POST['PHPusername'],10);
		echo validateSongReq($_POST['PHPusername'],$_POST['PHPcurrentTime'],$_POST['PHPURL']);
	break;	
	case "pushSongReq":
		connect();
		// add song request to database
		pushSongReq($_POST['PHPURL'],$_POST['PHPname'],$_POST['PHPartist'],$_POST['PHPart'],$_POST['PHPlength'],$_POST['PHPreqTime'],$_POST['PHPskipCount']);	
		// update last request time of user to current time and reset active countdown
		updateLastReqTime($_POST['PHPusername'],$_POST['PHPreqTime']);			
	break;
}
?>