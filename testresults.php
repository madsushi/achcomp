<html lang="en">
<head><title>WoW Achievement Comparison</title>
<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- CSS -->
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">

      /* Sticky footer styles
      -------------------------------------------------- */

      html,
      body {
        height: 100%;
        background-color: #111111;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -40px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 40px;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      footer {
          background-color: #000000;
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }
      
      
      /* Custom page CSS
      -------------------------------------------------- */
      /* Not required for template or sticky footer method. */

      .container {
        width: auto;
      }
      .container .credit {
        margin: 10px 0;
      }


</style>
    <link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<?php

require './includes/safesqli.php';
require './includes/header.php';
//connect to mysql via mysqli
$sqlcon = mysqli_connect("localhost","achcomp",$achpassword,"achcomp");

//grab the guildname from the GET data
//$guildname = mysqli_real_escape_string($sqlcon, $_GET['guildname']);
//$guildnamei = urldecode($guildname);

$apiRegion1 = mysqli_real_escape_string($sqlcon, $_GET['region1']);
$region1 = $apiRegion1;

$apiRegion2 = mysqli_real_escape_string($sqlcon, $_GET['region2']);
$region2 = $apiRegion2;


$apiRealm1 = mysqli_real_escape_string($sqlcon, $_GET['realm1']);
$realm1 = $apiRealm1;

$apiRealm2 = mysqli_real_escape_string($sqlcon, $_GET['realm2']);
$realm2 = $apiRealm2;


$apiPlayername1 = mysqli_real_escape_string($sqlcon, $_GET['playername1']);
$name1 = $apiPlayername1;

$apiPlayername2 = mysqli_real_escape_string($sqlcon, $_GET['playername2']);
$name2 = $apiPlayername2;


$apiFavor = mysqli_real_escape_string($sqlcon, $_GET['favor']);
$favor = $apiFavor;

$apiHideshared = mysqli_real_escape_string($sqlcon, $_GET['hideshared']);
$hideshared = $apiHideshared;

$apiHidemissed = mysqli_real_escape_string($sqlcon, $_GET['hidemissed']);
$hidemissed = $apiHidemissed;




?>

</head><body>


<br><br>
<font color="#FFFFFF" size="2">
<center><h1>WoW Achievement Comparison</h1></center>
<br>

