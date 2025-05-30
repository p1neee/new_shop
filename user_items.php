<?php
	//Скрипт показывает таблицу заказов пользователя
	header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
	include 'auth.php';
	include 'func.php';
	include 'scripts.php';
	$con=connect();
	$ord_id=abs(intval(trim($_GET['ord_id'])));
	$title="Просмотр заказа №$ord_id";
	$table='items';
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

	$query="
		SELECT
			`$table`.`id`,
			`$table`.`ord_id`,
			`products`.`name`,
			`$table`.`amount`,
			`$table`.`price`
		FROM
			`$table`
		LEFT JOIN
			`products` ON `products`.`id`=`$table`.`product_id`
		LEFT JOIN
			`users` ON `users`.`id`=`$table`.`user_id`
		WHERE 1
			AND `users`.`id`='".$_SESSION['id']."'
			AND `items`.`ord_id`='$ord_id'
	";

	$sum=0;
	$amount=0;
	$res=mysqli_query($con, $query) or die(mysqli_error($con));

	echo '
	<table border=0>
		<thead>
		<tr>
			<td>Наименование</td>
			<td>Количество</td>
			<td>Цена за 1ед.</td>
			<td>Сумма</td>
		</tr>
		</thead>
		<tbody>
	';
	while ($row=mysqli_fetch_array($res, MYSQLI_BOTH)) {
		$sum+=round($row['price']*$row['amount'], 2);
		$amount+=$row['amount'];
		echo "
		<tr>
			<td>$row[name]</td>
			<td>$row[amount]</td>
			<td>$row[price]</td>
			<td>".(round($row['price']*$row['amount'], 2))."</td>
		</tr>
		";
	};
	echo "
	<tr>
		<td colspan='4'>
			<b>Итого: единиц $amount на сумму $sum</b>
		</td>
	</tr>
	</tbody></table>";

?>


    </div>
  </div>
<?php
	include('footer.php');
?>
</body>
</html>
