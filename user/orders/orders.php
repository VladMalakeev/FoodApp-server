 <?php
//обработка запросов
if(isset($_POST['accept'])){
    $id = $_POST['accept'];
	$db->exec("update $orders_owners set new_order=0, confirmed=1 where id ='$id' ");
}

if(isset($_POST['reject'])){
    $id = $_POST['reject'];
    $db->exec("update $orders_owners set new_order=0,confirmed=0, refused=1 where id ='$id' ");
}

if(isset($_POST['delivered'] ) || isset($_POST['reject'])){
 	
	if(isset($_POST['delivered'] )){
		$id=$_POST['delivered'];
		$paid=1;
		$refused=0;
	}
	if(isset($_POST['reject'])){
		$id=$_POST['reject'];
		$paid=0;
		$refused=1;
	}
	
	$tmp=$db->query("select * from $orders_owners where id = '$id'");
	$data_orders=$tmp->fetch(PDO::FETCH_ASSOC);
	$dish_name=$data_orders['dish_name']; 
	$user_name=$data_orders['user_name']; 
	$latitude=$data_orders['latitude']; 
	$longitude=$data_orders['longitude']; 
	$order_time=$data_orders['order_time']; 
	$count=$data_orders['count']; 
	$price=$data_orders['price']; 
	
	
    $db->exec("insert into $history_owners ( dish_name, user_name, paid, refused, latitude, longitude, order_time, count, price)
	values( '$dish_name', '$user_name', $paid, $refused, $latitude, $longitude, '$order_time', $count, $price) ");
	$db->exec("delete from $orders_owners where id='$id' ");
}

?>