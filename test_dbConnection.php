<?php
	function getConnection(){
		$host = "localhost";
	    $dbname= "David"; // Your otter id
	    $username = "root"; // Your otter id
	    $password = "Cricket83";
	    $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	    $dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   
	   	return $dbConn;
	}
?>