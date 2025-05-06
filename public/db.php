<?php
$host = "db"; // e.g. mysql or blog-mysql
$port = 3306;
$dbname = "simple_blog";
$username = "user";
$password = "userpass";

$conn = new mysqli($host, $username, $password, $dbname, (int)$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>