<?php

  $param = file_get_contents("php://input");
  $param_decode = json_decode($param, true);
  $cityName = $param_decode["name"];

  //Reading csv file
  $csv = fopen("CanadaCities.csv","r");
  $cities = [];
  while(! feof($csv))
   { $cities[]=fgetcsv($csv); }
  fclose($csv);

  //Loop through search input with cities list
  //Creating classes in geoJson format
  $CityNotFound = True;

  for ($i = 0; $i < sizeof($cities); $i++) {
    if (strtoupper($cities[$i][0]) == strtoupper($cityName)) {
      $cityGeometry = new stdClass();
      $cityGeometry->type = "Point";
      $cityGeometry->coordinates = array((float)$cities[$i][2], (float)$cities[$i][1]);

      $cityProperties = new stdClass();
      $cityProperties->name = $cities[$i][0];
      $cityProperties->province = $cities[$i][3];

      $cityPoint = new stdClass();
      $cityPoint->type = "Feature";
      $cityPoint->properties = $cityProperties;
      $cityPoint->geometry = $cityGeometry;

      $cityCrsProperty = new stdClass();
      $cityCrsProperty->name = "urn:ogc:def:crs:OGC:1.3:CRS84";

      $cityCrs = new stdClass();
      $cityCrs->type = "name";
      $cityCrs->properties = $cityCrsProperty;

      $cityFound = new stdClass();
      $cityFound->type = "FeatureCollection";
      $cityFound->crs = $cityCrs;
      $cityFound->features = array($cityPoint);

      $cityFoundJson = json_encode($cityFound);
      echo $cityFoundJson;
      $CityNotFound = False;
    }
  }

  //Return message if city not found
  if ($CityNotFound) {
    echo "NoCityFound";
  }

 ?>
