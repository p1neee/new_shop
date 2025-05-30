<?php
	// отчет
	header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
	include 'auth.php';
	include 'func.php';
//	include 'scripts.php';
	$title='Отчет по товарам';
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

<h3>Топ-10 самых популярных товаров</h3>

<?php
	$query="
		SELECT
			products.name AS 'Товар',
			COUNT(logs.id) AS 'Количество добавлений в корзину'
		FROM products
		LEFT JOIN logs ON logs.event_type_id=5 AND logs.stat_id=products.id
		WHERE products.id<>0
		GROUP BY products.id
		ORDER BY `Количество добавлений в корзину` DESC
		LIMIT 10;
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
