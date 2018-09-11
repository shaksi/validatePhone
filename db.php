<?php
function dbConnect() {
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
  $hostname = $url["host"];
  $username = $url["user"];
  $password = $url["pass"];
  $database = substr($url["path"], 1);

  // Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  return false;
}

return $conn;
}




?>
