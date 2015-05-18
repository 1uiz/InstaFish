    <?php
    require "../functions/dbConnection.php";
    session_start();
    if(!isset($_POST['userID'])){
       
        echo "Error inserting to the database";
        
    }else if(isset($_POST['userID']) && isset($_POST['updatePin'])){
        
        // Update pin that belongs to a certain user.
        $imageType = exif_imagetype($_FILES['fileName']['tmp_name']);
        
        if($imageType != -1 && $imageType != 2 && $imageType != 3){
            echo json_encode(array("status" => "Cannot upload this file type"));
            unlink($_FILES['fileName']['tmp_name']);
        } else{
            $fileName = $_FILES['fileName']['name'];
            $pinID = $_POST['pinID'];
            
            move_uploaded_file($_FILES['fileName']['tmp_name'],   '../img/'. $_SESSION['
            '] . "/" . $fileName);
         
            $dbConn = getConnection();
	
	         //  update database with the name of the file for the profile picture
		
             
            $sql   = "UPDATE userData SET userID=" . $_POST['userID'] . ", time=" . $_POST['time'] . ", weight='" . $_POST['weight'] .  ", date='". $_POST['date'] . "', fishType='" . $_POST['fishType'] . "', amount=" . $_POST['amount'] . ", latitude=" . $_POST['userID'] . ", longitude=" . $_POST['userID'] .  ", comments='" . $_POST['comments'] . "', fishPicture='" . $fileName . "' WHERE pinID=" . $pinID;
	
	        $stmt = $dbConn -> prepare($sql);
	        $stmt -> execute();
	        echo json_encode(array("status" => "success!"));
            
        }
    }else if(isset($_POST['userID']) && isset($_POST['deletePin'])){
        $dbConn = getConnection();
        $sql = "DELETE FROM userData WHERE userID='" . $_POST['userID'] . "' AND pinId='" . $_POST['pinID'] . "'";
        $stmt = $dbConn -> prepare($sql);
        $stmt -> execute();
        echo json_encode(array("status" => "success!"));
    }

    else{
        $dbConn = getConnection();
        
        // let's match up names from post to here.
        var_dump($_POST);
        
        // TODO: Install exif module on production server
	    $imageType = exif_imagetype($_FILES['fileName']['tmp_name']); // 1, 2, 3 for gif, jpg or png respectively.
        
        echo $imageType;
	
	    if($imageType != 1 &&  $imageType != 2 && $imageType !=3){
		    // delete image if it's not a picture media type. 
		    echo json_encode(array("status" => "Cannot upload this file type"));
		    unlink($_FILES['fileName']['tmp_name']);
	    } else{
            
            $path = "../img/" . $_SESSION['username'];
	        if(!file_exists($path)){ // check whether the user's folder exists
		        mkdir($path);
	        }
            
	        $fileName = $_FILES['fileName']['name'];
            
            // check if the file exists already
            if(file_exists("img/" . $fileName)){
                echo json_encode(array("status" => "File exists"));
            } else{
	        move_uploaded_file($_FILES['fileName']['tmp_name'],   '../img/'. $_SESSION['username'] . "/" . $fileName);
         
	
	         //  update database with the name of the file for the profile picture
		
            $sql   = "INSERT INTO userData (userId, time, date, fishType, comments, amount, latitude, longitude, fishPicture, weight) VALUES (:userID, :time, :date, :fishType, :comments, :amount, :latitude, :longitude, :fishPicture, :weight)";

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
            $namedParameters[":weight"] = $_POST['weight'];

	
	        $stmt = $dbConn -> prepare($sql);
	        $stmt -> execute($namedParameters);
	        echo json_encode(array("status" => "success!"));
        }
      } 
    }
?>