<?php
  $toonapi1 = utf8_encode($name1);
  //echo $toonapi.'--';
  $apiUrl1 = "http://{$region1}.battle.net/api/wow/character/{$realm1}/{$toonapi1}?fields=achievements";
  $ch = curl_init($apiUrl1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_VERBOSE,false);

  $apiStr1 = curl_exec($ch);
  $apiArr1 = json_decode($apiStr1, true);
  $apiClass1 = $apiArr1['class'];
  $apiClassColor1 = "classcolor".$apiClass1;
  $apiArr1 = implode(",", $apiArr1['achievements']['achievementsCompleted']);
  $apiArr1 = explode(",", $apiArr1);

  $apiArr1 = array();
  $apiArr1test = "select id from achievements";
  $apiArr1move = mysqli_query($sqlcon, $apiArr1test);
  while ($test = mysqli_fetch_assoc($apiArr1move)){
  $apiArr1[] = $test['id'];
  }
  
  
  $toonapi2 = utf8_encode($name2);
  //echo $toonapi.'--';
  $apiUrl2 = "http://{$region2}.battle.net/api/wow/character/{$realm2}/{$toonapi2}?fields=achievements";
  $ch = curl_init($apiUrl2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_VERBOSE,false);

  $apiStr2 = curl_exec($ch);
  $apiArr2 = json_decode($apiStr2, true);
  $apiClass2 = $apiArr2['class'];
  $apiClassColor2 = "classcolor".$apiClass2;
  $apiArr2 = implode(",", $apiArr2['achievements']['achievementsCompleted']);
  $apiArr2 = explode(",", $apiArr2);
  
  $apiArr2 = array();
  $apiArr2test = "select id from achcat";
  $apiArr2move = mysqli_query($sqlcon, $apiArr2test);
  while ($test2 = mysqli_fetch_assoc($apiArr2move)){
  $apiArr2[] = $test2['id'];
  }
  
  
  $achievearray = array();
  
  $achievearrayquery = "SELECT id, points, title, icon, description FROM achievements";
  $achievequery = mysqli_query($sqlcon, $achievearrayquery);
  
  while ($row = mysqli_fetch_assoc($achievequery)){
    $id = $row['id'];
    $points = $row['points'];
    $title = $row['title'];
    $icon = $row['icon'];
    $description = $row['description'];
    $achievearray[$id]['id'] = $id;
    $achievearray[$id]['points'] = $points;
    $achievearray[$id]['title'] = $title;
    $achievearray[$id]['icon'] = $icon;
    $achievearray[$id]['description'] = $description;
  }
  
  echo '<center><form action="results.php" method="get">';
  echo '<input type="hidden" name="region1" value="'.$region1.'">';
  echo '<input type="hidden" name="region2" value="'.$region2.'">';
  echo '<input type="hidden" name="realm1" value="'.$realm1.'">';
  echo '<input type="hidden" name="realm2" value="'.$realm2.'">';
  echo '<input type="hidden" name="playername1" value="'.$name1.'">';
  echo '<input type="hidden" name="playername2" value="'.$name2.'">';
  echo '<table cellpadding="10"><tr><td>';
  echo '<input type="checkbox" name="hideshared" value="yes"><font color="white">Hide Shared Achievements</font><br><input type="checkbox" name="hidemissed" value="yes"><font color="white">Hide Missed Achievements</font>';
  echo '<td><a href="results.php?region1='.$region1.'&realm1='.$realm1.'&playername1='.$name1.'&region2='.$region2.'&realm2='.$realm2.'&playername2='.$name2.'&favor=p1"><img src="/img/p1.png"></a></td>';
  echo '<td><a href="results.php?region1='.$region1.'&realm1='.$realm1.'&playername1='.$name1.'&region2='.$region2.'&realm2='.$realm2.'&playername2='.$name2.'&favor=p2"><img src="/img/p2.png"></td>';
  echo '</td></tr><tr><td><input type="submit" value="Submit"></td></tr></table>';
  echo '</form></center>';
  
  echo '<center><table cellpadding="3"><thead><tr><th class="player">Achievement Comparison</th>';
  echo '<th class="'.$apiClassColor1.'">'.$name1.'</th><th class="'.$apiClassColor2.'">'.$name2.'</th>';
  echo '</tr></thead><tbody>';
  
