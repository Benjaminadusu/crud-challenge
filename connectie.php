<?php
// Database connection
$servername = "localhost";
$database = "crud challenge";
$username = "root";
$password = "";

$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);