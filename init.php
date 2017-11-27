<?php

	include 'connect.php';

	$sessionName = '';

	if(isset($_SESSION['user'])) {
		$sessionName = $_SESSION['user'];
	}

	//Routes

	$tpl = 'includes/templetes/' ; // templete directory

	$fun = 'includes/functions/' ; // functions directory

	$css = 'layout/css/' ; //css directory

	$js = 'layout/js/' ; //js directory

	$lang = 'includes/languages/'; //languages directory

	//includes files 

	include $fun . 'function.php';
	include $lang . 'english.php';
	include $tpl . 'header.php'; 


