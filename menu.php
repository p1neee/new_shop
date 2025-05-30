<?php
	$menu= '
<div class="menu">
	<ul>';
	// меню по уровням доступа: 10 - админ и т.д.
//	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="news.php">Новости</a> </li>';

	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="levels.php">Уровни доступа</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="users.php">Пользователи</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="logs.php">Журнал событий</a> </li>';

	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="report1.php">Отчет по авторизациям</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="report2.php">Отчет по категориям</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="report3.php">Отчет по товарам</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="report4.php">Отчет по запросам</a> </li>';

	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10, 5)) ) $menu.='<li> <a href="categories.php">Категории</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10, 5)) ) $menu.='<li> <a href="products.php">Товары</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10, 5)) ) $menu.='<li> <a href="discounts.php">Скидки</a> </li>';

	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10, 5)) ) $menu.='<li> <a href="orders.php">Заказы</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10, 5)) ) $menu.='<li> <a href="items.php">Содержимое заказов</a> </li>';
	if ( !empty($_SESSION['level']) && in_array($_SESSION['level'], array(10, 5)) ) $menu.='<li> <hr> </li>';

	if ( !isset($_SESSION['level'])) $menu.='<li> <a href="login.php">Авторизация</a> </li>';
	if ( !isset($_SESSION['level'])) $menu.='<li> <a href="reg.php">Регистрация</a> </li>';
	else {
		$menu.='Вы вошли под логином '.$_SESSION['login'].' <a href="?do=exit"><button><img src="images/exit.png" height="18px"> Выход</button></a>';
		$menu.='<li><a href="cart.php"><img src="images/cart.png" width="64px" height="64px"><div id="cart_info">Корзина</div></a> </li>';
		if ( in_array($_SESSION['level'], array(2,1)) ) $menu.='<li><a href="user_orders.php"><img src="images/order.png" height="18px">Заказы</a></li>';
	};
	$menu.='<li><a href="index.php"><img src="images/home.png" width="20px">Главная</a> </li>';
	$menu.='<li><a href="view.php"><img src="images/showcase.png" height="18px">Каталог</a></li>';
//	$menu.='<li><a href="contacts.php"><img src="images/mail.png" width="20px">Реквизиты</a> </li>';
	$menu.='<li><a href="about.php"><img src="images/about.png" width="20px">О нас</a> </li>';




	$menu.='
	</ul>
</div>';
	echo $menu;
?>