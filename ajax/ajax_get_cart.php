<?php
	# скрипт возращает таблицу товаров в корзине
	header('Content-type: text/html; charset=utf-8');
	include "../database.php";
	include "../func.php";
	error_reporting(E_ALL);

	$result=array('sum'=>0, 'amount'=>0);

	if (!empty($_POST['user_id'])) {
		$user_id=abs(intval(trim($_POST['user_id'])));
	}
	else {		die('no user');
	};

	// подключаемся к БД
	$con=connect();
	$table='items';

	$query="
		SELECT
			`$table`.`id`,
			`$table`.`ord_id`,
			`products`.`name`,
			`products`.`id` AS `product_id`,
			`$table`.`amount`,
			`$table`.`price`
		FROM
			`$table`
		LEFT JOIN
			`products` ON `products`.`id`=`$table`.`product_id`
		LEFT JOIN
			`users` ON `users`.`id`=`$table`.`user_id`
		WHERE 1
			AND `users`.`id`='$user_id'
			AND `items`.`ord_id`=0
		LIMIT 50;
	";

	$sum=0;
	$amount=0;
	$res=mysqli_query($con, $query) or die(mysqli_error($con));

	if (!mysqli_num_rows($res)) {		die('<h3>Корзина пуста</h3>');	};

	echo '
	<table border=0 width=900px>
		<thead>
		<tr>
			<td width=300px>Наименование</td>
			<td width=100px>Количество</td>
			<td width=100px>Цена за 1ед.</td>
			<td width=100px colspan="2">Сумма</td>
		</tr>
		</thead>
		<tbody>
	';
	while ($row=mysqli_fetch_array($res, MYSQLI_BOTH)) {
		$sum+=round($row['price']*$row['amount'], 2);
		$amount+=$row['amount'];
		$fname='upload/'.$row['product_id'].'.jpg';
		if (!file_exists('../upload/'.$row['product_id'].'.jpg')) { // если нет файла, показать "НЕТ ФОТО"
			$fname='upload/0.jpg';
		};
		clearstatcache($fname);
		echo "
		<tr>
			<td width=300px><image src='$fname' title='$row[name]' style='width:48px; height:48px'> $row[name]</td>
			<td width=100px>
				<input type=image style='width:16px; height:16px' src='images/minus.png' title='Уменьшить на 1' onclick='dec_amount_cart($row[id])'>
				$row[amount]
				<input type=image style='width:16px; height:16px' src='images/plus.png' title='Увеличить на 1'onclick='inc_amount_cart($row[id])'>
			</td>
			<td width=100px>$row[price]</td>
			<td width=100px>".(round($row['price']*$row['amount'], 2))."</td>
			<td width=100px onclick='delete_from_cart($row[id])'><input type=image src='images/del.png' title='Удалить'></td>
		</tr>
		";
	};
	echo "
	<tr>
		<td colspan='5'>
			<b>Итого: единиц $amount на сумму $sum</b>
		</td>
	</tr>
	</tbody></table>";
?>