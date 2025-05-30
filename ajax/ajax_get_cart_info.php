<?php
	# скрипт возращает количество товаров и сумму корзины пользователя
	include "../database.php";
	include "../func.php";
	error_reporting(E_ALL);

	$result=array('sum'=>0, 'amount'=>0);

	if (!empty($_POST['user_id'])) {
		$user_id=abs(intval(trim($_POST['user_id'])));
		// подключаемся к БД
		$con=connect();

		$query="
			SELECT `items`.*
			FROM
				`items`
			WHERE 1
				AND `items`.`user_id`=$user_id
				AND `items`.`ord_id`=0
		";

		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC)) {
			$result['sum']+=ROUND($row['price']*$row['amount'], 2);
			$result['amount']+=$row['amount'];
		};
	};

	echo json_encode($result);
?>