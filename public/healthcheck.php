<?php
$host = "db"; // e.g. mysql or blog-mysql
$port = 3306;
$dbname = "simple_blog";
$username = "user";
$password = "userpass";

echo "Trying to connect to DB at $host:$port...\n";

$conn = new mysqli($host, $username, $password, $dbname, (int)$port);

if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error . "\n";
    http_response_code(500);
    exit(1);
}

echo "✅ Connection successful!\n";
$conn->close();
