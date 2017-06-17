<?php

	include 'connect.php';

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
	if(!isset($noNavbar)) { include $tpl . 'navbar.php'; }
	

