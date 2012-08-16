<?php

require("../../connect.php");

class stats{

        private $data;

        public function __construct($par){
                if(is_array($par))   
                        $this->data = $par;
        }
        
        public function __toString(){
                
                
                $playerinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballstats_players` WHERE id='".$this->data["spiller"]."'"));
                $query = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");
                $return = "";
                $return .= '<form method="post" action="#" id="statsform" name="statsform" class="statsform">';
                $return .= '<table class="stats" cellpadding="0" border="0">';
                $return .= '<tr>';
                $return .= '<td width="150px"><a href="javascript:void(RemovePlayerStats(\''.$playerinfo["id"].'\'))"><img width="15px" src="img/remove.png"></a>'.$playerinfo["fornavn"].' '.$playerinfo["efternavn"].'</td>';
                            while($stattype = mysql_fetch_assoc($query)){
                                        if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="kampid")){
                                                $return .= '<td width="45px" align="center"><input  style="width:30px;text-align:right;" type="text" name="'.$stattype['Field'].'" id="'.$stattype['Field'].'" value="'.$this->data[$stattype['Field']].'"></td>';
                                        }
                            }
                        
                $return .= '<input type="hidden" name="action" id="action" value="edit">
                            <input type="hidden" name="id" id="id" value="'.$this->data["id"].'">';
                $return .= '</tr>';
                $return .= '</table>';
                $return .= '</form>';        
                
                return $return;
                                        
        }
        
        public function changeValues($values){
        
                $str="";
                foreach ($values as $key => $value){
                        if(($key!="id") && ($key!="action"))
                                $str .= "`".$key."`='".$value."',";
                }
                
                // DEBUG
                //$str = substr_replace($str ,"",-1);
                //$myFile = "testFile.txt";
                //$fh = fopen($myFile, 'w');
                //fwrite($fh,$str);
                //fclose($fh);
                
                mysql_query("UPDATE bballstats_stats SET ".$str." WHERE id='".$values['id']."'");
        
        }

}

?>