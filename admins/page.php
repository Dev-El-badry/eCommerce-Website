<?php 

	$do = isset($_GET['do']) ? $_GET['do'] : '';

	if($do == 'manage') {
		echo "you are in category manage";
	} elseif ($do == 'add') {
		echo "you are in category add";
	} elseif ($do == 'insert') {
		echo "you are in category insert";
	} else {
		echo 'error';
	}