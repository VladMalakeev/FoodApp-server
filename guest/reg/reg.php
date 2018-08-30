<?php
 /**
 * Обработчик формы регистрации
 */

 //Выводим сообщение об удачной регистрации
 if(isset($_GET['status']) and $_GET['status'] == 'ok')
	echo '<h2 class="succes">Вы успешно зарегистрировались! Перейдите на вашу почту и активируйте свой аккаунт!</h2>';
 
 //Выводим сообщение об удачной регистрации
 if(isset($_GET['active']) and $_GET['active'] == 'ok')
	echo '<h2 class="succes">Ваш аккаунт на foodapp.gq успешно активирован!</h2>';
	
 //Производим активацию аккаунта
 if(isset($_GET['key']))
 {
	//Проверяем ключ
	$sql = 'SELECT * 
			FROM owners  
			WHERE `active_hex` = :key';
	//Подготавливаем PDO выражение для SQL запроса
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':key', $_GET['key'], PDO::PARAM_STR);
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if(count($rows) == 0)
		$err[] = 'Ключ активации не верен!';
	
	//Проверяем наличие ошибок и выводим пользователю
	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		//Получаем адрес пользователя
		$email = $rows[0]['login'];
	
		//Активируем аккаунт пользователя
		$sql = 'UPDATE owners
				SET `active` = 1
				WHERE `login` = :email';
		//Подготавливаем PDO выражение для SQL запроса
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		
		//Отправляем письмо для активации
		$title = 'Ваш аккаунт на foodapp.gq успешно активирован';
		$message = 'Ваш аккаунт на foodapp.gq успешно активирован';
			
		sendMessageMail($email, MAIL_AUTOR, $title, $message);
			
		/*Перенаправляем пользователя на 
		нужную нам страницу*/
		header('Location:'. HOST .'?mode=reg&active=ok');
		exit;
	}
 }
 /*Если нажата кнопка на регистрацию,
 начинаем проверку*/
 if(isset($_POST['submit']))
 {
    setcookie('name', $_POST['name'], time()+3600);
    setcookie('email', $_POST['email'], time()+3600);
	//Утюжим пришедшие данные
    if(empty($_POST['name']))
    {
      $err[] = 'Поле name не может быть пустым';  
    }
		
       
    if(!preg_match("/^[a-zA-Z]/", $_POST['name']))   
    {
        $err[]= 'Поле name должно состоять из латинских букв'; 
    }
        
    if((strlen($_POST['name'])<2)||(strlen($_POST['name'])>25))
    {
    $err[]= 'Поле name должно быть длиной от 2 до 25 знаков ';
    } 
    
    
	if(empty($_POST['email']))
		$err[] = 'Поле Email не может быть пустым!';
	else
	{
		if(emailValid($_POST['email']) === false)
           $err[] = 'Не правильно введен E-mail'."\n";
	}
	
	if(empty($_POST['pass']))
		$err[] = 'Поле Пароль не может быть пустым';
  
	
	if(empty($_POST['pass2']))
		$err[] = 'Поле Подтверждения пароля не может быть пустым';
	
        
         if(strlen($_POST['pass'])<6)   
     $err[] = 'Поле Пароль должно содержать не менее 6 символов';
     
	//Проверяем наличие ошибок и выводим пользователю
	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		/*Продолжаем проверять введеные данные
		Проверяем на совподение пароли*/
		if($_POST['pass'] != $_POST['pass2'])
			$err[] = 'Пароли не совподают';
			
		//Проверяем наличие ошибок и выводим пользователю
	    if(count($err) > 0)
			echo showErrorMessage($err);
		else
		{
			/*Проверяем существует ли у нас 
			такой пользователь в БД*/
			$sql = 'SELECT `login` 
					FROM owners
					WHERE `login` = :login';
			//Подготавливаем PDO выражение для SQL запроса
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':login', $_POST['email'], PDO::PARAM_STR);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($rows) > 0)
				$err[] = 'Такой e-mail: <b>'. $_POST['email'] .'</b> уже существует!';
                
                
			//Проверяем наличие ошибок и выводим пользователю
			if(count($err) > 0)
				echo showErrorMessage($err);
			else
			{
				//Получаем ХЕШ соли
				$salt = salt();
				
				//Солим пароль
				$pass = md5(md5($_POST['pass']).$salt);
                
				/*Если все хорошо, пишем данные в базу*/
                $db->exec("ALTER TABLE users AUTO_INCREMENT=1");
				$sql = 'INSERT INTO owners
						VALUES(
								"",
                                :name,
								:email,
								:password,
								:salt,
								"'. md5($salt) .'",
								0)';
				//Подготавливаем PDO выражение для SQL запроса
				$stmt = $db->prepare($sql);
   	            $stmt->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
				$stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
				$stmt->bindValue(':password', $pass, PDO::PARAM_STR);
				$stmt->bindValue(':salt', $salt, PDO::PARAM_STR);
				$stmt->execute();
                
                //создаем таблицу меню
                $tableName = "menu_".$_POST['name'];
                $db->exec("create table $tableName(
                id int(11) AUTO_INCREMENT primary key,
                name varchar(255),
                descript varchar(255),
                price double )");
				
                //таблица инфо
                 $tableOrders = "orders_".$_POST['name'];
                $db->exec("create table $tableOrders(
                id int(11) AUTO_INCREMENT primary key,
                dish_name varchar(255),
                user_name varchar(255),
                new_order tinyint(0),
                confirmed tinyint(0),
                latitude double,
                longitude double,
                order_time varchar(15),
                count int(11),
				price float				
                 )");
				 
				 //таблица с историей
				 
				  $tableHistory = "history_".$_POST['name'];
                $db->exec("create table $tableHistory(
                id int(11) AUTO_INCREMENT primary key,
                dish_name varchar(255),
                user_name varchar(255),
                paid tinyint(0),
                refused tinyint(0),
                latitude double,
                longitude double,
                order_time varchar(15), 
				count int(11),
				price float	
                 )");
                
				//Отправляем письмо для активации
				$url = HOST .'?mode=reg&key='. md5($salt);
				$title = 'Регистрация на foodapp.gq';
				$message = 'Для активации Вашего акаунта пройдите по ссылке 
				<a href="'. $url .'">'. $url .'</a>';
				
				sendMessageMail($_POST['email'], MAIL_AUTOR, $title, $message);
				
				//Сбрасываем параметры
				header('Location:'. HOST .'?mode=reg&status=ok');
				exit;
			}
		}
	}
 }
 
