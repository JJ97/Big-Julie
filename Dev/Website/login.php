<?php
include 'main.php';
// return whether username and password match in user database
function loginValid($username,$password) {
	return (query('SELECT COUNT(password) AS loginValid FROM User WHERE username = \''.mysql_real_escape_string($username).'\' AND password = \''.mysql_real_escape_string($password).'\'','loginValid'));
}
// set value of loggedIn in user database
function toggleLogged($username,$loggedIn){
	mysql_query('UPDATE User SET loggedIn = \''.($loggedIn).'\'WHERE username = \''.mysql_real_escape_string($username).'\'');
}
// use 'action' from login.js to branch to different logic
switch ($_POST['action']) {
	// login request
	case "login":
		// establish connection with mySQL
		connect();
		// validate login details
		$loginvalid = loginValid($_POST['PHPusername'],$_POST['PHPpassword']);
		// if validation passed
		if( $loginvalid == 1){
			// flag user as logged in and active
			toggleLogged($_POST['PHPusername'],1);
			updateActive($_POST['PHPusername'],10);
		}
		// return if user login is valid to login.js
		echo $loginvalid;
		break;
	// logout request
	case "logout":
		connect();
		// flag user as logged in
		toggleLogged($_POST['PHPusername'],0);
		break;
	}
?>