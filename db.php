<?php
	error_reporting(E_ALL);
	ini_set("display_errors", "On");
	require "dbConnection.php";
	function getPlayer(){
		$dbConn = getConnection();
		$sql = "SELECT name, email FROM Player";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt -> fetchAll();
	}
?>

<!DOCTYPE html>
	<head>
		<title>Daniel</title>
		<meta charset="utf-8" />
	</head>
	<body>
		<h1>DB Records:</h1>
		<?php

			$players = getPlayer();
	
			foreach($players as $player){
				echo $player['name'] . " " . $player['email'];
			}
			
		?>
	</body>
</html>
