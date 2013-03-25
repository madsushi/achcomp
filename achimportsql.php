<?php

require './includes/safesqli.php';

//sql connection
$sqlcon = mysqli_connect("localhost","achcomp",$achpassword,"achcomp");
//mysql_query("set names 'utf8'");
require './includes/strip.php';


//for($i = 1; $i <= 8050; $i++){

  //echo $toonapi.'--';
  $apiUrl = "http://us.battle.net/api/wow/data/character/achievements";
  $ch = curl_init($apiUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_VERBOSE,false);

  $apiStr = curl_exec($ch);
  $apiArr = json_decode($apiStr, true);

  $apiFor = $apiArr['achievements'];
 
  
  foreach($apiFor as $v){
    $catid = $v['id'];
    $acharray = $v['achievements'];
    foreach($acharray as $vv){
      $subcat = NULL;
      $id = $vv['id'];
      $points = $vv['points'];
      $title = $vv['title'];
      $icon = $vv['icon'];
      $desc = $vv['description'];
      
      $sqlquery = "INSERT INTO achievements (id, points, title, icon, description) VALUES ('".$id."', '".$points."', '".$title."', '".$icon."', '".$desc."')";
      $runquery = mysqli_query($sqlcon, $sqlquery);
    }
    $catarray = $v['categories'];
    foreach($catarray as $vvv){
      $subcat = $vvv['id'];
      $acharray = $vvv['achievements'];
      foreach($acharray as $vvvv){
        $id = $vvvv['id'];
        $points = $vvvv['points'];
        $title = $vvvv['title'];
        $icon = $vvvv['icon'];
        $desc = $vvvv['description'];
        
        $sqlquery = "INSERT INTO achievements (id, points, title, icon, description) VALUES ('".$id."', '".$points."', '".$title."', '".$icon."', '".$desc."')";
        $runquery = mysqli_query($sqlcon, $sqlquery);
      }
    }
  }
mysqli_close($sqlcon); 

?>