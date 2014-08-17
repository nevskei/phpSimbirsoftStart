<?php
header("Content-Type: text/html; charset=utf-8"); 
echo'
<!DOCTYPE html>
<html>
 <meta charset="utf-8">
	<body>
		<p>Пример использования команд str_replace и trim, в строке "Hello Word!" слово Word Будет замененно на ваше имя, в случае если в начале и в конце имени будут введены пробелы они будут удалены</p>
		<form method="post">
			Введите ваше имя<br/>
			<input type="text" name="NAME">
			<input type="submit">
		</form>
			';
		
		if(isset($_POST['NAME']))
		{
			$inputName = trim($_POST['NAME']);
			if(!empty($inputName)){
				$inputStr = "Hello Word!";
				echo '<p style="color:green;">'.str_replace("Word", $inputName, $inputStr).'</p>';
			}
		}
		echo'<p>Пример использования команд strpos, strtolower/strtoupper и substr, получить из адресса почты логин и домен, и перевести домен перевести в верхний регистр, логин в нижний</p>';
		$email = "Flex@mail.ru";
		$dog = strpos($email, '@');
		echo 'Email: '.$email.'<br>Login: '.strtolower(substr($email, 0, $dog)).'<br> Domen: '.strtoupper(substr($email, $dog+1, strlen($email)-$dog));
		echo'<p>Как выглядят популярные пароли в md5</p> <table><tr><td>Пароль</td><td>md5</td><td>длина получившейся строки </td></tr><tr><td>qwe</td><td>'.md5('qwe').'</td><td>'.strlen(md5('qwe')).'</td></tr><tr><td>qwerty</td><td>'.md5('qwerty').'</td><td>'.strlen(md5('qwerty')).'</td></tr><tr><td>123456</td><td>'.md5('123456').'</td><td>'.strlen(md5('123456')).'</td></tr><tr><td>123456789</td><td>'.md5('123456789').'</td><td>'.strlen(md5('123456789')).'</td></tr><table>';
		echo'<p>Пример использования команд explode и implode, получить масси из даты в формате dd.mm.yyyy, и из массива ФИО получить строку</p>';
		$fio = array('fam'=>'Иванов', 'name'=>'Иван', 'oych'=>'Иванович');
		echo 'Входной массив: <br>';
		print_r($fio);
		echo '<br><br> Выходная строка: <br>'.implode(' ', $fio).'<br><br>';
		echo 'Входная строка: <br>'.date("d.m.Y").'<br><br> Выходной массив: <br>';
		print_r(explode('.', date("d.m.Y")));
		echo'<p>Пример команд money_format, namber_format</p> <table><tr><td>Число</td><td>money_format</td><td>number_format</td></tr>';
		
		setlocale(LC_MONETARY, 'en_US');
		for($number = 0.00000001; $number < 100000000; $numd=$number*100)
			echo'<tr><td>'.$number.'</td><td>'.money_format ( 'i', (float)$number ).'</td><td>'.number_format($number).'</td></tr>';
		echo'</table>';
echo'	</body>
</html>';
?>