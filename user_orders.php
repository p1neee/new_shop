<?php
	//Скрипт показывает таблицу заказов пользователя
	header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
	include 'auth.php';
	include 'func.php';
	include 'scripts.php';
	$title='Заказы';
	$table='orders';
	$con=connect();
	if (!in_array($_SESSION['level'], array(10, 2, 1))) { // доступ разрешен только группе пользователей
		header("Location: login.php"); // остальных просим залогиниться
		exit;
	};

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
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
<?php
	if (!empty($_REQUEST['Month']) && !empty($_REQUEST['Year'])
			&& !empty($_REQUEST['card1'])
			&& !empty($_REQUEST['card2'])
			&& !empty($_REQUEST['card3'])
			&& !empty($_REQUEST['card4'])
	) {
		echo "<p>Оплата проведена успешно!</p>";
	};
	$query="
		SELECT
			`$table`.`id`,
			DATE_FORMAT(`$table`.`dt`, '%d.%m.%Y %H:%i:%s') AS `dt`,
			`statuses`.`name`
		FROM
			`$table`
		LEFT JOIN
			`statuses` ON `statuses`.`id`=`$table`.`status`
		WHERE 1
			AND `$table`.`user_id`=".$_SESSION['id']."
		ORDER BY `$table`.`dt` DESC
	";

	$res=mysqli_query($con, $query) or die(mysqli_error($con));

	if (!mysqli_num_rows($res)) {
		die('<h3>У вас нет заказов</h3>');






	}
	else {
		echo '
		<table border=0 style="width:900px">
			<thead>
			<tr>
				<td>№ заказа</td>
				<td>Дата</td>
				<td>Статус</td>
			</tr>
			</thead>
			<tbody>
		';
		while ($row=mysqli_fetch_array($res, MYSQLI_BOTH)) {
			echo "
			<tr>
				<td>$row[id]</td>
				<td>$row[dt]</td>
				<td><a href='user_items.php?ord_id=$row[id]'>Просмотр</a></td>
			</tr>
			";
		};
		echo "
		</tbody></table>";
	};
?>
    </div>
  </div>
<?php
	include('footer.php');
?>
</body>
</html>
