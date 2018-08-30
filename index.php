<?php
session_start();
//общие настройки
header('Content-Type: text/html; charset=UTF8');

//отображения ошибок
ini_set('display_errors',1);
error_reporting(E_ALL);


//Включаем буферизацию содержимого
ob_start();

//Определяем переменную для переключателя
$mode = isset($_GET['mode'])  ? $_GET['mode'] : false;
$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;

$err = array();

//Устанавливаем ключ защиты
	define('KEY', true);

//соединение с бд
require('./config/config.php');

//подключаем функции
require('./functions/functions.php');

$link = $_SERVER['REQUEST_URI']; 

//принимаем заказ с андроида
include('./android/post.php');



if($user === false){
 
 	switch($mode)
	{
		//Подключаем обработчик с формой регистрации
		case 'reg':
			include './guest/reg/reg.php';
			include './guest/reg/reg_form.html';
           
		break;
		
		//Подключаем обработчик с формой авторизации
		case 'auth':
			include './guest/auth/auth.php';
			include './guest/auth/auth_form.html';
		break;
        
    
	}
    
    //Получаем данные с буфера
$content = ob_get_contents();
ob_end_clean();

//Подключаем наш шаблон
include './guest/html/index.html';

}

 
if($user === true) {  
     
    //данные о заведении
    $login=$_SESSION['login'];
    $data=$db->query("SELECT * FROM owners WHERE login='$login'")->fetch(PDO::FETCH_ASSOC);
    $_SESSION['name']=$data['name'];
	$orders_owners = "orders_".$data['name'];
    $menu_owners = "menu_".$data['name'];
	$history_owners = "history_".$data['name'];

	
	//обработка заказов
require('./user/orders/orders.php');
require('./user/orders/form_orders.php');
   //добавление нового меню
require('./user/menu/form_menu.php'); 

	//html файл личного кабинета
include('./user/index.html'); 

	
    
    //Выход из авторизации
 if(isset($_GET['exit']) == true){
 	//Уничтожаем сессию
 	session_destroy();

 	//Делаем редирект
 	header('Location:'. HOST .'?mode=auth');
 	exit;
 }
 }

?>
