<html>

<head>
<title>WoW Meta Parser - Guild Input</title>

<?php

require './includes/header.php';

?>

</head>
<body bgcolor="#111111"><center>
<font color="#FFFFFF" size="2">
<a href="/guildselect.php"><img src=/img/banner.png></a><br><br>


<?php

require './includes/safesqli.php';

//sql connection
$sqlcon = mysqli_connect("localhost","metaparser",$metapassword,"metaparser");
//mysqli_query($sqlcon, ("set names 'utf8'");
require './includes/strip.php';

//populate 'guilds' table
$apiRealm=mysqli_real_escape_string($sqlcon, $_POST['realmname']);
$apiGuild=mysqli_real_escape_string($sqlcon, $_POST['guildname']);
$apiRegion=mysqli_real_escape_string($sqlcon, $_POST['region']);

$maxcount = 36;
$tooncounter = array();
$toonsort = array();
$realmsort = array();

for( $i=1; $i<$maxcount; $i++ ) {
  $toonq = "toon".$i;
  $toonqu = "toon".$i."u";
  $$toonq = mysqli_real_escape_string($sqlcon, $_POST[$toonq]);
  $toondump = $$toonq;
  $$toonqu = $toondump;
  $tooncounter[] = $$toonqu;
  $realmq = "realm".$i;
  $$realmq = mysqli_real_escape_string($sqlcon, $_POST[$realmq]);

  $toonsort[] = $$toonqu;
  $realmsort[] = $$realmq;
}

$dummyvar1 = mysqli_query($sqlcon, "DELETE FROM guilds WHERE region='" . $apiRegion . "' AND guildname='" . $apiGuild . "' AND realmname='" . $apiRealm . "'");
$guildinsertquery = "INSERT INTO guilds (realmname, guildname, region) VALUES ('$apiRealm', '$apiGuild', '$apiRegion')";
$doinsertquery = mysqli_query($sqlcon, $guildinsertquery);
$toonsortf = array_filter($toonsort);
$toonsortu = array_map('strtolower', $toonsortf);
$realmsortf = array_filter($realmsort);
array_multisort($toonsortu, SORT_ASC, SORT_STRING, $toonsortf, $realmsortf);

for( $i=1; $i<$maxcount; $i++ ) {
  $toonq = "toon".$i;
  $toonqq = $$toonq;
  $toonqu = "toon".$i."u";
  $toonqqu = $$toonqu;
  $realmq = "realm".$i;
  $realmqq = $$realmq;
  $ii = $i - 1;
  $tooninsertquery = mysqli_query($sqlcon, "UPDATE guilds SET " . $toonq . "='" . $toonsortf[$ii] . "' WHERE region='" . $apiRegion . "' AND guildname='" . $apiGuild . "' AND realmname='" . $apiRealm ."'");
  if (!empty($toonsortf[$ii])) {
    $realminsertquery = mysqli_query($sqlcon, "UPDATE guilds SET " . $realmq . "='" . $realmsortf[$ii] . "' WHERE region='" . $apiRegion . "' AND guildname='" . $apiGuild . "' AND realmname='" . $apiRealm ."'");
  }
}

//pre-pop 'api' table
$tooncount = count(array_filter($tooncounter));
$tooncount = $tooncount + 1;

for( $i=1; $i<$tooncount; $i++ ) {
  $apiUrl = "apiUrl".$i;
  $toonqu = "toon".$i."u";
  $toonqqu = $$toonqu;
  $toonapi = utf8_encode($toonqqu);
  $apiStr = "apiStr".$i;

  $realmqz = "realm".$i;
  $realmqqz = $$realmqz;

  $$apiUrl = "http://{$apiRegion}.battle.net/api/wow/character/{$realmqqz}/{$toonapi}?fields=achievements";
  $ch = curl_init($$apiUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 10);
  curl_setopt($ch, CURLOPT_VERBOSE,false);

  $apiStr = curl_exec($ch);
  $apiArr = json_decode($apiStr, true);

  mysqli_query($sqlcon, "DELETE FROM api WHERE region='" . $apiRegion . "' AND toonname='" . $toonqqu . "' AND realmname='" . $realmqqz . "'");
  $apiClass = $apiArr['class'];
  $apiArr = implode(",", $apiArr['achievements']['achievementsCompleted']);

  $arrValuePairs = array('realmname' => $realmqqz, 'toonname' => $$toonqu, 'apiarray' => $apiArr, 'region' => $apiRegion, 'class' => $apiClass);
  standardSQLInsert('api',$arrValuePairs);
}

//Clean-up SQL

$clean1 = 'DELETE FROM api where toonname=""';
$clean2 = 'DELETE FROM guilds where guildname=""';
$dummyvar3 = mysqli_query($sqlcon, $clean1);
$dummyvar4 = mysqli_query($sqlcon, $clean2);
//inserting data order
//$order = "INSERT INTO data_employees
//(name, address)
//VALUES
//('$name',
//'$address')";

//IT PUTS THE DATA IN THE TABLE OR IT GETS THE HOSE
//$dump = mysqli_query($sqlcon, ($order);

$q=mysqli_query($sqlcon, "select id,guildname,realmname,region from guilds order by id desc limit 1");
$qr=mysqli_fetch_assoc($q);
$qrdump= $qr[guildname] . " of " . $qr[realmname] . "-" . $qr[region];

if (!empty($toon1)) {
  echo 'Thanks, your cross-realm group is now in!! <br><br><a class="regular" href="/results.php?region='.$qr[region].'&realm='.$qr[realmname].'&guildname='.$qr[guildname].'&meta=ds"><b>'.$qrdump.'</b></a>';
}
else{
  echo 'Sorry, you forgot to type in any character names. <a class="regular" href="guildimport.php">Please try again</a>.';
}

mysqli_close($sqlcon); 
?>


</body>
</html>