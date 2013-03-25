<html lang="en">
<head>
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

//connect to mysql via mysqli
$sqlcon = mysqli_connect("localhost","metaparser",$metapassword,"metaparser");

//grab the guildname from the GET data
$guildname = mysqli_real_escape_string($sqlcon, $_GET['guildname']);
$guildnamei = urldecode($guildname);

?>

<title><? echo $guildnamei; ?> - WoW Meta Check</title>

<?php

require './includes/header.php';

?>



</head>
<body>
<div id="wrap">
<div id="tester">
<center>
<a href="/guildselect.php"><img src=/img/banner.png></a>
<font color="#FFFFFF">

<?php

//gather the important variables via GET
$meta = mysqli_real_escape_string($sqlcon, $_GET['meta']);
$apiRealm = mysqli_real_escape_string($sqlcon, $_GET['realm']);
$realmname = $apiRealm;
$region = mysqli_real_escape_string($sqlcon, $_GET['region']);
$apiRegion = $region;
//encode the guild/realm URL info for linking later
$guildurl = urlencode($guildname);
$realmurl = urlencode($realmname);
echo "<h1><font color=\"#FFD700\">$guildnamei</font></h1>";

?>

<table>
<tr>
<td>
<font color="#FFFFFF" size="4"><p class="centermeta"><b>Pick a different meta:</b></p></font>
</td>
<td>
<form action="results.php" method="get" style="margin: 0; text-align: center;">
<input type="hidden" name="region" value="<? echo $region; ?>" />
<input type="hidden" name="realm" value="<? echo $realmname; ?>" />
<input type="hidden" name="guildname" value="<? echo $guildname; ?>" />
<select class="inputclass3" name="meta">

<?

//Sort metas identically
$sortedmetas = "<option disabled='disabled'> </option>
<option disabled='disabled'>--Mists of Pandaria--</option>
<option value='mop'>Tier 14 - HoF/MV/ToES</option>
<option value='mopd'>Tier 14 - Dungeons</option>
<option disabled='disabled'> </option>
<option disabled='disabled'>--Cataclysm--</option>
<option value='ds'>Tier 13 - Dragon Soul</option>
<option value='fl'>Tier 12 - Firelands</option>
<option value='t11'>Tier 11 - BWD/BoT/T4W</option>
<option value='catad'>Tier 11 - Dungeons</option>
<option disabled='disabled'> </option>
<option disabled='disabled'>--Wrath of the Lich King--</option>
<option value='icc'>Tier 10 - Icecrown Citadel</option>
<option disabled='disabled'> </option>
<option value='all'>**All Metas**</option>";

if ($meta=="ds") {
  echo "<option value='ds'>Tier 13 - Dragon Soul</option>".$sortedmetas;
}
elseif ($meta=="mop") {
  echo "<option value='mop'>Tier 14 - HoF/MV/ToES</option>".$sortedmetas;
}
elseif ($meta=="all") {
  echo "<option value='all'>**All Metas**</option>".$sortedmetas;
}
elseif ($meta=="fl") {
  echo "<option value='fl'>Tier 12 - Firelands</option>".$sortedmetas;
}
elseif ($meta=="t11") {
  echo "<option value='t11'>Tier 11 - BWD/BoT/T4W</option>".$sortedmetas;
}
elseif ($meta=="mopd") {
  echo "<option value='mopd'>Tier 14 - Dungeons</option>".$sortedmetas;
}
elseif ($meta=="catad") {
  echo "<option value='catad'>Tier 11 - Dungeons</option>".$sortedmetas;
}
elseif ($meta=="icc") {
  echo "<option value='icc'>Tier 10 - Icecrown Citadel</option>".$sortedmetas;
}

?>

</select></td><td><p class="centermeta">
<INPUT type="submit" value="Go!"></p></td>

</tr>
</table>
<br>




<?

//retrive the toon from the guilds table to create the tooncount
$guildquery = "SELECT * FROM guilds WHERE guildname='$guildname' AND region='$region' AND realmname='$realmname'";
$runguildquery = mysqli_query($sqlcon, $guildquery);
$guildarray = mysqli_fetch_row($runguildquery);
$tooncount = count(array_filter($guildarray));
//the "3" here is the id, the guild name, and the realm name
$tooncount = $tooncount - 3;
$tooncount = $tooncount/2;

//retrive the meta criteria from the achievement table
if ($meta == "all") {
  $metaquery = "SELECT meta, area, name from metas";
}
else{
  $metaquery = "SELECT meta, area, name from metas where meta='".$meta."'";
}

$metaresult = mysqli_query($sqlcon, $metaquery);


