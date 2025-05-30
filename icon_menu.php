<style>
	input {		margin: 10px;
	};
</style>
<?php
	$menu= '';
	// меню по уровням доступа: 10 - админ и т.д.
	if ( in_array($_SESSION['level'], array(10, 1)) ) $menu.='<button> <a href="tickets.php"> <input type=image src="images/help-desk.png" title="Заявки" width="50px" height="50px"> </a> </button> ';
	if ( in_array($_SESSION['level'], array(10)) ) $menu.='<button> <a href="users.php"> <input type=image src="images/users.png" title="Пользователи" width="50px" height="50px"> </a> </button> ';
	if ( in_array($_SESSION['level'], array(10)) ) $menu.='<button> <a href="reports.php"> <input type=image src="images/report.png" title="Отчеты" width="50px" height="50px"> </a> </button> ';
	echo $menu;
?>