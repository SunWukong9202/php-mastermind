<?php
require "db.php";

if($_SERVER['REQUEST_METHOD'] == 'GET') {
  $id = $_GET['id'];
  
  $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
  $stmt->execute([":id" => $id]); //array-as with all var to bind and execute in the stmt

  if($stmt->rowCount() == 0) {
    http_response_code(404);
    echo 'HTTP 404 NOT FOUND';
    return;
  }

  $conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);

  header("Location: index.php");
}
