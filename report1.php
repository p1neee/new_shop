<?php
	// отчет
	header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
	include 'auth.php';
	include 'func.php';
//	include 'scripts.php';
	$title='Отчет по авторизациям';
	$con=connect();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title><?php echo $title;?></title>
</head>
<body>
    <div class="banner">
        <h1><?php echo $title;?></h1>
    </div>
    <div class="content">
<?php
	include('menu.php');
?>
        <div class="main-content">

<div class="row">

<h3>Количество авторизаций по пользователям</h3>

<?php
	$query="
		SELECT
			users.fio AS 'ФИО',
			COUNT(logs.id) AS 'Количество авторизаций'
		FROM users
		LEFT JOIN logs ON logs.event_type_id=1 AND logs.user_id=users.id
		GROUP BY users.id
		ORDER BY `Количество авторизаций` DESC;
	";
	$res = mysqli_query($con, $query) or die(mysqli_error($con). ' '.$query);
	echo print_table2($query, $con);
?>

<hr>
<h3>Количество разлогинов по пользователям</h3>

<?php
	$query="
		SELECT
			users.fio AS 'ФИО',
			COUNT(logs.id) AS 'Количество разлогинов'
		FROM users
		LEFT JOIN logs ON logs.event_type_id=2 AND logs.user_id=users.id
		GROUP BY users.id
		ORDER BY `Количество разлогинов` DESC;
	";
	$res = mysqli_query($con, $query) or die(mysqli_error($con). ' '.$query);
	echo print_table2($query, $con);
?>

</div>


        </div>
    </div>
<?php
	include('footer.php');
?>
</body>
</html>
