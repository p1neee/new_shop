<?php
	# скрипт добавляет в корзину
	include "../database.php";
	include "../func.php";
	error_reporting(E_ALL);

	// все ли данные введены?
	if (empty($_POST['user_id'])) {
			die('Не все данные выбраны'); // если нет, выходим
	};

	// подключаемся к БД
	$con=connect();

	$user_id=abs(intval($_POST['user_id']));


	// проверяем, надо ли создавать заказ
	$query="
		SELECT COUNT(*)
		FROM
			`items`
		WHERE 1
			AND `items`.`user_id`=$user_id
			AND `items`.`ord_id`=0
	";
	$res=mysqli_query($con, $query) or die(mysqli_error($con));
	if (!mysqli_fetch_array($res, MYSQLI_BOTH)[0]) {		die(iconv('UTF-8', 'CP1251', 'Корзина была пуста. Заказ не оформлен.'));	};

	// создаем новый заказ
	$query="
		INSERT INTO `orders`
		SET
			user_id='$user_id',
			dt='".date("Y-m-d H:i:s")."',
			status=0
	";
	$res=mysqli_query($con, $query) or die(mysqli_error($con));
	$order_id=mysqli_insert_id($con);

	$query="
		UPDATE
			`items`
		SET
			`ord_id`=$order_id
		WHERE 1
			AND `user_id`='$user_id'
			AND `ord_id`=0
	";

	$res=mysqli_query($con, $query);
	if (!$res) {
		die(mysqli_error($con));
	};

	echo 'ok';
?>