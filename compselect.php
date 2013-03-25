<html>

<head>
<title>ArmoryPlus.com</title>
<script type="text/javascript" src="/includes/power.js"></script>
<script type="text/javascript" src="/includes/jquery.min.js"></script>
  <link href="/css/select2.css" rel="stylesheet"/>
    <script src="/js/select2/select2.js"></script>
<?php

require './includes/safesqli.php';
require './includes/header.php';
//connect to mysql via mysqli
$sqlcon = mysqli_connect("localhost","metaparser",$metapassword,"metaparser");
//get the latest guild added to display later
?>

<script type="text/javascript" src="/includes/guildselectajax.js"></script>
<link rel="stylesheet" href="/css/bootstrap.css" type="text/css" media="screen" title="master" charset="utf-8">
<link rel="stylesheet" href="/css/user.css" type="text/css" media="screen" title="master" charset="utf-8">
<link rel="stylesheet" href="/includes/style.css" type="text/css" media="screen" title="master" charset="utf-8">
</head>

<body bgcolor="#111111">
<?php
	require './includes/navbar.php';
?>

<center>
<font color="#FFFFFF" size="2">
<h3>Compare Achievements</h3>
<img src="/i/demoachcomp.png"><br><br>

<form name="chooser" action="results.php" method="get">


<table cellpadding="10">
  <tr>
    <td>
      <font color="white"><b>Player 1</b>
    </td>
      <td>
        <select class="inputclass4 select2" name="region1">
          <option value="us">Region</option>
          <option value="us">US</option>
          <option value="eu">EU</option>
        </select>
      </td>
      <td>
        <select class="inputclass4 select2" name="realm1">
          <option value="malganis">Realm</option>
            <?php
              $realmlist=mysqli_query($sqlcon, "select realmname, fancyrealmname from realms");

              while($realmarray = mysqli_fetch_assoc($realmlist)) {
                $realmarrayrealmname=$realmarray["realmname"];
                $realmarrayfancyname=$realmarray["fancyrealmname"];
                echo '<option value="'.$realmarrayrealmname.'">'.$realmarrayfancyname.'</option>';
              }
            ?>
          </select>
        </td>
      <td>
        <input type="text" class="inputclass2" name="playername1" style="height:28px;" placeholder="Name"></select>
      </td>
  </tr>

  
  <tr><td><br><br></td></tr>
  
  <tr>
    <td>
      <font color="white"><b>Player 2</b>
    </td>
      <td>
        <select class="inputclass4 select2" name="region2">
          <option value="us">Region</option>
          <option value="us">US</option>
          <option value="eu">EU</option>
        </select>
      </td>
      <td>
        <select class="inputclass4 select2" name="realm2">
          <option value="malganis">Realm</option>
            <?php
              $realmlist=mysqli_query($sqlcon, "select realmname, fancyrealmname from realms");

              while($realmarray = mysqli_fetch_assoc($realmlist)) {
                $realmarrayrealmname=$realmarray["realmname"];
                $realmarrayfancyname=$realmarray["fancyrealmname"];
                echo '<option value="'.$realmarrayrealmname.'">'.$realmarrayfancyname.'</option>';
              }
            ?>
          </select>
        </td>
      <td>
        <input type="text" class="inputclass2" name="playername2" style="height:28px;" placeholder="Name"></select>
      </td>
      </tr>
      <tr>
    <td></td><td></td><td></td><td><INPUT type="submit" class="btn-success pull-right" value="Compare!"></td>
  </tr>
</table>


</form>
    <script>
        $('.select2').select2();
        $('button[data-select2-open]').click(function(){
        $('#' + $(this).data('select2-open')).select2('open');
      });

    </script>

<br><br>
</body>
</html>