while ($metarow = mysqli_fetch_assoc($metaresult)) {
  $achievequery = "SELECT number,section FROM achievements WHERE meta='".$metarow["meta"]."' and area='".$metarow["area"]."' order by sort asc";
  $achieveresult = mysqli_query($sqlcon, $achievequery);
  $areadump = $metarow["meta"].$metarow["area"];

  while ($row = mysqli_fetch_assoc($achieveresult)) {
    $achievearray[$areadump][] = $row['number'];
    $sectiondump = "_" . $row['number'] . "Section";
    $$sectiondump = $row['section'];
  }
}

$namequery = "SELECT meta, area, name from metas";
$nameresult = mysqli_query($sqlcon, $namequery);

while ($namerow = mysqli_fetch_assoc($nameresult)) {
  $namedump = $namerow["meta"].$namerow["area"];
  $titlearray[$namedump] = $namerow["name"];
}


//big loop to retrieve individual toons and then check their achieves
$toonarray = array();
$columntotal = array();

foreach ($achievearray as $keydump => $v) {
  foreach ($v as $vv) {
    $columntotal[$keydump][$vv] = 0;
  }
}

for( $i=1; $i<$tooncount; $i++ ) {
  $toonq = "toon".$i;
  $tooni = "toon".$i."i";
  $realmq = "realm".$i;

  $gettoon = "SELECT $toonq FROM guilds WHERE region='$region' AND guildname='$guildname' AND realmname='$realmname'";
  $runtoonquery = mysqli_query($sqlcon, $gettoon);
  $toonrow = mysqli_fetch_row($runtoonquery);
  $toonrowdump = $toonrow[0];
  $toonarray[$toonrowdump] = array();

  $getrealm = "SELECT $realmq FROM guilds WHERE region='$region' AND guildname='$guildname' AND realmname='$realmname'";
  $getrealmquery = mysqli_query($sqlcon, $getrealm);
  $realmrow = mysqli_fetch_row($getrealmquery);
  $realmrowdump = $realmrow[0];
  $toonarray[$toonrowdump]['realm'] = $realmrowdump;
}


//retrieve the apiarray value from the api table for each toon
foreach($toonarray as $toonkey => $toonvalue) {
  $toondump = $toonkey;
  $apiTest = array();
  $query = "SELECT apiarray FROM api WHERE region='$region' AND toonname='".$toonkey."' and realmname='".$toonvalue['realm']."'";
  $runquery = mysqli_query($sqlcon, $query);
  $result = mysqli_fetch_row($runquery);
  $result = $result[0];
  $apiArr = explode(",", $result);

  //Magic Loop - this converts the exploded array into my new apiTest array by naming each value after itself -- so achievement 4154 becomes apiTest[4154]
  //trash code from before -- just in case
  //for( $q=0; $q<$apiCount; $q++ )
  //{
  //$qdump = $apiDump[$q];
  //$apiTest[$qdump] = $qdump;
  foreach ($apiArr as $v) {
    $apiTest[$v] = $v;
  }

  //get class info for class-coloring to apply the right CSS later

  $classquery = "SELECT class FROM api WHERE region='$region' AND toonname='".$toonkey."' AND realmname='".$toonvalue['realm']."'";
  $runclassquery = mysqli_query($sqlcon, $classquery);
  $classrow = mysqli_fetch_row($runclassquery);
  $toonarray[$toonkey]['class'] = $classrow[0];

  //now that the apiTest arrays have been created, we need to start incrementing our counters if a match is found. isset() ends up being much faster than our old arrayintersect() method, but could possibly be improved further
  foreach ($achievearray as $keydump => $v) {
    foreach ($v as $vv) {
      if (isset($apiTest[$vv])) {
        $toonarray[$toondump][$keydump][$vv] = 1;
        $columntotal[$keydump][$vv]++;
      }
      else{
        $toonarray[$toondump][$keydump][$vv] = 0;
      }
    }

    $achievecount = count($achievearray[$keydump]);
    $toontotal[$toondump][$keydump] = array_sum($toonarray[$toondump][$keydump]);
    $toonscore[$toondump][$keydump] = $achievecount - $toontotal[$toondump][$keydump];
    if ($toontotal[$toondump][$keydump] == $achievecount) {
      $rowclass[$toondump][$keydump] = "goodptally";
    }
    else{
      $rowclass[$toondump][$keydump] = "badptally";
    }
  }
}

//we need to make sure we have an even number...
$toonround = round($tooncount, 0, PHP_ROUND_HALF_DOWN);

