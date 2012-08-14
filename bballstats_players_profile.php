<?php 

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

getThemeHeader();

?>

function loadinparent(id){

    if ((document.spiller.fornavn.value=="") || (document.spiller.fornavn.value=="Udfyld fornavn")){
         document.spiller.fornavn.value="Udfyld fornavn";
    }else{
         opener.document.spiller.fornavn.value=document.spiller.fornavn.value;
         opener.document.spiller.efternavn.value=document.spiller.efternavn.value;
         opener.document.spiller.nummer.value=document.spiller.nummer.value;
         opener.document.spiller.id.value=document.spiller.id.value;
         opener.document.spiller.teamid.value=document.spiller.teamid.value;
         opener.document.spiller.beskrivelse.value=document.spiller.beskrivelse.value;
         opener.document.spiller.position.value=document.spiller.position.value;
         opener.document.spiller.submit();
         window.close();
    }

}

function formfocus() {
   document.getElementById('fornavn').focus();
}
window.onload = formfocus;

<?php

getThemeTitle("Spiller");


if($_GET['id'] == "-1"){
     $operation = "Opret";
}else{
     $operation = "Opdater";
     $player = mysql_fetch_assoc(mysql_query("SELECT * FROM bballstats_players WHERE id='".$_GET['id']."'"));
}

$teamlist .= '<option value=""';
     
$teams = mysql_fetch_assoc(mysql_query("SELECT * FROM bballstats_config WHERE id = 1"));
     
$teams = explode(",",$teams['hold']);
     
foreach($teams as $teamid){
     if($teamid != ""){
         $teaminfo = mysql_fetch_assoc(mysql_query("SELECT * FROM calendars WHERE id = '".$teamid."'"));
         $teamlist .= '<option value="'.$teamid.'"';
         if($_POST['teamid']==$teamid){
               $teamlist .= ' selected';
         }
         $teamlist .= '>'.$teaminfo['team'].'</option>';
     }
}

?>

<form method="post" id="spiller" name="spiller" action="javascript:loadinparent()">
Fornavn: <input id="fornavn" type="text" name="fornavn" value="<?php echo $player['fornavn'] ?>"><br>
Efternavn: <input id="efternavn" type="text" name="efternavn" value="<?php echo $player['efternavn'] ?>"><br>
Hold: <select name="teamid" id="teamid"><?php echo $teamlist ?></select><br>
Nummer: <input id="nummer" type="text" name="nummer" value="<?php echo $player['nummer'] ?>"><br>
Position: <input id="position" type="text" name="position" value="<?php echo $player['position'] ?>"><br>
Beskrivelse: <textarea rows="8" cols="50" id="beskrivelse" name="beskrivelse"><?php echo $player['beskrivelse'] ?></textarea><br>
<input type="hidden" id="id" name="id" value="<?php echo $_GET['id'] ?>">
<!-- <input type="hidden" id="teamid" name="teamid" value="<?php echo $_GET['teamid'] ?>">-->
<input name="opdater" type="submit" value="<?php echo $operation ?>">
</form>

<?php

getThemeBottom();

?>
