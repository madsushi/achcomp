<?php
	require './includes/safesqli.php';
//PHP TIME, BITCHES;

//First, connect to MySQL totally securely
	$sqlcon = mysqli_connect("localhost","metaparser",$metapassword,"metaparser");
    require './includes/strip.php';
	//mysql_query("set names 'utf8'");

//Let's get started;

	$apiRealm=mysqli_real_escape_string($sqlcon, $_POST['realmname']);
	$apiGuild=mysqli_real_escape_string($sqlcon, $_POST['guildname']);
	$apiRegion=mysqli_real_escape_string($sqlcon, $_POST['region']);
	
	$maxcount = 36;

for( $i=1; $i<$maxcount; $i++ ) {
  $toonq = "toon".$i;
  $tooni = "toon".$i."i";
  $realmq = "realm".$i;

  $gettoon = "SELECT $toonq FROM guilds WHERE region='$apiRegion' AND guildname='$apiGuild' AND realmname='$apiRealm'";
  $runtoonquery = mysqli_query($sqlcon, $gettoon);
  $toonrow = mysqli_fetch_row($runtoonquery);
  $$toonq = $toonrow[0];
  $$tooni = $$toonq;

  $getrealm = "SELECT $realmq FROM guilds WHERE region='$apiRegion' AND guildname='$apiGuild' AND realmname='$apiRealm'";
  $getrealmquery = mysqli_query($sqlcon, $getrealm);
  $realmrow = mysqli_fetch_row($getrealmquery);
  $$realmq = $realmrow[0];
}

	
		for( $i=1; $i<$maxcount; $i++ )
			{
			$toonq = "toon".$i;
			$toonin = $$toonq;

      if(!empty($toonin)){
			$apiUrl = "apiUrl".$i;
			$toonqu = "toon".$i."u";
			//$toonqqu = $$toonqu;
			$apiStr = "apiStr".$i;
			$toonapi = utf8_encode($toonin);
			$tooninq = utf8_decode($toonin);
			
			$realmqz = "realm".$i;
			$realmqqz = $$realmqz;
      
        
        $$apiUrl = "http://{$apiRegion}.battle.net/api/wow/character/{$realmqqz}/{$toonapi}?fields=achievements";
        $ch = curl_init($$apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_VERBOSE,	false);
        
        $apiStr = curl_exec($ch);
        $apiArr = json_decode($apiStr, true);
      
    
      //Pipe it into MySQL


        mysqli_query($sqlcon, "DELETE FROM api WHERE region='" . $apiRegion . "' AND toonname='" . $toonin . "' AND realmname='" . $realmqqz . "'");			
        $apiClass = $apiArr['class'];
        $apiArr = implode(",", $apiArr['achievements']['achievementsCompleted']);

        $arrValuePairs = array('realmname' => $realmqqz, 'toonname' => $toonin, 'apiarray' => $apiArr, 'region' => $apiRegion, 'class' => $apiClass);
        standardSQLInsert('api',$arrValuePairs);
        }
			
			}
	
	//Clean-up SQL
	$clean1 = 'DELETE FROM api where toonname=""';
	$clean2 = 'DELETE FROM guilds where guildname=""';
	$vardump1 = mysqli_query($sqlcon, $clean1);
	$vardump2 = mysqli_query($sqlcon, $clean2);
	
	mysqli_close($sqlcon); 
?>