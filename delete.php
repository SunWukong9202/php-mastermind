<?php
require "db.php";

session_start();

if(!isset($_SESSION['user'])) {
  header('Location: login.php');
  return;
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
  $id = $_GET['id'] ?? null;
  
  $stmt = $conn->prepare("SELECT * FROM contacts WHERE id = :id LIMIT 1");
  $stmt->execute([":id" => $id]); //array-as with all var to bind and execute in the stmt

  if($stmt->rowCount() == 0) {
    http_response_code(404);
    echo 'HTTP 404 NOT FOUND';
    return;
  }
  
  $contact = $stmt->fetch(PDO::FETCH_ASSOC);

  if($contact['user_id'] != $_SESSION['user']['id']) {
    http_response_code(403);
    echo 'HTTP 403 UNAUTHORIZED';
    return;
  }

  $conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $contact['id']]);

  $_SESSION['flash'] = ['msj' => "Contact {$contact['name']} deleted."];

  header("Location: home.php");
  return;
}
