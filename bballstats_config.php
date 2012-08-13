<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

if (!checkAdmin($_SESSION['username'])){
      ob_start();
      header( "Location: index.php" );
      ob_flush();
}
getThemeHeader();

getThemeTitle("Statistik Konfiguration");

require("../../menu.php");

if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'bballstats_config'"))){
      mysql_query("CREATE TABLE `bballstats_config` (`id` int(11) NOT NULL AUTO_INCREMENT, `hold` text NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
      mysql_query("INSERT INTO `bballstats_config` SET `id`=1");
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


getThemeBottom();

?>