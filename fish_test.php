<?php

    session_start();

    if (!isset($_SESSION['username'])){  //validates whether user has logged in
        header("Location: login.html");
    }

?>

<html>
<head>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<link href="css/elements.css" rel="stylesheet">

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var geocoder = new google.maps.Geocoder();
var latitude = 0.0;
var longitude = 0.0;
var average = 0.0;
var total = 0.0;
function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address);
    } else {
      updateMarkerAddress('Cannot determine address at this location.');
    }
  });
}

function updateMarkerStatus(str) {
  document.getElementById('markerStatus').innerHTML = str;
}
    


function updateMarkerPosition(latLng) {
  document.getElementById('info').innerHTML = [
    latLng.lat(),
    latLng.lng()
  ].join(', ');
}

function updateMarkerAddress(str) {
  document.getElementById('address').innerHTML = str;
}
    
function getLatitude(latLng){
    return latLng.lat();
}
    
function getLongitude(latLng){
    return latLng.lng();
}
   
function getAverage(){
    console.log("getAverage");
    $.ajax({
        type: "post",
        url: "http://gallery-armani.codio.io:3000/Instafish/endpoints/retrieveRecords.php",
        dataType: "json",
        data: {"userID": userID, "thisUserAverage": "1"},
        success: function(data, status){
            console.log("getAverage is successful");
            if(data[0]["avg"] == null){
                average = 0.0;
            } else{
                
                average = data[0]["avg"];
            }
            $("#stats").html("Average of your catches: " + average + "<br />Total number of catches: " + total);
            
            // Display average on div
        },
        complete: function(data, status){
            console.log("getAverage complete");
        }
        
    });
}    

function getUserPins(){
    console.log("getUserPins");
    $.ajax({
        type: "post",
         url: "http://gallery-armani.codio.io:3000/Instafish/endpoints/retrieveRecords.php",
         dataType: "json",
         data: {"userID": userID, "thisUser": "1"},
         success: function(data, status){
		     console.log("get user pins success");
             console.log("Size: " + data.length);
             total = data.length;
             for(var x = 0; x < data.length; x++){
               var pinID = data[x]['pinId'];
             	var location = new google.maps.LatLng(data[x]['latitude'], data[x]['longitude']);
             	var comment = data[x]['comments'];
            	var date = data[x]['date'];
            	var fishType = data[x]['fishType'];
            	var amount = data[x]['amount'];
            	var picture = data[x]['fishPicture'];
             	var picturePath = "img/" + username + "/" + picture;
                var weight = data[x]['weight'];
                 console.log("Here: " + picturePath);
             	var infoWindowContent = [
		        ['<div class="info_content">' +
		        '<h3>' + comment + '</h3>' + '<p hidden>' + pinID + "</p>" + 
		        '<p><strong>Date:</strong> '+ date + ' <br/><strong>Weight:</strong> ' + weight + ' <br/><strong>Type of fish:</strong> ' + fishType + ' <br/><strong>Amount caught:</strong> ' + amount + '</p>' +
		        '<img src=' + picturePath + ' height=125px width=100px/>'+'</div>']
		    ];
             	addUserMarkers(map, infoWindowContent[0][0], location, pinID);
			  	}
         },
         
         complete: function(data, status){
              console.log("getUserPins failure") 
              console.log(data);
              console.log(status);
              //alert(status);
             //$("#test").html(data, status);
         }
    });
}
    
function deleteUserPin(userID, userPin){
    console.log("Delete user pins");
    $.ajax({
        type: "post",
        url: "http://gallery-armani.codio.io:3000/Instafish/endpoints/insertRecords.php",
        dataType: "json",
        data: {"userID": userID, "pinID" : userPin, "deletePin": 1},
        success: function(data, status){
            console.log("delete success");
            for(var x = 0; x < userMarkers.length; x++){
                if(userMarkers[x]['ID'] == userPin){
                    console.log("Found markers");
                    userMarkers[x].setMap(null);
                }
            }
        },
        complete: function(data, status){
            setTimeout(function(){getUserPins()},3000);
        }
            
    });
}

