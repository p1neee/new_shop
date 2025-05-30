<?php
	header('Content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<style>
h1 {
  font-size: 32px;
  font-weight: bold;
  text-align: center;
  color: #333333;
  text-shadow: 2px 2px #CCCCCC;
  margin-top: 50px;
  margin-bottom: 30px;
}

body {
  font-family: "Roboto", sans-serif;
  font-size: 18px;
  line-height: 1.5;
  color: #555555;
  margin: 5;
  padding: 0;
}

a {
  color: #0077CC;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
</head>

<body>
<h1>Установщик базы данных веб-проекта</h1>
<?php
	// настройки
	/*
	$servername = "localhost"; // имя сервера
	$username = "root"; // логин
	$password = ""; // пароль
	*/
	require_once "database.php";

	$servername=$hostname;
	$username=$mysql_login;
	$password=$mysql_password;


	$dump_fname="db.sql"; // имя файла дампа

	if (!file_exists($dump_fname)) {
		die("<p>Файл $dump_fname не найден</p>");
	};

	$f=file_get_contents($dump_fname);
	// найти имя БД
	preg_match_all('!База данных: `(.*?)`|Database: (\w+)!', $f, $res);
	if (empty($res[1][0])) {
		// попытаемся найти имя БД в другом формате еще
		preg_match_all('!Database: (\w+)!', $f, $res);
		if (empty($res[1][0])) {
			die('Не найдено имя БД');
		}
		else {
			$database_name=$res[1][0];
			echo "<p>В файле дампа найдена база данных `$database_name`.</p>";
		};
	}
	else {
		$database_name=$res[1][0];
		echo "<p>В файле дампа найдена база данных `$database_name`.</p>";
	};

	$database="a0670070_shop";

	// подключаемся
	$conn = mysqli_connect($servername, $username, $password);

	// проверка подключения
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	};


	// удаляем БД, если есть
	$query="
		DROP DATABASE IF EXISTS `$database_name`
	";
	if (mysqli_query($conn, $query)) {
		//echo "<p>Удаление БД успешно.</p>";
	}
	else {
		echo "<p>Ошибка при удалении: " . mysqli_error($conn). "</p>";
	};


	// создание базы данных
	$query = "CREATE DATABASE `$database_name`";
	if (mysqli_query($conn, $query)) {
		echo "<p>Создание БД успешно.</p>";
	}
	else {
		echo "<p>Ошибка при создании: " . mysqli_error($conn). "</p>";
	};


	// выбор БД
	if (!mysqli_select_db($conn, $database_name)) {
		die("<p>Ошибка выбора БД</p>");
	};


	// выполнение всех запросов дампа
	if (mysqli_multi_query($conn, $f)) {
		echo "<p>Загрузка дампа завершена.</p>";
	}
	else {
		echo "<p>Ошибка при загрузке дампа " . mysqli_error($conn). "</p>";
	};

	// закрытие подключения
	mysqli_close($conn);
	echo "<p>База данных `$database_name` успешно создана и загружена из дамп-файла.</p>";
?>
</body>
</html>