<?php
session_start();
session_destroy();
header("Location: fish_test_login.html");  //takes users back to login screen
?>
