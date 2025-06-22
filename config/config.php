<?php
// config.php

$host = "localhost";
$dbname = "rvs";
$user = "root";
$pass = "";

// Create connection
$db = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

define("APPURL", "http://localhost/retroviral_solution");
?>
