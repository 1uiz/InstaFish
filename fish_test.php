<?php

    session_start();

    if (!isset($_SESSION['username'])){  //validates whether user has logged in
        header("Location: login.html");
    }

?>

<html>
<head>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="js/my_js.js"></script>
<link href="css/elements.css" rel="stylesheet">
<script src="js/my_js.js"></script>

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var geocoder = new google.maps.Geocoder();

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

function initialize() {
    
    var userID = 15;
     $.ajax({
         type: "post",
         url: "http://45.55.190.168/InstaFish/endpoints/retrieveRecords.php",
         dataType: "json",
         data: {"userID": userID},
         success: function(data, status){
  			var map = new google.maps.Map(document.getElementById('mapCanvas'), {
			center: new google.maps.LatLng(data[0]['latitude'], data[0]['longitude']),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoom: 5
		});
             for(var x = 0; x < data.length; x++){
             	var location = new google.maps.LatLng(data[x]['latitude'], data[x]['longitude']);
             	var comment = data[x]['comments'];
            	var date = data[x]['date'];
            	var fishType = data[x]['fishType'];
            	var amount = data[x]['amount'];
            	var picture = data[x]['profilePicture'];
             	
             	var infoWindowContent = [
		        ['<div class="info_content">' +
		        '<h3>' + comment + '</h3>' +
		        '<p><strong>Date:</strong> '+ date +' <br/><strong>Type of fish:</strong> ' + fishType + ' <br/><strong>Amount caught:</strong> ' + amount + '</p>' +
		        '<img src=' + picture + ' height=125px width=100px/>'+'</div>']
		    ];
             	addMarker(map, infoWindowContent[0][0], location);
			  	}
         },
         
         complete: function(data, status){
              //alert(status);
             //$("#test").html(data, status);
         }
     });
    
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
    height:400px;
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
          <form action="#" id="form" method="post" name="form" enctype="multipart/form-data">
            <img id="close" src="img/closeX.png" onclick ="div_hide()">
              <h2 style="background-color:#377fa3;color:#ffffff;">Add info about your pin</h2>
                <hr>

                <div>Enter the date: <input id="datepicker" placeholder="xx/xx/xxxx"/></div>
                <div>Type of fish: <input id="name" name="name" placeholder="Type of fish" type="text"></div>
                <div>Amount: <input type="number" min="0" name="amount" id="amount" style="background-color:white"></div>
                <div>Comments: <textarea id="comment" placeholder="Share some info that could help others."></textarea></div>
                <div>Select image:<input type="file" id="selectImage" name="fileName"/></div>
                    <br/>
                    <br/>
                <button id="addInfo" onclick="check_empty()">Add</button>
          </form>
        </div>
    </div>
    <br/>
    <div class="container">
      <div class="row">
        <button id="popup" onclick="div_show()" style="background-color:#377fa3;">Drop Pin!</button>
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
                alert(data['status']);
           },
            complete: function(data, status){
                alert(data);
            }
        });
    }
    
 function addMarker(date, typeOfFish, amount, comments, fileName, latitude, longitude){
     $.ajax({
         type: "post",
         url: "http://gallery-armani.codio.io:3000/Instafish/endpoints/insertRecords.php",
         dataType: "json",
         data: {"userID": <?=$_SESSION['userId']?>, "date": date, "fishType": typeOfFish, "comments": comments, "amount": amount, "latitude": latitude, "longitude": longitude}
     })
 }
    
</script>

    <script>
    function check_empty() {
      console.log("check empty here");
        if (document.getElementById('name').value == "" || document.getElementById('amount').value == "" || document.getElementById('comment').value == "") {
          alert("Fill All Fields !");
        }
        else {
          console.log("Form success");
          document.getElementById('form').submit();
            // get all elements here
          alert("Form Submitted Successfully...");
           
       }
    }
        
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
    $("#datepicker").datepicker();
  });
  </script>
