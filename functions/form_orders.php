<?php

session_start();
define('KEY', true);

include('../config/config.php');

	$orders_owners = "orders_".$_SESSION['name'];
    $menu_owners = "menu_".$_SESSION['name'];


//извлекаем новые заказы;
$active_orders=array();
$ord_tmp=$db->query("select * from $orders_owners where new_order=1");
$i=0;
while($order_map=$ord_tmp->fetch(PDO::FETCH_ASSOC)){
 $active_orders[$i]=array(  "id" => $order_map['user_name'],
                        "lat" => $order_map['latitude'],
                        "lng" => $order_map['longitude'],
                        "dish" => $order_map['dish_name'],
                        "count" => $order_map['count'],
                        "price" => $order_map['price'],
                        "time" => $order_map['order_time']                        
 ); 
 $i++;  
}
 
    
//добавляем заказы в обработку

$progress_orders=array();
$ord_tmp2=$db->query("select * from $orders_owners where confirmed=1");
$j=0;
while($order_map2=$ord_tmp2->fetch(PDO::FETCH_ASSOC)){
 $progress_orders[$j]=array(  "id" => $order_map2['user_name'],
                        "lat" => $order_map2['latitude'],
                        "lng" => $order_map2['longitude'],
                        "dish" => $order_map2['dish_name'],
                        "count" => $order_map2['count'],
                        "price" => $order_map2['price'],
                        "time" => $order_map2['order_time']                        
 ); 
 $j++;  
}
 
$count_active=count($active_orders);
$count_comfirm=count($progress_orders);
$main_array = array("new_order" => array("count" => $count_active,
										 "data" => $active_orders),
				    "confirmed" => array("count" => $count_comfirm,
					                     "data" => $progress_orders)
										 );
if(isset($_POST['newdata'])){				  
echo json_encode($main_array);
}














