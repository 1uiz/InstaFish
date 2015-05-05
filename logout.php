<?php
session_start();
session_destroy();
header("Location: login.html");  //takes users back to login screen
?>
