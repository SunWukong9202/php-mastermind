<?php
$host = "localhost";
$database = "contacts_app";
$user = "root";
$password = "";

try{
  $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // foreach($conn->query("SHOW DATABASES") as $row) {
  //   echo "<pre>";
  //   print_r($row);
  //   echo "</pre>";
  // }
  echo "Connected successfully";
}catch(PDOExepcion $err) {
  echo "Connection failed: " . $err->getMessage();
}
