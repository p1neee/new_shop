<?php
//	sleep(1); // защита от подбора пароля
	if (session_id() == '') session_start();
	if (isset($_GET['do']) && $_GET['do'] == 'exit'){
		//save_event($con, $user_id=0, $event_type_id=0, $note='', $stat_id=0)
		include 'func.php';
		$con=connect();
		save_event($con, $_SESSION['id'], 2, '', 0);
		unset($_SESSION['login']);
		session_destroy();
		header("Location: index.php");
	};
/*
	if (!isset($_SESSION['login']) || !$_SESSION['login']){
		header("Location: login.php");
		exit;
	};
*/
?>