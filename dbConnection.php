<?php
	function getConnection(){
		$host = "localhost";
		$dbname = "test";
		$username = "dbUser";
		$password = "test";
		$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    	$dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConn;
	}
?>