foreach($achievearray as $keydump => $v) {

  //create a row for each toon and dump their achievement status
  
     $id = $v['id'];
     $points = $v['points'];
     $title = $v['title'];
     $icon = $v['icon'];
     $description = $v['description'];
     
  if($favor == "p1"){
      if(in_array($id, $apiArr1) && in_array($id, $apiArr2)){ 

      }elseif(!in_array($id, $apiArr1) && !in_array($id, $apiArr2)){
      
      }elseif($points == 0){
      
      }elseif(in_array($id, $apiArr2)){
      
      }elseif(in_array($id, $apiArr1)){
      
      if($class == "rowclass0"){
        $class = "rowclass1";
      }
      else{
        $class = "rowclass0";
      }
      
      echo '<tr class="'.$class.'"><td width="375" style="padding:10px;"><b><a href="http://www.wowhead.com/achievement='.$id.'">' . $title . ' - '.$points.'</a></b><br><br><font color="white">'.$description.'</font></td>';

      echo '<td>';
        if(in_array($id, $apiArr1)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
         echo '<img src="/img/0.png">';
        }
      echo '</td>';
      
      echo '<td>';
        if(in_array($id, $apiArr2)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
          echo '<img src="/img/0.png">';
        }
      echo '</td>';
      echo '</tr>';
      }
  }elseif($favor == "p2"){
       if(in_array($id, $apiArr1) && in_array($id, $apiArr2)){ 

      }elseif(!in_array($id, $apiArr1) && !in_array($id, $apiArr2)){
      
      }elseif($points == 0){
      
      }elseif(in_array($id, $apiArr1)){
      
      }elseif(in_array($id, $apiArr2)){
      
      if($class == "rowclass0"){
        $class = "rowclass1";
      }
      else{
        $class = "rowclass0";
      }
      
      echo '<tr class="'.$class.'"><td width="375" style="padding:10px;"><b><a href="http://www.wowhead.com/achievement='.$id.'">' . $title . ' - '.$points.'</a></b><br><br><font color="white">'.$description.'</font></td>';

      echo '<td>';
        if(in_array($id, $apiArr1)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
         echo '<img src="/img/0.png">';
        }
      echo '</td>';
      
      echo '<td>';
        if(in_array($id, $apiArr2)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
          echo '<img src="/img/0.png">';
        }
      echo '</td>';
      echo '</tr>';
      }
    
    }elseif($hidemissed == "yes" && $hideshared == "yes"){
      if(in_array($id, $apiArr1) && in_array($id, $apiArr2)){
    
      }elseif(!in_array($id, $apiArr1) && !in_array($id, $apiArr2)){
      
      }elseif($points == 0){
      
      }else{
      
      if($class == "rowclass0"){
        $class = "rowclass1";
      }
      else{
        $class = "rowclass0";
      }
      
      echo '<tr class="'.$class.'"><td width="375" style="padding:10px;"><b><a href="http://www.wowhead.com/achievement='.$id.'">' . $title . ' - '.$points.'</a></b><br><br><font color="white">'.$description.'</font></td>';

      echo '<td>';
        if(in_array($id, $apiArr1)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
         echo '<img src="/img/0.png">';
        }
      echo '</td>';
      
      echo '<td>';
        if(in_array($id, $apiArr2)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
          echo '<img src="/img/0.png">';
        }
      echo '</td>';
      echo '</tr>';
      
      }
    }elseif($hidemissed == "yes" && $hideshared != "yes"){
      if(!in_array($id, $apiArr1) && !in_array($id, $apiArr2)){
      
      }elseif($points == 0){
      
      }else{
      
      if($class == "rowclass0"){
        $class = "rowclass1";
      }
      else{
        $class = "rowclass0";
      }
      
      echo '<tr class="'.$class.'"><td width="375" style="padding:10px;"><b><a href="http://www.wowhead.com/achievement='.$id.'">' . $title . ' - '.$points.'</a></b><br><br><font color="white">'.$description.'</font></td>';

      echo '<td>';
        if(in_array($id, $apiArr1)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
         echo '<img src="/img/0.png">';
        }
      echo '</td>';
      
      echo '<td>';
        if(in_array($id, $apiArr2)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
          echo '<img src="/img/0.png">';
        }
      echo '</td>';
      echo '</tr>';
      
      }
    
    
    
    
    }elseif($hidemissed != "yes" && $hideshared == "yes"){
      if(in_array($id, $apiArr1) && in_array($id, $apiArr2)){
    
      }elseif($points == 0){
      
      }else{
      
      if($class == "rowclass0"){
        $class = "rowclass1";
      }
      else{
        $class = "rowclass0";
      }
      
      echo '<tr class="'.$class.'"><td width="375" style="padding:10px;"><b><a href="http://www.wowhead.com/achievement='.$id.'">' . $title . ' - '.$points.'</a></b><br><br><font color="white">'.$description.'</font></td>';

      echo '<td>';
        if(in_array($id, $apiArr1)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
         echo '<img src="/img/0.png">';
        }
      echo '</td>';
      
      echo '<td>';
        if(in_array($id, $apiArr2)){
          echo '<a href="http://www.wowhead.com/achievement='.$id.'"><img src="/icons/'.$icon.'.png"></a>';
        }else{
          echo '<img src="/img/0.png">';
        }
      echo '</td>';
      echo '</tr>';
      
      }
    
    }else{
    
    
      if($class == "rowclass0"){
        $class = "rowclass1";
      }
      else{
        $class = "rowclass0";
      }
    
    echo '<tr class=' . $class . '><td width="375" style="padding:10px;"><b><a href="http://www.wowhead.com/achievement='.$id.'">' . $title . ' - '.$points.'</a></b><br><br><font color="white">'.$description.'</font></td>';

    echo '<td>';
      if(in_array($id, $apiArr1)){
        echo '<img src="/icons/'.$icon.'.png">';
      }else{
       echo '<img src="/img/0.png">';
      }
    echo '</td>';
    
    echo '<td>';
      if(in_array($id, $apiArr2)){
        echo '<img src="/icons/'.$icon.'.png">';
      }else{
        echo '<img src="/img/0.png">';
      }
    echo '</td>';
    echo '</tr>';

 

} 
}
  
echo '</tbody></table></center><br><br>';
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  ?>
  
  
  
  
  
  
  </body>
  </html>