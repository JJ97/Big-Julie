<?php
function skipReq($username){
	// get whether user is logged in
	$loggedIn = userLogged($username);	
	// if user logged in
	if ($loggedIn == TRUE){
		// if user not already skipped song
		if (getSkipped($username) == 0){
			// set user to have skipped song
			updateActive($username,10);
			setSkipped($username);
			return 1;
		}
		else {
			return ("already skipped song");
		}
	}
	else {
		 return ("not logged in");
	}	
}

function getSkipped($username){
	return query('SELECT skipped FROM User WHERE username = \''.mysql_real_escape_string($username).'\'','skipped');
}

function setSkipped($username){
	mysql_query('UPDATE User SET skipped = 1 WHERE username =\''.mysql_real_escape_string($username).'\'');
}

include 'main.php';
switch ($_POST['action']) {
	case "skipReq":
		connect();
		// reset active counter for user
		updateActive($_POST['PHPusername'],10);
		echo skipReq($_POST['PHPusername']);
		break;	
}
?>

