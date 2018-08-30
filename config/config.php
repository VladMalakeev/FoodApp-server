<?php

//Ключ защиты
 if(!defined('KEY'))
 {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('./404.html'));
 }

//Адрес базы данных
 define('DBSERVER','localhost');

//Логин БД
 define('DBUSER','vlad_malakeev');

 //Пароль БД
 define('DBPASSWORD','Vlad2020');

 //БД
 define('DATABASE','vlad_malakeev');

//Errors
 define('ERROR_CONNECT','Немогу соеденится с БД');

 //Errors
 define('NO_DB_SELECT','Данная БД отсутствует на сервере');

//Адрес хоста сайта
 define('HOST','https://'. $_SERVER['HTTP_HOST'] .'/');

//Адрес почты от кого отправляем
 define('MAIL_AUTOR','startUp@tracking-app.tk');

define('REG_TABLE', 'users');

//Подключение к базе данных mySQL с помощью PDO
try {
   $db = new PDO('mysql:host=localhost;dbname='.DATABASE, DBUSER, DBPASSWORD, array(
    	PDO::ATTR_PERSISTENT => true
    	));
        
        

} catch (PDOException $e) {
    print "Ошибка соединеия!: " . $e->getMessage() . "<br/>";
    die();
}

?>