function initialize() {
    
    userID = <?=$_SESSION['userID']?>;
    username = "<?=$_SESSION['username']?>";
    var centerLatLng = new google.maps.LatLng(36.600344, -121.787797);
    latitude = 36.600344;
    longitude = -121.787797;
    var pinMessage = "Drag and pin me!";
    userMarkers = [];
    
    
    var infoWindow = new google.maps.InfoWindow({
        content: pinMessage
    })
    
    map = new google.maps.Map(document.getElementById('mapCanvas'), {
			center: centerLatLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoom: 10
		});
    
    var dragMarker = new google.maps.Marker({
        position: centerLatLng,
        title: "Pin me!",
        map: map,
        draggable: true
        
    });
    
    infoWindow.open(map, dragMarker);
    
    //events 
    google.maps.event.addListener(dragMarker, 'dragend', function(){
        latitude = getLatitude(dragMarker.getPosition());
        longitude = getLongitude(dragMarker.getPosition());
        console.log(getLatitude(dragMarker.getPosition()));
        console.log(getLongitude(dragMarker.getPosition()));
    })
    
    getUserPins();
    
     $.ajax({
         type: "post",
         url: "http://gallery-armani.codio.io:3000/Instafish/endpoints/retrieveRecords.php",
         dataType: "json",
         data: {"userID": userID},
         success: function(data, status){
  			
             for(var x = 0; x < data.length; x++){
             	var location = new google.maps.LatLng(data[x]['latitude'], data[x]['longitude']);
             	var comment = data[x]['comments'];
            	var date = data[x]['date'];
            	var fishType = data[x]['fishType'];
            	var amount = data[x]['amount'];
            	var picture = data[x]['fishPicture'];
             	console.log("Picture " + picture);
                var picturePath = "img/" + username + "/" + picture;
                console.log(picturePath);
                var weight = data[x]['weight'];
             	var infoWindowContent = [
		        ['<div class="info_content">' +
		        '<h3>' + comment + '</h3>' +
		        '<p><strong>Date:</strong> '+ date +' <br/><strong>Weight:</strong> ' + weight + '<br/><strong>Type of fish:</strong> ' + fishType + ' <br/><strong>Amount caught:</strong> ' + amount + '</p>' +
		        '<img src=' + picturePath +  ' height="125px width=100px"/>'+'</div>']
		    ];
                 console.log('<img src=' + picturePath +  ' height=125px width=100px/>');
             	addMarker(map, infoWindowContent[0][0], location);
			  	}
         },
         
         complete: function(data, status){
              //alert(status);
             //$("#test").html(data, status);
         }
     });
    
    
    getAverage();
    
    
}
    

    // add markers that belong to signed in user
    function addUserMarkers(map, name, location, pinID){
        console.log("addUserMarker");
        var icon = {
            url: "fishIcon.png", // url
            scaledSize: new google.maps.Size(50, 50), // scaled size
            
        };
        
        var marker = new google.maps.Marker({
            position: location,
            icon: icon,
            map: map,
            ID: pinID
        });
        
        userMarkers.push(marker);
        
        google.maps.event.addListener(marker, 'click', function(){
            if(typeof infowindow != 'undefined') infowindow.close();
            infowindow = new google.maps.InfoWindow({
                content: name
            });
            infowindow.open(map,marker);
        })
    }
    
    function addMarker(map, name, location){
    var image = 'Map_marker.png';
	var marker = new google.maps.Marker({
		position: location,
        icon: image,
		map: map
	});
	
	google.maps.event.addListener(marker, 'click', function(){
        if(typeof infowindow != 'undefined') infowindow.close();
        infowindow = new google.maps.InfoWindow({
            content: name
        });
		infowindow.open(map,marker);
	});
        
        
}
    
google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>

  <style>
  @font-face{
  	font-family: customFont;
  	src: url(fonts/Airstream.ttf);
  }
  #wrapper{
    background: rgb(233, 234, 237);
  }

  #mapCanvas {
    float: center;
    height:300px;
    padding-top:100px;

  }
  #myForm{
      width: 500px;
      height: 200px;
      float: left;
  }
  #infoPanel {
    float: left;
    margin-left: 10px;
  }
  #infoPanel div {
    margin-bottom: 5px;
  }
  #header{
      width: 100%;
      height: 50px;
      background-color: #377fa3;
  }
  #banner{
      float: right;
      color: white;
      margin: 15px;
  }
  #logout{
    width:100px;
    height:60px;
  }
  #name{
    width:200px;
  }
  #title{
    padding-left:20px;
    font-size:55px;
    color: #ffffff;
    font-family: customFont;
  }
  #extraRow{
    background-color: #377fa3;
  }
  #selectImage, #amount, #name{
    text-align:center;
    padding-top:10px;
  }
  </style>
  <body id="wrapper">
  <div class="container" id="header">
    <div class="row">
        <div class="col-xs-6">
          <h1 id="title">InstaFish</h1>
        </div>
        <div class="col-xs-6">
          <form action="logout.php">
            <input type="submit" value="Logout" id="logout" />
          </form>
        </div>
    </div>
  </div>


  <div class="container" id="extraRow" style='height:55px;'>
    <div class="row"></div>
  </div>
  <div class="container">
    <div class="row">
      <div id="mapCanvas" style="-moz-box-shadow: 1px 1px 3px 2px #3b5998;
        -webkit-box-shadow: 1px 1px 3px 2px #3b5998;
        box-shadow:         1px 1px 3px 2px #3b5998;">
      </div>
    </div>
      
    
  </div>


  <!--******************************* THIS BLOCK OF CODE PRINTS OUT THE LATITUDE AND LONGITUDE OF THE MARKERS CURRENT POSITION *********
  <div id="infoPanel">
    <b>Marker status:</b>
    <div id="markerStatus"><i>Click and drag the marker.</i></div><!-       ->
    <b>Current position:</b>
    <div id="info"></div>
    <b>Closest matching address:</b>
    <div id="address"></div>
  </div>
           ****************************************************************************************************************************-->
    <!--<div id="fishForm">
       <fieldset id="myForm" style="background-color: #f7f7f7">
            Catch date (date and time):<input type="datetime-local" name="bdaytime" style="background-color:white"><br/>

            Type of fish: <input type="text" name="fish" id="fish" size="35" style="background-color:white"><br/>

            Amount: <input type="number" name="amount" id="amount" style="background-color:white"><br/>

            Comments:<br/> <textarea placeholder="Share some info that could help others." cols="80" rows="5" style="background-color:white"></textarea><br/>

            <form method="post" enctype="multipart/form-data">
                Select image:<input type="file" name="fileName" />
                <input type="submit" />
            </form>
        </fieldset>
    </div>-->

    <div id="abc" style="display: none;">
        <!-- Popup Div Starts Here -->
        <div id="popupDiv">
        <!-- Contact Us Form -->
          <form id="form" method="post" name="fileinfo" enctype="multipart/form-data">
            <img id="close" src="img/closeX.png" onclick ="div_hide()">
              <h2 style="background-color:#377fa3;color:#ffffff;">Add info about your pin</h2>
                <hr>

                <div>Enter the date: <input id="datepicker" name="date" placeholder="xx/xx/xxxx"/></div>
                <div>Type of fish: <input id="name" name="fishType" placeholder="Type of fish" type="text"></div>
                <div>Amount: <input type="number" min="0" name="amount" id="amount" style="background-color:white"></div>
                <div>Weight of catch: <input type="number" min="1" name="weight" id="weight" style="backgroun-color:white"> </div>
                   
                <div>Comments: <textarea id="comment" name="comments" placeholder="Share some info that could help others."></textarea></div>
                
                <div>Select image:<input type="file" id="selectImage" name="fileName"/></div>
                    <br/>
                    <br/>
                <button id="addInfo">Add</button>
          </form>
        </div>
    </div>
    <br/>
    <div class="container">
      <div class="row">
        <button id="popup" onclick="div_show()" style="background-color:#377fa3;">Drop Pin!</button>
      </div>
        <div class="row">
        <div id="userStats">
            <h3 style="text-align: center">Stats of your catches!</h3>
            <div id="stats">
                
            </div>
        </div>
      </div>
    </div>
  
    </body>
    </html>

