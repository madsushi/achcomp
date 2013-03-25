<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 
Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<title>WoW Meta Parser - Guild Input</title>

<?php 

  require './includes/header.php';
  
?>

</head>

<body bgcolor="#111111"><center>
<a href="/guildselect.php"><img src=/img/banner.png></a>
<font color="#FFFFFF" size="2">
<h1 class="regular"><b>Cross-realm Group Import</b></h1><h2 class="regimpport">Track your raiders' meta progress by entering their names and realms here</h2>

<table border="0">
<tr>
<td>
<table>
<form method="post" action="xrimportsql.php">
<tr>
<td>Region</td>
<td><select class="inputclass" name="region">
<option value="us">US</option>
<option value="eu">EU</option>
</select>
</td>
</tr>
<tr>
<td>Main Realm Name <br></td>
<td>
<select class="inputclass" name="realmname">
<option value="">Select a realm</option>

<?php

require './includes/safesqli.php';
//connect to mysql via mysqli
$sqlcon = mysqli_connect("localhost","metaparser",$metapassword,"metaparser");
$realmlist=mysqli_query($sqlcon, "select realmname, fancyrealmname from realms");

while($realmarray = mysqli_fetch_assoc($realmlist)) {
  $realmarrayrealmname=$realmarray["realmname"];
  $realmarrayfancyname=$realmarray["fancyrealmname"];
  echo '<option value="'.$realmarrayrealmname.'">'.$realmarrayfancyname.'</option>';
}

?>

</select>
</td>

</tr>
<tr>
<td>Group Name</td>
<td><input class="inputclassx" type="text" name="guildname" size="20">
</td>
</tr>


 <?php
 
$maxcount = 36;
for( $i=1; $i<$maxcount; $i++ ) {
  echo('<tr><td>Player '.$i.'</td><td><input class="inputclassx" type="text" name="toon'.$i.'" size="20"></td><td>
  <select class="inputclassx" name="realm'.$i.'">
  <option value="">Select a realm</option>');
  $realmlist=mysqli_query($sqlcon, "select realmname, fancyrealmname from realms");

  while($realmarray = mysqli_fetch_assoc($realmlist)) {
    $realmarrayrealmname=$realmarray["realmname"];
    $realmarrayfancyname=$realmarray["fancyrealmname"];
    echo '<option value="'.$realmarrayrealmname.'">'.$realmarrayfancyname.'</option>';
  }
  echo('</select></td></tr>');
}

?>

<tr>
<td></td><td></td>
<td align="right"><input type="submit" 
name="submit" value="Submit"></td>
</tr>
</table>
</td>
</tr>


</table>

<br><br><br>
<a class="regular" href="mailto:chase@mdssh.com?Subject=metaparser"><b>email</b></a> | <a class="regular" href="https://twitter.com/#!/madsushi"><b>twitter</b></a><font color=white> |</font> <a href="guildselect.php" class="regular"><b>home</b></a><br>
<br>
</body>
</html>