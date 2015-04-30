<?php
    require "../functions/dbConnection.php";
    session_start();
    if(!issset($_POST['name'])){
        echo "Error inserting to the database";
        
    } else{
        $dbConn = getConnection();
        
        
	    $imageType = exif_imagetype($_FILES['fileName']['tmp_name']); // 1, 2, 3 for gif, jpg or png respectively.
	
	    if($imageType != 1 ||  $imageType != 2 || $imageType !=3){
		    // delete image if it's not a picture media type. 
		    echo "Here";
		    unlink($_FILES['fileName']['tmp_name']);
	    } else{
            $path = "img/" . $_SESSION['username'];
	        if(!file_exists($path)){ // check whether the user's folder exists
		        mkdir($path);
	        }
            
	        $fileName = $_FILES['fileName']['name'];
	        move_uploaded_file($_FILES['fileName']['tmp_name'],   'img/' . $_SESSION['username'] . "/" . $fileName);
	
	    //  update database with the name of the file for the profile picture
		
    
            $sql   = "INSERT INTO Instafish (userId, time, date, fishType, comments, amount, latitude, longitude, profilePicture) VALUES (:userID, :time, :date, :fishType, :comments, :amount, :latitude, :longitude, :profilePicture)";
	        $namedParameters = array();
	        $namedParameters[":username"] = $_POST['userID'];
            $namedParameters[":time"] = $_POST['time'];
            $namedParameters[":time"] = $_POST['time'];
            $namedParameters[":time"] = $_POST['time'];
            $namedParameters[":time"] = $_POST['time'];
            $namedParameters[":time"] = $_POST['time'];
            
            
        
	
	        $stmt = $dbConn -> prepare($sql);
	        $stmt -> execute($namedParameters);
	        $result = $stmt->fetch();
    }
?>