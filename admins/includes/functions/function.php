<?php


	function getTitle() {
		global $pageTitle;

		if (isset($pageTitle)) {
			
			echo $pageTitle;
		} else {
			echo 'Default';
		}
	}

	/*
	** Redirect To Home v1.0
	** $errorMsg = Echo Message Error
	** $seconds = Seconds before redirect
	*/

	function redirectPage($errorMsg, $seconds = 3) {
		echo "<div class='alert alert-danger'>" . $errorMsg . '</div>';
		echo "<div class='alert alert-success'>You Will Redirect After" . $seconds . ' Seconds</div>';

		header("refresh:$seconds;url=index.php");
		exit();
	}

	
	/*
	** Redirect To Home v2.0
	** $theMsg = Echo Message Error
	** $seconds = Seconds before redirect
	** $url = the link you want to redirect it
	*/

	function redectHome($theMsg, $url = null, $second = 3) {

		// check if url is empty or not
		if($url === null) {

			$url = 'index.php' ;

		} else {

				if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') 
				{
					$url = $_SERVER['HTTP_REFERER'];
				} else {
					$url = 'index.php' ;
				}
		}

		echo $theMsg ;
		echo "<div class='alert alert-success'>You Will Redirect After " . $second . ' Seconds</div>';

		header("refresh:$second;url=$url");
		exit();
	}

	/*
	**	function check item v1.0
	** 	function to check item in database
	**	$select = select item in database
	**	$from =  the table to select from
	**	$value = the value of select
	*/

	function checkItem($select, $from, $value) {
		global $con; 

		$statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
		$statment->execute([$value]);
		$count = $statment->rowCount();

		return $count;
	}

	/*
	**	function count item v2.0
	**	function to count items in database
	**	$item = Seclet item in database
	**	$table = the table to select from
	**	Count() to count numbers of items
	*/

	function itemCount($item, $table) {
		global $con;

		$stmt2 = $con->prepare("SELECT count($item) FROM $table");
		$stmt2->execute();

		return $stmt2->fetchColumn();
	}

	/*
	**	get lastest recorder v1.0
	**	getLastest To Get Lastest Records
	**	$select 	= select item in database
	**	$table 		= select table in database
	**	$order 		= way to order items
	**	$limit 		= the limit of numbers of items
	*/

	function getLastest ($select, $table, $order, $limit = 5) {
		global $con;

		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$getStmt->execute();

		$record = $getStmt->fetchAll();

		return $record;
	}


