<?php
$q = intval($_GET['q']);
$dtz = new DateTimeZone("Asia/Taipei"); //Your timezone
$now = new DateTime();
$startDate = new DateTime();
if ($q == 1) {
	//today
} elseif ($q == 2) {
	$startDate->modify('-7 days');
} elseif ($q == 3) {
	$startDate->modify('-30 day');
} elseif ($q == 4) {
	$startDate = $startDate->createFromFormat('Y-m-d', '2016-11-11');
}

$con = mysqli_connect("localhost", "root", "", "nba_game_data");
mysqli_query($con, "SET character_set_results=utf8");
mb_language('uni');
mb_internal_encoding('UTF-8');

if (!$con) {
	die("Can not connect:" . mysqli_error());
}
mysqli_query($con, "set names 'utf8'");
$sql = "SELECT * FROM game WHERE GAME_DATE >= '" . $startDate->format('Y-m-d') . "' && GAME_DATE <= '" . $now->format('Y-m-d') . "' ORDER BY game.GAME_DATE DESC, game.AWAY_TEAM_NAME DESC";
$myData = mysqli_query($con, $sql);

$month = "";
$gameDate = "";
while ($record = mysqli_fetch_array($myData)) {
	$monthTemp = date("m", strtotime($record['GAME_DATE']));
	if ($month != $monthTemp) {
		if ($month != "") {
			echo "</ul>";
			echo "</li>";
		}
		echo "<li>";
		echo "<a href='#Group" . $monthTemp . "'> " . $monthTemp . "月</a>";
		echo "<ul class='nav nav-stacked'>";
	}
	if ($gameDate != $record['GAME_DATE']) {
		echo "<li><a href='#Group" . $record['GAME_DATE'] . "'>" . $record['GAME_DATE'] . "</a></li>";
	}
	$month = date("m", strtotime($record['GAME_DATE']));
	$gameDate = $record['GAME_DATE'];
}
echo "</ul>";
echo "</li>";
?>