<script>
    
 // ajax method to delete marker
 
 function deleteMarker(pinID){
        $.ajax({
            type: "post",
            url: "http://gallery-armani.codio.io:3000/Instafish/endpoints/insertRecords.php",
            dataType: "json",
            data: {"userID": "1", "deletePin": "1", "pinID": pinID},
            success: function(data, status){
                console.log("Success!");
                alert(data['status']);
           },
            complete: function(data, status){
                console.log("Noooo");
                alert(data);
            }
        });
    }
    

    // Complete!
 function addFishingLocation(date, typeOfFish, amount, comments){
     var upload_data = new FormData(document.forms.namedItem("fileinfo"));
     var time = new Date();
     upload_data.append("userID", <?=$_SESSION['userID']?>);
     upload_data.append("latitude", latitude);
     upload_data.append("longitude", longitude);
     upload_data.append("time", time.toLocaleTimeString().substring(0, time.toLocaleDateString().length - 1)); // time
     console.log(JSON.stringify(upload_data));
     
     var xhr = new XMLHttpRequest;
        xhr.open('POST', 'http://gallery-armani.codio.io:3000/Instafish/endpoints/insertRecords.php', true);
        xhr.onload = function(oEvent){
            if(xhr.status == 200){
                alert("Uploaded!");
            } else{
                alert("An error occurred");
            }
        }
        xhr.send(upload_data);
  
    // Update Map.
    // 
    div_hide();
    console.log("div should close");
    $('#datepicker').value = "";
    $('#name').value = "";
    $('#amount').value = "";
     
    $('#comments').value = "";
    setTimeout(function(){getUserPins(); getAverage();},3000);
     console.log("Does it work now?");
 }

</script>

    <script>
        $("#addInfo").click(function(e){
            e.preventDefault();
            if ($('#name').value == "" || $('#amount').value == "" || $('#comment').value == "") {
          alert("Fill All Fields !");
            }
            else {
                console.log("Form success");
                var date = $('#datepicker').val();
                var typeOfFish = $('#name').val();
                var amount = $('#amount').val();
                var comments = $('#comments').val();
                var weight  = $("#weight").val();
                console.log(date)
                console.log(typeOfFish)
                console.log(amount)
                console.log(comments)
                console.log(weight);
                addFishingLocation(date, typeOfFish, amount, comments);
          
            // get all elements here to insert to form
                alert("Form Submitted Successfully...");
           
             }
            
        });

        
    //Function To Display Popup
    function div_show() {
    document.getElementById('abc').style.display = "block";
    }
    //Function to Hide Popup
    function div_hide(){
    document.getElementById('abc').style.display = "none";
    }
    </script>

  <!--Add DatePicker-->
  <script>
  $(document).ready(function() {
    $("#datepicker").datepicker({dateFormat: "yy-mm-dd"});
  });
  </script>
