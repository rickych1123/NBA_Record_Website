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

$gameDate = "";
while ($record = mysqli_fetch_array($myData)) {
	if ($gameDate != $record['GAME_DATE']) {
		if ($gameDate != "") {
			echo "</table>";
		}

		echo "<br/>";
		echo "<table class='table-responsive table-condensed table-bordered table-hover table-striped'>
                <tr>
                <th>" . $record['GAME_DATE'] . "</th>
                </tr>";
		echo "<tr>
                <th>AWAY</th>
                <th>HOME</th>
                <th>SCORE</th>
                <th>SCORE</th>
                <th>O/U</th>
              </tr>";
		$gameDate = $record['GAME_DATE'];
	}
	echo "<tr>";
	if ($record['AWAY_SCORE'] != 0 && $record['HOME_SCORE'] != 0) {
		//判斷讓分盤 主場過盤
		if ($record['HOME_SCORE'] + $record['HOME_TEAM_SPRD'] - $record['AWAY_SCORE'] > 0) {
			echo "<td class='col-md-3' title='最近戰績： " . $record['AWAY_TEAM_TOTAL_STREAK'] . "\n例行賽戰績： " . $record['AWAY_SEASON_RECORD'] . "'>" . $record['AWAY_TEAM_NAME'] . " (" . $record['AWAY_TEAM_SPRD'] . ")" . "</td>";
			echo "<td class='col-md-3 bg-success'title='最近戰績： " . $record['HOME_TEAM_TOTAL_STREAK'] . "\n例行賽戰績： " . $record['HOME_SEASON_RECORD'] . "'>" . $record['HOME_TEAM_NAME'] . " (" . $record['HOME_TEAM_SPRD'] . ")" . "</td>";
		}
		//客場過盤
		else {
			echo "<td class='col-md-3 bg-success'title='最近戰績： " . $record['AWAY_TEAM_TOTAL_STREAK'] . "\n例行賽戰績： " . $record['AWAY_SEASON_RECORD'] . "'>" . $record['AWAY_TEAM_NAME'] . " (" . $record['AWAY_TEAM_SPRD'] . ")" . "</td>";
			echo "<td class='col-md-3'title='最近戰績： " . $record['HOME_TEAM_TOTAL_STREAK'] . "\n例行賽戰績： " . $record['HOME_SEASON_RECORD'] . "'>" . $record['HOME_TEAM_NAME'] . " (" . $record['HOME_TEAM_SPRD'] . ")" . "</td>";
		}
		//判斷賽果 客場勝
		if ($record['AWAY_SCORE'] > $record['HOME_SCORE']) {
			echo "<td class='col-md-1 text-danger'> <strong>" . $record['AWAY_SCORE'] . "<strong>" . "</td>";
			echo "<td class='col-md-1'>" . $record['HOME_SCORE'] . "</td>";
		}
		//主場勝
		else {
			echo "<td class='col-md-1'>" . $record['AWAY_SCORE'] . "</td>";
			echo "<td class='col-md-1 text-danger'> <strong>" . $record['HOME_SCORE'] . "</strong>" . "</td>";
		}
	} else {
		echo "<td class='col-md-3' title='最近戰績： " . $record['AWAY_TEAM_TOTAL_STREAK'] . "\n例行賽戰績： " . $record['AWAY_SEASON_RECORD'] . "'>" . $record['AWAY_TEAM_NAME'] . "(" . $record['AWAY_TEAM_SPRD'] . ")" . "</td>";
		echo "<td class='col-md-3' title='最近戰績： " . $record['HOME_TEAM_TOTAL_STREAK'] . "\n例行賽戰績： " . $record['HOME_SEASON_RECORD'] . "'>" . $record['HOME_TEAM_NAME'] . "(" . $record['HOME_TEAM_SPRD'] . ")" . "</td>";
		echo "<td class='col-md-1'>" . $record['AWAY_SCORE'] . "</td>";
		echo "<td class='col-md-1'>" . $record['HOME_SCORE'] . "</td>";
	}
	//判斷大小分盤
	$scoreTotal = $record['AWAY_SCORE'] + $record['HOME_SCORE'];
	if ($record['AWAY_SCORE'] + $record['HOME_SCORE'] > $record['OVER_UNDER']) {
		echo "<td class='col-md-1 bg-danger'>" . $record['OVER_UNDER'] . " (" . $scoreTotal . ")" . "</td>";
	} else {
		echo "<td class='col-md-1'>" . $record['OVER_UNDER'] . " (" . $scoreTotal . ")" . "</td>";
	}

	echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>