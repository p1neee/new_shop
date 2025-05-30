<?php
	// отчет
	header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
	include 'auth.php';
	include 'func.php';
//	include 'scripts.php';
	$title='Отчет по категориям';
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

<h3>Топ-10 самых популярных категорий</h3>

<?php
	$query="
		SELECT
			categories.name AS 'Категория',
			COUNT(logs.id) AS 'Количество посещений'
		FROM categories
		LEFT JOIN logs ON logs.event_type_id=3 AND logs.stat_id=categories.id
		WHERE categories.id<>0
		GROUP BY categories.id
		ORDER BY `Количество посещений` DESC
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
