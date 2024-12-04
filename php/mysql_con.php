<?php
$serverName = "localhost";
$username = "root";
$password = "";
$dbname = "yalagoal";

$conn = new mysqli($serverName, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Failed to connect to the database. Please try again later.");
}
