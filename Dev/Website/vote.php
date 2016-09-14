<?php
function getURL($offset){
	return query('SELECT URL FROM Request ORDER BY `Request`.`reqTime` ASC LIMIT 1 OFFSET '.($offset+1),'URL');
}

function voteReq($username,$offset){
	$URL = getURL($offset);
	pushVote($username,$URL);
	return 1;
}

function pushVote($username,$URL){
	mysql_query('INSERT INTO `Big_Julie`.`Vote`(`username`, `URL`) VALUES (\''.mysql_real_escape_string($username).'\', \''.mysql_real_escape_string($URL).'\')');
}

include 'main.php';
switch ($_POST['action']) {
	case "voteReq":
		connect();
		updateActive($_POST['PHPusername'],10);
		echo voteReq($_POST['PHPusername'],$_POST['PHPoffset']);
		break;
}
?>

