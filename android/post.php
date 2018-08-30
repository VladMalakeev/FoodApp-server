<?php
//запись даных в бд
if(isset($_POST['lat'], $_POST['lng'], $_POST['id'], $_POST['owner'], $_POST['dish_name'],$_POST['count'] )){
    $id=$_POST['id']; 
    $lat=$_POST['lat'];
    $lng=$_POST['lng'];  
    $owner=$_POST['owner'];
    $dish_name=$_POST['dish_name'];
    $count = $_POST['count'];
    $tname='orders_'.$owner;
    $mname = 'menu_'.$owner;
    
    
   
    $time = time();
    $db->exec("ALTER TABLE $tname AUTO_INCREMENT=1");
    $db->exec("insert into $tname(dish_name, user_name, new_order, confirmed, latitude,longitude,order_time,count,price) values(
    (select id from $mname where name='$dish_name'),
    $id,
    1,
    0,
    $lat,
    $lng,
    $time,
    $count,
    $count*(select price from $mname where name = '$dish_name')
     )");
     
    
}
else{
    $lat=0;
    $lng=0;
    $id=null;
    $owner=null;
    $dish_name=null;
}
