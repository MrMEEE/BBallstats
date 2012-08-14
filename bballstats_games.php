<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

getThemeHeader();

?>

function FormSubmitTeam(el) {

  var teaminfo = el.value.split(':');

  document.teamlist.teamid.value=teaminfo[0]
  document.teamlist.teamname.value=teaminfo[1]
  teamlist.submit() ;

  return;
}

<?php

getThemeTitle("Statistik Kampe");

require("../../menu.php");

$teamlist = "";

if(mysql_num_rows(mysql_query("SELECT * FROM bballstats_config WHERE id = 1 AND hold = ''"))){

     $teamlist .= '<option value="" selected>Ingen hold aktiveret</option>';

}else{

     $teamlist .= '<option value="">Vælg hold</option>';

     $teams = mysql_fetch_assoc(mysql_query("SELECT * FROM bballstats_config WHERE id = 1"));

     $teams = explode(",",$teams['hold']);

     foreach($teams as $teamid){
          if($teamid != ""){
               $teaminfo = mysql_fetch_assoc(mysql_query("SELECT * FROM calendars WHERE id = '".$teamid."'"));
               $teamlist .= '<option value="'.$teamid.':'.$teaminfo['team'].'"';
               if($_POST['teamid']==$teamid){ 
                     $teamlist .= ' selected';
               }
               $teamlist .= '>'.$teaminfo['team'].'</option>';
          }
     }
}

echo 'Hold:
<form method="post" name="teamlist">
 <select name="teamselect" onChange="FormSubmitTeam(this)">
  '.$teamlist.'
  <input type="hidden" id="teamid" name="teamid" value="">
  <input type="hidden" id="teamname" name="teamname" value="">
 </select>
</form><br>';

$query = mysql_query("SELECT * FROM `games` WHERE team='".$_POST['teamid']."'");

while($game = mysql_fetch_assoc($query)){

     $udehold = explode("Mod ",$game['text']);
     $hjemmehold = explode(":",$game['text']);
     echo $game['id'].' '.$game['date'].' '.$game['time'].' '.$hjemmehold[0].' mod '.$udehold[1].' '.$game['result'].'<br>';

}

getThemeBottom();

?>
