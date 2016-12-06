<?php

//setting header to json
header('Content-Type: application/json');
//header("Content-Type:text/html; charset=utf-8");
//database
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'nba_game_data');

//get connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
mysqli_query($mysqli, "SET names utf8");
mb_language('uni');
mb_internal_encoding('UTF-8');
if (!$mysqli) {
	die("Connection failed: " . $mysqli->error);
}
$now = new DateTime();
//query to get data from the table
$query = sprintf("SELECT away_score, home_score, home_team_name, away_team_name, home_team_sprd, away_team_sprd, game_date FROM game WHERE away_team_name = '邁阿密熱火' || home_team_name='邁阿密熱火' ORDER BY game_date LIMIT 10");

//execute query
$result = $mysqli->query($query);

//loop through the returned data
$data = array();
foreach ($result as $row) {
	$data[] = $row;
}

//free memory associated with result
$result->close();

//close connection
$mysqli->close();

//now print the data
print json_encode($data);
?>