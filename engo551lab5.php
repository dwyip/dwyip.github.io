<?php
  $host='localhost';
  $db = 'engo551';
  $username = 'postgres'; //replace correct username e.g. postgres
  $password = 'camp2014'; //replace correct password e.g. camp2014
  $dsn =
  "pgsql:host=$host;port=5432;dbname=$db;user=$username;password=$password";

  $param = file_get_contents("php://input");
  $param_decode = json_decode($param, true);

  $communityName = $param_decode["name"];

  try{
  // create a PostgreSQL database connection
  $conn = new PDO($dsn);
  // query results if connected to the PostgreSQL successfully
  if($conn){

    $queryStatement = 'SELECT name, type, address_ab, postal_cod, community, latitude, longitude FROM "lab5"."school"';
    $query = $conn->query($queryStatement);
    $schoolData = $query->fetchAll();


    $allSchools = array();

    for ($i=0; $i < sizeof($schoolData); $i++) {
      if (strtoupper($communityName) == $schoolData[$i]['community']) {

        $schoolProp = new StdClass();
        $schoolProp->name = $schoolData[$i]['name'];
        $schoolProp->type = $schoolData[$i]['type'];
        $schoolProp->address = $schoolData[$i]['address_ab'];
        $schoolProp->postal_code = $schoolData[$i]['postal_cod'];

        $schoolGEOM = new StdClass();
        $schoolGEOM->type = "Point";
        $schoolGEOM->coordinates = array((float)$schoolData[$i]['longitude'], (float)$schoolData[$i]['latitude']);

        $school = new StdClass();
        $school->type = "Feature";
        $school->properties = $schoolProp;
        $school->geometry = $schoolGEOM;

        array_push($allSchools, $school);
      }
    }
    $communitySchool = new stdClass();
    $communitySchool->type = "FeatureCollection";
    $communitySchool->features = $allSchools;

    $communityJson = json_encode($communitySchool);
    echo $communityJson;

  }
  }catch (PDOException $e){
  // report error message
  echo $e->getMessage();
  }
?>
