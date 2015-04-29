<?php
	function getConnection(){
		$host = "45.55.190.160";
   	 	$dbname = "test";
    	$username = "root";
    	$password = "rivka luis david daniel";
    	$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    	$dbConn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConn;
  	
	}
?> 