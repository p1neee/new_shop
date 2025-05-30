<?php
	# скрипт увеличиваем количество в корзине
	include "../database.php";
	include "../func.php";
	error_reporting(E_ALL);

	// все ли данные введены?
	if (empty($_POST['user_id']) || empty($_POST['id'])) {
			die('Не все данные выбраны'); // если нет, выходим
	};

	// подключаемся к БД
	$con=connect();

	$id=abs(intval($_POST['id']));
	$user_id=abs(intval($_POST['user_id']));


	// проверка на наличие
	$buf_query="
		SELECT
			`products`.`amount`- IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'amount'
		FROM
			`products`
		LEFT JOIN
			`items` ON `items`.`product_id`=`products`.`id`
		WHERE 1
			AND `products`.`id`=(
				SELECT product_id
				FROM items
				WHERE `id`=$id
				LIMIT 1
			)
		GROUP BY `products`.`id`
	";
	$buf_res=mysqli_query($con, $buf_query) or die(mysqli_error($con));
	$exists=mysqli_fetch_array($buf_res, MYSQLI_BOTH)[0];
	if ($exists<=0) {
		die(iconv('UTF-8', 'CP1251', 'Товар закончился!'));
//		die('Товар закончился!');
	};

	// увеличиваем количество
	$query="
		UPDATE `items`
		SET
			amount=amount+1
		WHERE 1
			AND `user_id`='$user_id'
			AND `id`=$id
			AND `ord_id`=0
		LIMIT 1
	";

	$res=mysqli_query($con, $query);
	if (!$res) {
		die(mysqli_error($con));
	};

	echo 'ok';
?>