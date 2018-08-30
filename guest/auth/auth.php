<?php
 /**
 * Обработчик формы авторизации
 * Авторизация пользователя
 */
 

 

//Если нажата кнопка то обрабатываем данные
 if(isset($_POST['submit']))
 {
	//Проверяем на пустоту
	if(empty($_POST['email']))
		$err[] = 'Не введен Логин';
	
	if(empty($_POST['pass']))
		$err[] = 'Не введен Пароль';
	
	//Проверяем email
	if(emailValid($_POST['email']) === false)
		$err[] = 'Не корректный E-mail';

	//Проверяем наличие ошибок и выводим пользователю
	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
	   auth('user');//передаваемое значение можна поменять для авторизации админа
	}
 }


function auth($value){
    global $db;
     $_SESSION['login']=$_POST['email'];
		/*Создаем запрос на выборку из базы 
		данных для проверки подлиности пользователя*/
		$sql = 'SELECT * 
				FROM owners
				WHERE `login` = :email
				AND `active` = 1';
		//Подготавливаем PDO выражение для SQL запроса
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
		$stmt->execute();

		//Получаем данные SQL запроса
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		//Если логин совподает, проверяем пароль
		if(count($rows) > 0)
		{
			//Получаем данные из таблицы
			if(md5(md5($_POST['pass']).$rows[0]['salt']) == $rows[0]['password'])
			{	
				$_SESSION[$value] = true;
				
				//Сбрасываем параметры
				header('Location:'. HOST );
				exit;
			}
			else
				echo showErrorMessage('Неверный пароль!');
		}else{
			echo showErrorMessage('Логин <b>'. $_POST['email'] .'</b> не найден!');
            
            
		}
 }