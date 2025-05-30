<?php
	# скрипт добавляет в корзину
	include "../database.php";
	include "../func.php";
	error_reporting(E_ALL);

	// все ли данные введены?
	if (empty($_POST['user_id']) || empty($_POST['id'])) {
//		die(iconv('UTF-8','CP1251', 'Не все данные выбраны')); // если нет, выходим
		die(iconv('UTF-8','CP1251','Ошибка при добавлении в корзину. Возможно, вы не авторизованы')); // если нет, выходим
	};

	// подключаемся к БД
	$con=connect();

	$id=abs(intval($_POST['id']));
	$user_id=abs(intval($_POST['user_id']));

	save_event($con, $user_id, 5, '', $id);

	// проверка на наличие
	$buf_query="
		SELECT
			`products`.`amount`- IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'amount'
		FROM
			`products`
		LEFT JOIN
			`items` ON `items`.`product_id`=`products`.`id`
		WHERE 1
			AND `products`.`id`='$id'
		GROUP BY `products`.`id`
	";
	$buf_res=mysqli_query($con, $buf_query) or die(mysqli_error($con));
	$exists=mysqli_fetch_array($buf_res, MYSQLI_BOTH)[0];
	if ($exists<=0) {
//		die(iconv('UTF-8', 'CP1251', 'Товар закончился!'));
		die('Товар закончился!');
	};

	// вдруг есть такой товар в корзине
	$query="
		SELECT COUNT(*)
		FROM `items`
		WHERE 1
			AND `user_id`='$user_id'
			AND `product_id`='$id'
			AND `ord_id`=0
	";
	$res=mysqli_query($con, $query) or die(mysqli_error($con));
	if (mysqli_fetch_array($res, MYSQLI_BOTH)[0]) {
		// такой товар уже есть, надо просто увеличить количество на 1
		$query="
			UPDATE `items`
			SET
				`amount`=`amount`+1
			WHERE 1
				AND `user_id`='$user_id'
				AND `product_id`='$id'
				AND `ord_id`=0
		";
	}
	else {
		$query="
			INSERT INTO `items`
			SET
				`amount`=1,
				`ord_id`=0,
				`product_id`='$id',
				`price`=(
					SELECT ROUND(`price`*(1-IFNULL(discounts.value, 0)/100), 2) AS price
					FROM `products`
					LEFT JOIN discounts ON `discounts`.`id`=`products`.`discount_id` AND NOW() BETWEEN `discounts`.`start` AND `discounts`.`stop`
					WHERE products.`id`='$id'
					LIMIT 1
				)
				,
				`user_id`='$user_id'
		";
	};
	$res=mysqli_query($con, $query);
	if (!$res) {
		die(mysqli_error($con));
	};

	echo 'ok';
?>