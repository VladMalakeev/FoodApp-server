<?php
//главный обработчик всех запросов
$menu='menu_'.$_SESSION['name'];

//удаление меню
if(isset($_POST['delete'])){
	$id=$_POST['delete'];
	$db->exec("delete from $menu where id='$id'");
}
//добавление меню в бд
$path_name='user/menu/'.$_SESSION['name'];
if(!is_dir($path_name)){
mkdir("$path_name", 0777);
}
$path = $path_name.'/';
$tmp_path = 'tmp/';
// Массив допустимых значений типа файла
$types = array('image/gif', 'image/png', 'image/jpeg');
// Максимальный размер файла
$size = 1024000;
 
if(isset($_POST['submitMenu'])){
    $name = $_POST['nameMenu'];
    if(empty($name)){
        $err[]='поле название не может быть пустым';
		
    }
    $descrypt = $_POST['descryptMenu'];
    if(empty($descrypt)){
        $err[]='заполните поле описание';
    }
    
    $price = $_POST['priceMenu'];
    if(empty($price)){
        $err[]='укажите цену';
    }
	
	if (!in_array($_FILES['picture']['type'], $types)){
		$err[]='Запрещённый тип файла';
	}
	
	if ($_FILES['picture']['size'] > $size){
		$err[]='Слишком большой размер файла';
	}
	if (!@copy($_FILES['picture']['tmp_name'], $path . $_FILES['picture']['name'])){
		$err[]='ошибка чтения картинки';
	}


    $photo =$path . $_FILES['picture']['name'];
    
    if(count($err)>0){
		
		?>
	
		<div id='error'>
	
		<ul>
		<?php
        foreach($err as $error)
		{
		echo "<li>$error</li>";
		}
		?>
		</ul>
		</div>
		<?php
    }
    else{
       
         $db->exec("ALTER TABLE $menu AUTO_INCREMENT=1");
        $sql = "INSERT INTO $menu
        						VALUES(
								'',
                                :name,
								:descript,
								:price,
								:photo)";
				//Подготавливаем PDO выражение для SQL запроса
				$stmt = $db->prepare($sql);
   	            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
				$stmt->bindValue(':descript', $descrypt, PDO::PARAM_STR);
				$stmt->bindValue(':price', $price, PDO::PARAM_STR);
				$stmt->bindValue(':photo', $photo, PDO::PARAM_STR);
              //  $stmt->bindValue(':photo', $photo, PDO::PARAM_STR);	
                
        
        if(	$stmt->execute()){
          echo "<div id='error'>даные успешно сохранены</div>" ;
		  
        }
        else{
            echo "<div id='error'>ошибка записи данных</div>";
        }
    }
}
?>