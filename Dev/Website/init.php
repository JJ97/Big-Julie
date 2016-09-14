<?php

function getNextSongCover($index){
	return query('SELECT art FROM Request ORDER BY `Request`.`reqTime` ASC LIMIT 1 OFFSET '.mysql_real_escape_string($index),'art');
}

function getNextSongName($index){
	return query('SELECT name FROM Request ORDER BY `Request`.`reqTime` ASC LIMIT 1 OFFSET '.mysql_real_escape_string($index),'name');
}

include 'main.php';
// user 'action' from login.js to branch to different logic
switch ($_POST['action']) {
	// validate login details, set user.loggedIn as 1 if validation passed
	case "getNextSongCover":
		// establish connection with mySQL
		connect();
		echo getNextSongCover($_POST['PHPindex']);
	break;		
	case "getNextSongName":
		connect();
		echo getNextSongName($_POST['PHPindex']);
	break;
}
?>
