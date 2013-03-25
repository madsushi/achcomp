<?
require './includes/safesqli.php';
	
//First, connect to MySQL totally securely
$sqlcon = mysqli_connect("localhost","metaparser",$metapassword,"metaparser");
$realmid=mysqli_real_escape_string($sqlcon, $_GET['realm']);
$q=mysqli_query($sqlcon, "select guildname from guilds where realmname='$realmid' order by guildname asc");
$myarray=array();
$realmstr="";

while($nt=mysqli_fetch_assoc($q)) {
  $ntencode= utf8_encode($nt['guildname']);
  $realmstr=$realmstr . "\"$ntencode\"".",";
}

$realmstr=substr($realmstr,0,(strLen($realmstr)-1)); // Removing the last char , from the string
echo "new Array($realmstr)";
mysqli_close($sqlcon); 
?>