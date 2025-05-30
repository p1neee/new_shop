<?php
	// главная страница
	header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
	include 'auth.php';
	include 'func.php';
//	include 'scripts.php';
	$title='Оплата';
	// Получение всех товаров
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

<form class="credit-card" method="POST" action="user_orders.php">
<h4 class="title">Данные карты</h4>
<!-- Card Number -->
<p>
Номер карты:
<input type="text" name="card1" id="card1" class="card-number" pattern="[0-9]{4}" size=4 required value="1234"> -
<input type="text" name="card2" id="card2" class="card-number" pattern="[0-9]{4}" size=4 required value="1234"> -
<input type="text" name="card3" id="card3" class="card-number" pattern="[0-9]{4}" size=4 required value="1234"> -
<input type="text" name="card4" id="card4" class="card-number" pattern="[0-9]{4}" size=4 required value="1234"><br>
</p>
<p>
Срок действия:
<select name="Month" required>
	<option selected value="january">01</option>
	<option value="february">02</option>
	<option value="march">03</option>
	<option value="april">04</option>
	<option value="may">05</option>
	<option value="june">06</option>
	<option value="july">07</option>
	<option value="august">08</option>
	<option value="september">09</option>
	<option value="october">10</option>
	<option value="november">11</option>
	<option value="december">12</option>
</select>

<select name="Year" required>
<option selected value="2025">2025</option>
<option value="2026">2026</option>
<option value="2027">2027</option>
<option value="2028">2028</option>
<option value="2029">2029</option>
<option value="2030">2030</option>
<option value="2031">2031</option>
<option value="2032">2032</option>
<option value="2033">2033</option>
<option value="2034">2034</option>
<option value="2035">2035</option>
</select>
</p>
<p>
CVV:
<!-- Card Verification Field -->
<input type="text"placeholder="CVV" required value="123">
</p>

<!-- Buttons -->
<button type="submit" class="proceed-btn">Продолжить</a></button>
</div>
</form>

        </div>
    </div>
<?php
	include('footer.php');
?>
</body>
</html>
