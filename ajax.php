<?php

require("../../connect.php");
require "bballstats_stats_class.php";


//$id = (int)$_POST['id'];

try{

	switch($_POST['action'])
	{
		case 'edit':
			stats::changeValues($_POST);
			break;
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

?>
