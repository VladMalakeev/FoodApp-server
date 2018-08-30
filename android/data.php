<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
define('KEY', true);
require('../config/config.php');
header('Content-Type: text/html; charset=UTF8');

$arrInfo = array();
$i=0;
$result=$db->query("select name from owners");
while($owner=$result->fetch(PDO::FETCH_ASSOC)){
    $table='menu_'.$owner['name'];
    $result2=$db->query("select * from $table");
    
	$j=0;
    $arrMenu=array();	
while($menu=$result2->fetch(PDO::FETCH_ASSOC)){
     	  $arrMenu[$j]=array(
                            "name_menu" => $menu['name'],
                            "descript" => $menu['descript'],
                            "price" => $menu['price']
						   );
			$j++;
			
    }
	$arrInfo[$i]=array(
        "name_owners" => $owner['name'],
        "menu" =>$arrMenu);
     $i++;
}
$owners=array();
$owners=array("owners"=>$arrInfo);


echo json_encode( $owners, JSON_UNESCAPED_UNICODE )."enddead";

?>