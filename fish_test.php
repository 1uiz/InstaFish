<?php

    session_start();

    if (!isset($_SESSION['username'])){  //validates whether user has logged in
        header("Location: login.html");
    }

?>

<html>
<head>
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

  var map;
  var bounds = new google.maps.LatLngBounds();
  var mapOptions = {
  mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);

  //IMAGE/ Geopoint icon.
  var image = 'Map_marker.png';
    // Multiple Markers
    var markers = [
        ['Salinas, California', 36.665433, -121.651498],
        ['Palace of Westminster, London', 51.499633,-0.124755]
    ];

    // Info Window Content
    var infoWindowContent = [
        ['<div class="info_content">' +
        '<h3>Salinas, California</h3>' +
        '<p><strong>Date:</strong> 05/044/2015 <br/><strong>Type of fish:</strong> Sardine <br/><strong>Amount caught:</strong> 5</p>' +
        '<img src=img/sardine.jpg height=125px width=100px/>'+'</div>'],
        ['<div class="info_content">' +
        '<h3>Palace of Westminster</h3>' +
        '<p><strong>Date:</strong> 05/044/2015 <br/><strong>Type of fish:</strong> Sardine <br/><strong>Amount caught:</strong> 5</p>' +
        '<img src=img/sardine.jpg height=125px width=100px/>'+'</div>']
    ];

    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;

    // Loop through our array of markers & place each one on the map
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: image,
            title: markers[i][0]
        });

        // Allow each marker to have an info window
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map, marker);
  });

google.maps.event.addDomListener(window, 'load', initialize);

  /*// Update current position info.
  updateMarkerPosition(latLng);
  geocodePosition(latLng);*/

  // Add dragging event listeners.
  google.maps.event.addListener(marker, 'dragstart', function() {
    updateMarkerAddress('Dragging...');
  });

  google.maps.event.addListener(marker, 'drag', function() {
    updateMarkerStatus('Dragging...');
    updateMarkerPosition(marker.getPosition());
  });

  google.maps.event.addListener(marker, 'dragend', function() {
    updateMarkerStatus('Drag ended');
    geocodePosition(marker.getPosition());
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
    
 function addMarker()
    
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
