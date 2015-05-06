<?php
    require "../functions/dbConnection.php";

    // Our db will only
   //  respond if the post request comes with a userID.
    // Return a query 
    if(isset($_POST['userID']) && isset($_POST['thisUser'])){
        $dbConn = getConnection();
        
        $sql = "SELECT * FROM userData WHERE userId=" . $_POST['userID'];
        $stmt = $dbConn -> prepare($sql);
        $stmt -> execute();
        $result = $stmt -> fetchAll();
        
        echo json_encode($result);
    }
    else if(isset($_POST['userID'])){
        $dbConn = getConnection();
    
        $sql = "SELECT * FROM userData";
        $stmt = $dbConn -> prepare($sql);
        $stmt -> execute();
        $result = $stmt -> fetchAll();

        echo json_encode($result);
    } else{
        echo "404. Page not found.";
    }
    
?>