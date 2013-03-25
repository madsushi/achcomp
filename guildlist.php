<?
require './includes/safesqli.php';
	
//First, connect to MySQL totally securely
$sqlcon = mysqli_connect("localhost","metaparser",$metapassword,"metaparser");
$q=mysqli_query($sqlcon, "select id,guildname,realmname,region from guilds order by id desc");
echo "<table cellpadding=4 cellspacing=4>";

while($qr=mysqli_fetch_assoc($q)) {
  echo "<tr><td>$qr[id]</td><td>$qr[guildname]</td><td>$qr[realmname]</td><td>$qr[region]</td></tr>";
}

echo "</table>";
mysqli_close($sqlcon); 
?>