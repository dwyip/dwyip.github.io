<?php
  $host='localhost';
  $db = 'engo551';
  $username = 'postgres'; //replace correct username e.g. postgres
  $password = 'camp2014'; //replace correct password e.g. camp2014
  $dsn =
  "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";
  try{
  // create a PostgreSQL database connection
  $conn = new PDO($dsn);
  // query results if connected to the PostgreSQL successfully
  if($conn){
    $queryStatement = 'SELECT name FROM "lab5"."community"';
    $query = $conn->query($queryStatement);
    $communityName = $query->fetchAll();

  }
  }catch (PDOException $e){
  // report error message
  echo $e->getMessage();
  }
?>
