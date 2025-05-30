<?php
	// витрина
	header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
	include 'auth.php';
	include 'func.php';
	include 'scripts.php';
	$title='Каталог';
	// Получение всех товаров
	$con=connect();

	if (!empty($_REQUEST['find_mask'])) {
		$find_mask=mysqli_real_escape_string($con, trim($_REQUEST['find_mask']));
		save_event($con, $_SESSION['id'], 7, $find_mask, 0);
		$cat_id=0;
		$filter_product=" AND `products`.`name` LIKE '%$find_mask%'";
	}
	else {
		$filter_product='';
	};
	$cat_id=empty($_GET['cat_id']) ? '' : abs(intval($_GET['cat_id']));
	save_event($con, $_SESSION['id'], 3, '', $cat_id);
	if ($cat_id) {
		// если выбрана категория
		$query="
			SELECT name
			FROM categories
			WHERE 1
				AND id=$cat_id
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res);
		$cat_name=$row['name'];
//		echo "<h2>Категория: $cat_name</h2>";
	};
	$filter_cat= $cat_id==0 ? '' : "AND `$table`.`cat_id`='$cat_id'"; // если категория не выбрана, показать все товары



	$query="
	SELECT t.*
	FROM (
		SELECT
			`products`.`id`,
			`products`.`name`,
			`products`.`descr`,
			`categories`.`name` AS `category`,
			`products`.`price`,
			`products`.`weight`,
			`products`.`length`,
			`products`.`width`,
			`products`.`height`,
			`products`.`amount`- IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'amount',
#			`products`.`amount` AS amnt,
			IFNULL(`discounts`.`value`, 0) AS `discount_value`,
			TIMESTAMPDIFF(DAY, `products`.`date_add`, NOW()) AS `delta`
		FROM
			`products`
		LEFT JOIN
			`items` ON `items`.`product_id`=`products`.`id`
		LEFT JOIN
			`categories` ON `categories`.`id`=`products`.`cat_id`
		LEFT JOIN
			`discounts` ON (`discounts`.`id`=`products`.`discount_id` AND NOW() BETWEEN `discounts`.`start` AND `discounts`.`stop`)
		WHERE 1
			$filter_cat
			$filter_product
		GROUP BY `products`.`id`
		ORDER BY `products`.`name`
		) AS t
	WHERE amount>0;
	";
	$res = mysqli_query($con, $query) or die(mysqli_error($con). ' '.$query);
	$products =mysqli_fetch_all($res, MYSQLI_ASSOC);
?>
<script>

	// вернуть сумму и количество единиц в корзине пользователя
	function get_cart_info() {
		$.ajax({
			url: 'ajax/ajax_get_cart_info.php',
			type: 'POST',
			async: true,
			dataType: "JSON",
			data: {
				user_id: '<?php echo $_SESSION['id']; ?>'
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				$('#cart_info').html('Корзина ('+response.amount+')');
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});





	};

	// сразу после загрузки страницы выполнить
	$(function() {
		get_cart_info();
	});

	// добавлям товар в корзину пользователю
	function to_cart(id) {
		var user_id='<?php echo $_SESSION["id"];?>';
		$.ajax({
			url: 'ajax/ajax_add_to_cart.php',
			type: 'POST',
			async: true,
			data: {
				id: id,
				user_id: user_id
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				if (response=='ok') {
					get_cart_info();
					alert('Добавлено в корзину!');
				}
				else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});

	};

</script>

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
	include 'menu.php';
	//include 'showcase.php';
?>

<div class="row">

<!-- Горизонтальный список категорий -->
<div class="text-center my-3">
<form action="view.php" method="POST">
	<input id="find_mask" name="find_mask" type="text" size="50">
	<button>Найти товар</button>
</form>

	<a href="?cat_id=0" class="btn btn-outline-primary m-1">Все категории</a>
	<?php
	$con = connect();
	$cat_query = "SELECT id, name FROM categories WHERE id>0 ORDER BY name";
	$cat_res = mysqli_query($con, $cat_query) or die(mysqli_error($con));
	while ($cat = mysqli_fetch_assoc($cat_res)) {
		if ($cat['id']==$cat_id) {
			$add_style="btn-primary";
		}
		else {
			$add_style="btn-outline-primary";
		};
		echo "<a href=\"?cat_id=$cat[id]\" class=\"btn $add_style m-1\">$cat[name]</a>";
	}
	?>
</div>

	<div class="main-content">
		<div class="container mt-5">
			<div class="row row-cols-1 row-cols-md-4 g-4">
				<?php
					if (count($products) > 0) {
						foreach ($products as $product) {
							if ($product['delta']<30) { // товар добавлен меньше 30 дней назад, т.е. это новинка
								$new="<div><img src='images/new.png' style='width:100px'></div>";
							}
							else {
								$new='';
							};
							if ($product['discount_value']>0) { // цена со скидкой
								$price_new=number_format (round($product['price']*(1-$product['discount_value']/100), 2), 2, '.', '');
								$price_str="
									<font style='color: #888; font-size:x-small; text-decoration:line-through'>$product[price] $valuta</font>
									<img src='images/discount.png' height='24px' title='Скидка'>
									<font style='color: #000;'>$price_new $valuta</font>
								";
								$price_str=trim($price_str);
							}
							else {
								$price_str="<font style='color: #000;'>$product[price] $valuta</font>";
							};

							echo '
							<div class="col">
								<div class="card h-100">';
							$fname='upload/'.$product['id'].'.jpg';
							if (!file_exists($fname)) { // если нет файла, показать "НЕТ ФОТО"
								$fname='upload/0.jpg';
							};

							// обрезать описание, если оно очень длинное
							$descr=$product['descr'];
							if (mb_strlen($descr, 'UTF-8')>50) {
								$short_descr=mb_substr($descr, 0, 50, 'UTF-8').'...';
							}
							else {
								$short_descr=$descr;
							};

							echo "
									<img src='$fname' class='card-img-top' alt='$product[name]'
									style='
										height: 100%;
										object-fit: contain;
										width: 100%;
										cursor:pointer;
									'
									onclick='to_cart($product[id]);'>
									<div class='card-body'>
										$new
										<h5 class='card-title'><strong><span onclick='window.location.href=\"card.php?product_id=$product[id]\"' style='cursor: pointer;'>$product[name]</span></strong></h5>
										<p class='card-text' title='$descr'>".nl2br(htmlspecialchars($short_descr))."</p>
									</div>
									<div class='card-footer' style='cursor:pointer;' onclick='to_cart($product[id]);'>
										<strong>$price_str</strong>
									</div>
								</div>
							</div>
							";
						};
					}
					else {
						echo '<p>Товары не найдены.</p>';
					};
				?>
			</div>
		</div>
	</div>

</div>


        </div>
    </div>
<?php
	include 'footer.php';
?>
</body>
</html>