foreach ($achievearray as $keydump => $v) {
  foreach ($v as $vv) {
    $subtract = $columntotal[$keydump][$vv];
    $columnscore[$keydump][$vv] = $toonround - $subtract;
    if ($subtract == $toonround) {
      $headerclass[$keydump][$vv] = "complete";
      $columnclass[$keydump][$vv] = "goodtally";
    }
    else{
      $headerclass[$keydump][$vv] = "incomplete";
      $columnclass[$keydump][$vv] = "badtally";
    }
  }
}

//break out all of the class colors and assign the CSS classes
foreach ($toonarray as $toonkey => $toonvalue) {

  $toonclass = $toonarray[$toonkey]['class'];

  if ($toonclass >=1 && $toonclass <=12) {
    $toonarray[$toonkey]['classcolor'] = "classcolor".$toonclass;
  }
  else{
    $toonarray[$toonkey]['classcolor'] = "classcolorx";
  }
}

//this is my very simple alternating function that lets me alternate the row coloring


//the following sections are for making the table/display for each meta achievement
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//create the wowhead links and define the header row
foreach($achievearray as $keydump => $v) {
  $class = "rowclass1";
  echo '<table cellpadding="3"><thead><tr><th class="player">'.$titlearray[$keydump].'</th>';

  foreach ($v as $vv) {
    echo '<th class="' . $headerclass[$keydump][$vv] . '"><a href="http://www.wowhead.com/achievement=' . $vv . '"><img src=/img/' . $vv . '.jpg></a></th>';
  }

  echo '</tr></thead><tbody>';
  //create a row for each toon and dump their achievement status
  foreach($toonarray as $toonkey => $toonvalue ) {
  
    if($class == "rowclass0"){
      $class = "rowclass1";
    }
    else{
      $class = "rowclass0";
    }

    echo '<tr ><td class=' . $class . '><b><a class="' . $toonvalue['classcolor'] . '" href="http://' . $region . '.battle.net/wow/en/character/' . $toonvalue['realm'] . '/' . $toonkey . '/simple">' . $toonkey . '</a><b></td>';

    foreach ($v as $vv) {
      $sectiondumpd = "_" . $vv . "Section";
      $sectiondumpdd = $$sectiondumpd;
      echo '<td class=' . $class . '><center><a href="http://' . $region. '.battle.net/wow/en/character/' . $toonvalue['realm'] . '/' . $toonkey . '/achievement#' . $sectiondumpdd . ':a' . $vv . '"><img src=/img/'. $toonarray[$toonkey][$keydump][$vv] . '.png></a></center></td>';
    }

    echo '<td class="' . $rowclass[$toonkey][$keydump] . '"><b>' . $toonscore[$toonkey][$keydump] . '</b></td>';
    echo '</tr>';
  }

  echo '<tr ><td></td>';

  foreach ($v as $vv) {
    echo '<td class="' . $columnclass[$keydump][$vv] . '"><b>' . $columnscore[$keydump][$vv] . '</b></td>';
  }

  echo '</tr></tbody></table><br><br>';
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Is your guild all messed up? <b><a href="#" class="regular" onclick="wipeDB();">Wipe it!</a></b>
//<br><br>

?>
</center></font>
</div>
<div id="push"></div>

</div>

<footer class="navbar navbar-fixed-bottom">

<p class="muted credit"><center><b><a href="javascript:postwith('guildedit.php', {realmname: '<? echo $realmname; ?>', guildname: '<? echo $guildname; ?>', region: '<? echo $region; ?>'})" class="regular">edit roster</a> <font color=white>| <a href="#" class="regular" onclick="importSQL();">refresh from armory</a> <font color=white>|</font> </font> <a href="guildselect.php" class="regular"><b>home</b></a></center></p>

</footer>



<script type="text/javascript" src="/includes/jquery.min.js"></script>
<script type="text/javascript" src="/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
redirectURLf = "results.php<? echo "?guildname=$guildurl&realm=$realmurl&region=$region&meta=$meta" ?>"
function importSQL() {
$.post("armoryrefresh.php", { realmname: "<? echo $realmname; ?>", guildname: "<? echo $guildname; ?>", region: "<? echo $region; ?>"});
setTimeout("location.href = redirectURLf;",5000);
}

function postwith (to,p) {
var myForm = document.createElement("form");
myForm.method="post" ;
myForm.action = to ;
for (var k in p) {
var myInput = document.createElement("input") ;
myInput.setAttribute("name", k) ;
myInput.setAttribute("value", p[k]);
myForm.appendChild(myInput) ;
}
document.body.appendChild(myForm) ;
myForm.submit() ;
document.body.removeChild(myForm) ;
}


</script>
</body>

<? mysqli_close($sqlcon); ?>
</html>