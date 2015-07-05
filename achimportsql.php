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
      $id = mysqli_real_escape_string($sqlcon,$vv['id']);
      $points = mysqli_real_escape_string($sqlcon,$vv['points']);
      $title = mysqli_real_escape_string($sqlcon,$vv['title']);
      $icon = mysqli_real_escape_string($sqlcon,$vv['icon']);
      $desc = mysqli_real_escape_string($sqlcon,$vv['description']);
      
      $sqlquery = "INSERT INTO achievements (id, points, title, icon, description) VALUES ('".$id."', '".$points."', '".$title."', '".$icon."', '".$desc."')";
      $runquery = mysqli_query($sqlcon, $sqlquery);
    }
    $catarray = $v['categories'];
    foreach($catarray as $vvv){
      $subcat = $vvv['id'];
      $acharray = $vvv['achievements'];
      foreach($acharray as $vvvv){
        $id = mysqli_real_escape_string($sqlcon,$vvvv['id']);
        $points = mysqli_real_escape_string($sqlcon,$vvvv['points']);
        $title = mysqli_real_escape_string($sqlcon,$vvvv['title']);
        $icon = mysqli_real_escape_string($sqlcon,$vvvv['icon']);
        $desc = mysqli_real_escape_string($sqlcon,$vvvv['description']);
        
        $sqlquery = "INSERT INTO achievements (id, points, title, icon, description) VALUES ('".$id."', '".$points."', '".$title."', '".$icon."', '".$desc."')";
        $runquery = mysqli_query($sqlcon, $sqlquery);
      }
    }
  }
mysqli_close($sqlcon); 

?>