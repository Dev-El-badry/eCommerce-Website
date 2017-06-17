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

	/*
	**	function get categories v1.0
	**	function to get all categories in database
	*/

	function getCat() {
		global $con;

		$stmt = $con->prepare("SELECT * FROM categories ORDER BY ID DESC");
		$stmt->execute();

		return $stmt->fetchAll();
	}

	/*
	**	function get AD items  v1.0
	**	function to get AD items From database
	*/

	function getItems($where, $value, $approve = NULL) {

		$sql = $approve == NULL ? 'AND Approve = 1' : '' ;

		global $con;

		$stmt = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");
		$stmt->execute([$value]);

		return $stmt->fetchAll();
	}

	/*
	**	function get items v1.0
	**	function to get all items From database
	*/

	function CheckUserActivate($user) {
		global $con;

		$stmt = $con->prepare("SELECT Username, RegStatus FROM users WHERE Username = ?");
		$stmt->execute([$user]);

		return $stmt->rowCount();
	}