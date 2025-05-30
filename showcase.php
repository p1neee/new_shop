<?php
Error_reporting(E_ALL);
	$con=connect();
	$showcase= '';
	$query="
		SELECT
			`name`, `parent`, `id` AS `cat_id`
		FROM
			`categories`
		WHERE 1
			AND `categories`.`parent`=0
			AND `categories`.`id`<>0
	";
	echo '<h2><a href="view.php"><img src="images/cats.png" height="18px"> Категории</h2>';
	Echo '';
	$res=mysqli_query($con, $query) or die(mysqli_error($con));
	while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC)) {
//		$selected= ($status==$row['id']) ? 'selected' : '';
		echo "<a id='cats_style' href=\"view.php?cat_id=$row[cat_id]\">$row[name]</a><br>";
	};
	echo '';

	echo $showcase;
?>