<?php
session_start();
require 'test_dbConnection.php';

$dbConn = getConnection();

$username = $_POST['username'];
$password = sha1($_POST['password']);

$sql = "SELECT * FROM instaUsers WHERE username = :username AND password = :password";
$namedParameters = array();
$namedParameters[':username'] = $username;
$namedParameters[':password'] = $password;
$stmt = $dbConn -> prepare($sql);
$stmt->execute($namedParameters);
$result = $stmt->fetch(); //We are expecting one record

if (empty($result)) {

  echo"Wrong username or password";
    header("Location: fish_test_login.php?error=WRONG USERNAME OR PASSWORD");


} else {

    $_SESSION['username']  = $result['username'];
    $_SESSION['adminName'] = $result['firstName'] . " " . $result['lastName'];
    header("Location: fish_test.html");

}



?>
