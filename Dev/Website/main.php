<?php
// establishes connection with mySQL server
function connect(){
  $con = mysql_connect('censored','censored','censored');
  mysql_select_db('Big_Julie', $con);
}
// generalised mySQL query function, unwraps data from mySQL array before returning
  function query($query,$returnAs) {
  return (mysql_fetch_array(mysql_query($query))[$returnAs]);
}
// update active counter of specific user
function updateActive($username,$active){
	mysql_query('UPDATE User SET active = '.$active.' WHERE username = \''.mysql_real_escape_string($username).'\'');
}
// return loggedIn flag of specific user
function userLogged($username){
	return(query('SELECT loggedIn FROM User WHERE username = \''.mysql_real_escape_string($username).'\'','loggedIn'));
}
// return number of active users
function getActiveUsers(){
	return (query('SELECT COUNT(active) AS activeUsers FROM User WHERE active > 0','activeUsers'));
}
?> 