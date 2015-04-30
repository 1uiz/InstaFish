<?php

    session_start();
    include 'test_dbConnection.php';

    $dbConn = getConnection();

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent sql injection
    $sql = "SELECT * from `instaUsers` WHERE username = :username AND password = :password";
    $namedParameters = array();
    $namedParameters[':username'] = $username;
    $namedParameters[':password'] = hash("sha1", $password);

    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute($namedParameters); //We are expecting one record
    $result = $stmt -> fetch();
    

    if(empty($result)){
        $sql = "INSERT INTO log (userName, userId, isSuccesfullLogin) VALUES (:uName, :uId, :isSuccessful)";
        $namedParameters = array();
        $namedParameters[":uName"] = $result['username'];
        $namedParameters[":uId"] = $result['userID'];
        $namedParameters[":isSuccessful"] = 0;
        
        $stmt = $dbConn -> prepare($sql);
        $stmt -> execute($namedParameters);
        header("Location: login.html?error=WRONG USERNAME OR PASSWORD");
        
    }
    else{
        // insert log record to database
        $_SESSION['username'] = $result['username'];
        $_SESSION['adminName'] = $result['firstName'] . " " . $result['lastName'];
        

        $sql = "INSERT INTO log (userName, userId, isSuccesfullLogin) VALUES (:uName, :uId, :isSuccessful)";
        $namedParameters = array();
        $namedParameters[":uName"] = $result['username'];
        $namedParameters[":uId"] = $result['userID'];
        $namedParameters[":isSuccessful"] = 1;
        
        $stmt = $dbConn -> prepare($sql);
        $stmt -> execute($namedParameters);

        header("Location: fish_test.html");
    }

?>