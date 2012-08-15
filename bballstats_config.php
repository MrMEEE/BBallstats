<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

getThemeHeader();

?>

function ConfirmRemoveType(type){
 
 answer = confirm("Er du sikker på at du vil slette denne type??\n Alle stats af denne type vil blive slettet!!")
 
 if (answer !=0)
 {
   document.typer.fjerntype.value=type;
   typer.submit();
 }
 
}

<?php

getThemeTitle("Statistik Konfiguration");

require("../../menu.php");

if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'bballstats_config'"))){
      mysql_query("CREATE TABLE `bballstats_config` (`id` int(11) NOT NULL AUTO_INCREMENT, `hold` text NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("INSERT INTO `bballstats_config` SET `id`=1,`hold`=''");
      mysql_query("CREATE TABLE `bballstats_players` (`id` int(11) NOT NULL AUTO_INCREMENT, `hold` int(11) NOT NULL , `fornavn` text NOT NULL,`efternavn` text NOT NULL, `beskrivelse` text NOT NULL, `nummer` text NOT NULL, `position` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("CREATE TABLE `bballstats_stats` (`id` int(11) NOT NULL AUTO_INCREMENT, `spiller` int(11) NOT NULL , `hold` int(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
}

$config = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballstats_config` WHERE `id`=1"));

$hold = explode(",",$config['hold']);

if(isset($_GET['add'])){

      if(!in_array($_GET['add'],$hold)){
            array_push($hold,$_GET['add']);
            $holdstr = implode(",",$hold);
            mysql_query("UPDATE `bballstats_config` SET `hold`='".$holdstr."' WHERE `id`='1'");
      }

}

if(isset($_GET['remove'])){

      if(in_array($_GET['remove'],$hold)){
            $hold = array_diff($hold,array($_GET['remove']));
            $holdstr = implode(",",$hold);
            if($holdstr == ","){
                  $holdstr = "";
            }
            mysql_query("UPDATE `bballstats_config` SET `hold`='".$holdstr."' WHERE `id`='1'");
      }
}

$stats = "";
$nostats = "";

$query = mysql_query("SELECT * FROM `calendars`");

while($row = mysql_fetch_assoc($query)){

      if(in_array($row['id'],$hold)){
            $stats .= $row['team'].'  <a href="bballstats_config.php?remove='.$row['id'].'"><img width="15px" src="img/remove.png"></a><br>';
      }else{
            $nostats .= $row['team'].'  <a href="bballstats_config.php?add='.$row['id'].'"><img width="15px" src="img/add.png"></a><br>';
      }

}

echo "<h3>Hold med statistik aktiveret:</h3> <br>".$stats."<br><br>";
echo "<h3>Hold uden statistik aktiveret:</h3> <br>".$nostats."<br><br>";

echo '<h3>Statstyper:</h3><br>';

if(isset($_POST['nytype'])){
      if($_POST['nytype'] != ""){
            if(mysql_num_rows(mysql_query("SHOW COLUMNS FROM `bballstats_stats` WHERE `Field`='".$_POST['nytype']."'"))){
                  $message='<font color="red">"'.$_POST['nytype'].'" findes allerede..</font><br><br>';
            }else{
                  mysql_query("ALTER TABLE `bballstats_stats` ADD `".$_POST['nytype']."` INT NOT NULL");
                  $message='<font color="green">"'.$_POST['nytype'].'" tilføjet...</font><br><br>';
            }
      }
}

if(isset($_POST['fjerntype'])){
      if($_POST['fjerntype'] != ""){
            mysql_query("ALTER TABLE `bballstats_stats` DROP `".$_POST['fjerntype']."`");
            $message='<font color="orange">"'.$_POST['fjerntype'].'" blev slettet...</font><br><br>';
      }
}

echo $message;

$query = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");

while($stattype = mysql_fetch_assoc($query)){
      if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="hold"))
      echo '<a href="javascript:void(ConfirmRemoveType(\''.$stattype["Field"].'\'))"><img width="15px" src="img/remove.png"></a> '.$stattype["Field"].'<br>';
}

echo '<br><form method="post" name="typer" id="typer">
            <input type="text" style="width:100px;" id="nytype" name="nytype">
            <input type="hidden" id="fjerntype" name="fjerntype" value="">
            <input type="submit" id="opretny" name="opretny" value="Tilføj">    
      </form>';

getThemeBottom();

?>
