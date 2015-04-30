<?php
    require "../functions/dbConnection.php";
    session_start();
    if(!isset($_POST['userID'])){
        
        echo "Error inserting to the database";
        
    } else{
        $dbConn = getConnection();
        
        
        // TODO: Install exif module on production server
	    $imageType = exif_imagetype($_FILES['fileName']['tmp_name']); // 1, 2, 3 for gif, jpg or png respectively.
        
        echo $imageType;
	
	    if($imageType != 1 &&  $imageType != 2 && $imageType !=3){
		    // delete image if it's not a picture media type. 
		    echo json_encode(array("status" => "Cannot upload this file type"));
		    unlink($_FILES['fileName']['tmp_name']);
	    } else{
            
            $path = "img/" . $_SESSION['username'];
	        if(!file_exists($path)){ // check whether the user's folder exists
		        mkdir($path);
	        }
            
	        $fileName = $_FILES['fileName']['name'];
            
            // check if the file exists already
            if(file_exists("img/" . $fileName)){
                echo json_encode(array("status" => "File exists"));
            } else{
	        move_uploaded_file($_FILES['fileName']['tmp_name'],   'img/'. $_SESSION['username'] . "/" . $fileName);
         
	
	         //  update database with the name of the file for the profile picture
		
            $sql   = "INSERT INTO userData (userId, time, date, fishType, comments, amount, latitude, longitude, fishPicture) VALUES (:userID, :time, :date, :fishType, :comments, :amount, :latitude, :longitude, :fishPicture)";
	        $namedParameters = array();
	        $namedParameters[":userID"] = $_POST['userID'];
            $namedParameters[":time"] = $_POST['time'];
            $namedParameters[":date"] = $_POST['date'];
            $namedParameters[":fishType"] = $_POST['fishType'];
            $namedParameters[":comments"] = $_POST['comments'];
            $namedParameters[":amount"] = $_POST['amount'];
            $namedParameters[":latitude"] = $_POST['latitude'];
            $namedParameters[":longitude"] = $_POST['longitude'];
            $namedParameters[":fishPicture"] = $fileName;
            
	
	        $stmt = $dbConn -> prepare($sql);
	        $stmt -> execute($namedParameters);
	        echo json_encode(array("status" => "success!"));
        }
      } 
    }
?>