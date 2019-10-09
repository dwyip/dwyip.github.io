<?php

  $param = file_get_contents("php://input");
  $param_decode = json_decode($param, true);

  $bound = $param_decode["bound"];
  $sw = $bound["_southWest"];
  $ne = $bound["_northEast"];


  $params = array(
  'api_key' => '4d0d018e61c719056e6a15ffab89df3f',
  'method' => 'flickr.photos.search',
  'bbox' =>  $sw['lng'].','.$sw['lat'].','.$ne['lng'].','.$ne['lat'],
  'extras' => 'geo',
  'has_geo' => '1',
  'per_page' => '20', //Getting 20 photos
  'page' => '1',
  'format' => 'json',
  'nojsoncallback' => '1',
  );
  $encoded_params = array();
  foreach ($params as $k => $v){
  $encoded_params[] = urlencode($k).'='.urlencode($v);
  }
  $url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
  $rsp = file_get_contents($url);
  $rsp = str_replace( 'jsonFlickrApi(', '', $rsp );
  $rsp = substr( $rsp, 0, strlen( $rsp ) );
  $rsp2 = json_decode($rsp, true);
  //echo '<pre>';
  //print_r($rsp2);

  //Fetch photos within given boundaries
  $photos = $rsp2['photos']['photo'];
  $allPics = array();
  for ($i=0; $i < sizeof($photos); $i++) {

    $picProp = new StdClass();
    $picProp->id = $photos[$i]['id'];
    $picProp->secret = $photos[$i]['secret'];
    $picProp->farm = $photos[$i]['farm'];
    $picProp->server = $photos[$i]['server'];

    $picGEOM = new StdClass();
    $picGEOM->type = "Point";
    $picGEOM->coordinates = array((float)$photos[$i]['longitude'], (float)$photos[$i]['latitude']);

    $pic = new StdClass();
    $pic->type = "Feature";
    $pic->properties = $picProp;
    $pic->geometry = $picGEOM;

    array_push($allPics, $pic);

  }

  $pictures = new stdClass();
  $pictures->type = "FeatureCollection";
  $pictures->features = $allPics;

  $picturesJson = json_encode($pictures);
  echo $picturesJson;


 ?>
