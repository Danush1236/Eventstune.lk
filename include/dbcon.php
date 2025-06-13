<?php

$DBhost = "localhost";
$DBusername = "root";
$DBuserpwd = "";
$DBname = "eventstunes";

$link = mysqli_connect($DBhost, $DBusername, $DBuserpwd, $DBname);
if ($link->connect_error) {
    die("Connection failed: ");
}
else{
    //echo "Connected successfully";
}

?>
