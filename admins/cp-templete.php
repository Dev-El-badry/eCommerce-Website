<?php

	ob_start();


	session_start();

	include 'init.php' ;

	$pageTitle = '';

	if(isset($_SESSION['Username'])) {

		$do = isset($_GET['do']) ? $_GET['do'] : 'manage' ;

		if ($do == 'manage') {

			echo "Welcome";

		} elseif ($do == 'add') {

		} elseif ($d0 == 'insert') {
			
		} elseif ($do == 'edit') {
			
		} elseif ($do == 'update') {
			
		} elseif ($do == 'delete') {
			
		}

	} else {
		echo "can\'t access to the page";
	}

	include $tpl . 'footer.php';

	ob_end_flush();