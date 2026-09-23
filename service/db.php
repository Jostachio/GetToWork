<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "gettoworkdb";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
        die("Database Error" . mysqli_connect_error());
}
?>