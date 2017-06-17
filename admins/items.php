<?php

	ob_start();


	session_start();

	include 'init.php' ;

	$pageTitle = 'Items';

	if(isset($_SESSION['Username'])) {

		$do = isset($_GET['do']) ? $_GET['do'] : 'manage' ;

		if ($do == 'manage') {

			$query = '';

			if (isset($_GET['approve']) && $_GET['approve'] == 'pending') {
				$query = 'AND Approve = 0';
			}

			// to show Pending members
		$stmt = $con->prepare("SELECT items.*, categories.Name AS Cat_Name, users.Username 
									FROM
									 items
									INNER JOIN
									 categories
									  ON categories.ID = items.Cat_ID
									INNER JOIN
									 users ON
									  users.UserID = items.Member_ID $query");
		$stmt->execute();
		$rows = $stmt->fetchAll();

		if (!empty($rows)) {
		?>
		
		<div class="container">
			<h1 class="text-center">Manage Items</h1>

			<div class="table-responsive">
				<table class="main-table text-center table table-bordered">

						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Describtion</th>
							<th>Price</th>
							<th>Adding Date</th>
							<th>Category</th>
							<th>Username</th>
							<th>Controls</th>
						</tr>

						
							<?php
								foreach ($rows as $row) {
									echo "<tr>";
										echo "<th>" . $row['Item_ID'] . '</th>';
										echo "<td>" . $row['Name'] . '</td>';
										echo "<td>" . $row['Description'] . '</td>';
										echo "<td>" . $row['Price'] . '</td>';
										echo "<td>" . $row['Add_Date'] . '</td>';
										echo "<td>" . $row['Cat_Name'] . '</td>';
										echo "<td>" . $row['Username'] . '</td>';
										echo "<td>" . 
										'<a href="?do=edit&itemid=' . $row['Item_ID'] . '" class="btn btn-primary"><i class="fa fa-edit"></i>Edit</a>
										 <a href="?do=delete&itemid=' . $row['Item_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a>
										';
										if($row['Approve'] == 0) { echo ' <a href="?do=approve&itemid=' . $row['Item_ID'] . '" class="btn btn-success"><i class="fa fa-check"></i>Approve</a>';	}

										 echo "</td>";
										}
										
									echo "</tr>";
								
							?>	
						

				</table>
				<a href="?do=add" class="btn btn-success"><i class="fa fa-plus"></i> Add Item</a>
			</div>

			
		</div>
<?php } else { echo '<div class="container"><span class="nice-msg">This Page Not There Records</span></div>'; }
		} elseif ($do == 'add') { //add items ?>

			<div class="container">
			<h1 class="text-center">Add Items</h1>
			<div class="col-md-8 col-md-offset-2">
				<form class="form-horizontal" action="?do=insert" method="POST">
			<!-- Start name field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Name: </label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="name" class="form-control" required="required" />
					</div>
				</div>
			<!--End name field -->
			<!-- Start describtion field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Describtion :</label>
					<div class="col-sm-10 col-md-8">
						<input class="form-control" name="describtion" required="required" type="text" />
					</div>
				</div>
			<!--End describtion field -->
			<!-- Start price field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Price: </label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="price" class="form-control" required="required" />
					</div>
				</div>
			<!--End price field -->
			<!-- Start country made field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Made: </label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="made" class="form-control" required="required" />
					</div>
				</div>
			<!--End country made field -->
			<!-- Start status field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Status: </label>
					<div class="col-sm-10 col-md-8">
						<select name="status" class="form-control">
							<option value="0">....</option>
							<option value="1">Old</option>
							<option value="2">New</option>
							<option value="3">Used</option>
						</select>
					</div>
				</div>
			<!--End status field -->
			<!-- Start members field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Members: </label>
					<div class="col-sm-10 col-md-8">
						<select name="member" class="form-control">
							<option value="0">....</option>
							<?php
								$stmt = $con->prepare('SELECT * FROM users');
								$stmt->execute();
								$memb = $stmt->fetchAll();
								foreach ($memb as $m) {
									echo "<option value='".$m['UserID']."'>". $m['Username'] ."</option>";
								}
								
							?>
						</select>
					</div>
				</div>
			<!--End members field -->
			<!-- Start categories field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Category: </label>
					<div class="col-sm-10 col-md-8">
						<select name="category" class="form-control">
							<option value="0">....</option>
							<?php
								$stmt = $con->prepare('SELECT * FROM categories');
								$stmt->execute();
								$cats = $stmt->fetchAll();
								foreach ($cats as $cat) {
									echo "<option value='".$cat['ID']."'>". $cat['Name'] ."</option>";
								}
								
							?>
						</select>
					</div>
				</div>
			<!--End categories field -->
			<!-- Start submit field -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-md-offset-4 col-sm-2 col-md-4">
						<input type="submit" name="add" value="Add Categories" class="btn btn-primary btn-lg" />
					</div>
				</div>
			<!--End submit field -->
			</form>
			</div>
		</div>
<?php
		} elseif ($do == 'insert') {
			// check on the request
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Insert Items</h1>";
					$name 			= $_POST['name'];
					$describtion 	= $_POST['describtion'];
					$made 			= $_POST['made'];
					$price 			= $_POST['price'];
					$status 		= $_POST['status'];
					$member 		= $_POST['member'];
					$cat 			= $_POST['category'];

					echo '<div class="container">';
					//check from server side
					$formError = [];// collect in array

					if (empty($name)) {
						$formError[] = 'Must Fill The Name';
					}
					if ($name > 2) {
						$formError[] = 'Must Name More character than 2';
					}

					if (empty($describtion)) {
						$formError[] = 'Must Fill The describtion';
					}
					if ($describtion > 2) {
						$formError[] = 'Must Describtion More character than 2';
					}

					if (empty($made)) {
						$formError[] = 'Must Fill The Country-Made';
					}

					if (empty($price)) {
						$formError[] = 'Must Fill The Price';
					}

					if ($status == '0') {
						$formError[] = 'Must choose The Status';
					}

					if ($member == '0') {
						$formError[] = 'Must choose The member';
					}

					if ($cat == '0') {
						$formError[] = 'Must choose The category';
					}

					foreach ($formError as $error) {
						echo "<div class='alert alert-danger'>" . $error . "</div>";
					}

					//update data
					if (empty($formError)) {

						//insert datainfo to database 
					$stmt = $con->prepare('INSERT INTO 
												items(Name, Description, Price, Add_Date, Country_Made, Status, Member_ID, Cat_ID)
												VALUES(:zname, :zdes, :zprice, now(), :zmade, :zstatus, :zmember, :zcat)');

					$stmt->execute([
						'zname' 	=> $name,
						'zdes' 		=> $describtion,
						'zprice' 	=> $price,
						'zmade' 	=> $made,
						'zstatus' 	=> $status,
						'zmember'	=> $member,
						'zcat'		=> $cat
						]);

					$theMsg =  '<div class="alert alert-info">' . $stmt->rowCount() . 'update record</div>';
					redectHome($theMsg, 'back');
					} 
					
				} else {
					echo "<div>";
					redectHome($theMsg);
					echo "</div>";
				}
				echo '</div>';
			
		} elseif ($do == 'edit') {

			//check user is set and is number
		$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

		$stmt = $con->prepare('select * from items where Item_ID = ?');
		
		$stmt->execute([$itemid]);

		$row = $stmt->fetch();

		$count = $stmt->rowCount();

		if($count > 0 ) 
		{ ?>
		<div class="container">
			<h1 class="text-center">Edit Items</h1>
			<div class="col-md-8 col-md-offset-2">
				<form class="form-horizontal" action="?do=update" method="POST">
					<input type="hidden" name="item" value="<?php echo $itemid ?>" />
			<!-- Start name field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Name: </label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="name" class="form-control" value="<?php echo $row['Name']; ?>" required="required" />
					</div>
				</div>
			<!--End name field -->
			<!-- Start describtion field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Describtion :</label>
					<div class="col-sm-10 col-md-8">
						<input class="form-control" name="describtion" value="<?php echo $row['Description']; ?>" required="required" type="text" />
					</div>
				</div>
			<!--End describtion field -->
			<!-- Start price field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Price: </label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="price" class="form-control" value="<?php echo $row['Price']; ?>" required="required" />
					</div>
				</div>
			<!--End price field -->
			<!-- Start country made field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Made: </label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="made" class="form-control" value="<?php echo $row['Country_Made']; ?>" required="required" />
					</div>
				</div>
			<!--End country made field -->
			<!-- Start status field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Status: </label>
					<div class="col-sm-10 col-md-8">
						<select name="status" class="form-control">
							<option value="0">....</option>
							<option value="1" <?php if($row['Status'] == 1) { echo "selected"; } ?> >Old</option>
							<option value="2" <?php if($row['Status'] == 2) { echo "selected"; } ?> >New</option>
							<option value="3" <?php if($row['Status'] == 3) { echo "selected"; } ?> >Used</option>
						</select>
					</div>
				</div>
			<!--End status field -->
			<!-- Start members field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Members: </label>
					<div class="col-sm-10 col-md-8">
						<select name="member" class="form-control">
							<option value="0">....</option>
							<?php
								$stmt = $con->prepare('SELECT * FROM users');
								$stmt->execute();
								$memb = $stmt->fetchAll();
								foreach ($memb as $m) {
									echo "<option value='".$m['UserID']."'"; 
									if($row['Member_ID'] == $m['UserID']) { echo "selected"; } 
									echo ">". $m['Username'] ."</option>";
								}
								
							?>
						</select>
					</div>
				</div>
			<!--End members field -->
			<!-- Start categories field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Category: </label>
					<div class="col-sm-10 col-md-8">
						<select name="category" class="form-control">
							<option value="0">....</option>
							<?php
								$stmt = $con->prepare('SELECT * FROM categories');
								$stmt->execute();
								$cats = $stmt->fetchAll();
								foreach ($cats as $cat) {
									echo "<option value='".$cat['ID']."'"; 
									if($row['Cat_ID'] == $cat['ID']) { echo "selected"; } 
									echo ">". $cat['Name'] ."</option>";
								}
								
							?>
						</select>
					</div>
				</div>
			<!--End categories field -->
			<!-- Start submit field -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-md-offset-4 col-sm-2 col-md-4">
						<input type="submit" name="add" value="Save Changes" class="btn btn-primary btn-lg" />
					</div>
				</div>
			<!--End submit field -->
			</form>
			</div>

			<div class="row">
				<div class="col-md-12">
					<!-- Start Mangae Comments -->
		<?php
			$stmt = $con->prepare("SELECT
								 comments.*, users.Username AS user
								  FROM comments
								  INNER JOIN 
								  	users ON users.UserID = comments.user_id
								  WHERE item_id = ?
								  ");
			$stmt->execute([$itemid]);
			$rows = $stmt->fetchAll();

			if (!empty($rows)) {
		?>
		
			<h1 class="text-center">Manage [ <?php echo $row['Name']; ?> ] Comments</h1>

			<div class="table-responsive">
				<table class="main-table text-center table table-bordered">

						<tr>
							<th>#</th>
							<th>comment</th>
							<th>Comment Date</th>
							<th>User</th>
							<th>Controls</th>
						</tr>

						
							<?php
								foreach ($rows as $row) {
									echo "<tr>";
									echo "<th>" . $row['c_id'] . '</th>';
									echo "<td>" . $row['comment'] . '</td>';
									echo "<td>" . $row['comment_date'] . '</td>';
									echo "<td>" . $row['user'] . '</td>';
									echo "<td>" . 
									'<a href="?do=edit&comid=' . $row['c_id'] . '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Edit</a>
									 <a href="?do=delete&comid=' . $row['c_id'] . '" class="btn btn-xs btn-danger confirm"><i class="fa fa-close"></i>Delete</a>
									';

									if($row['status'] == 0) {
										echo '<a href="?do=activate&comid=' . $row['c_id'] . '" class="btn btn-xs btn-info"><i class="fa fa-close"></i>Activate</a>';
									}

									 echo "</td>";
									}
									echo "</tr>";
								
							?>	
				</table>
			</div>
			<?php } ?>
			<!-- End Mange Comments -->
				</div>
			</div>
		</div>


		<?php } 
	
		} elseif ($do == 'update') {
			echo "<h1 class='text-center'>Update Items</h1>";
				// check on the request
				if($_SERVER['REQUEST_METHOD'] == 'POST') {

					$id 			= $_POST['item'];
					$name 			= $_POST['name'];
					$describtion 	= $_POST['describtion'];
					$made 			= $_POST['made'];
					$price 			= $_POST['price'];
					$status 		= $_POST['status'];
					$member 		= $_POST['member'];
					$cat 			= $_POST['category'];

					//check from server side
					echo '<div class="container">';
						//check from server side
						$formError = [];// collect in array

						if (empty($name)) {
							$formError[] = 'Must Fill The Name';
						}
						if ($name > 2) {
							$formError[] = 'Must Name More character than 2';
						}

						if (empty($describtion)) {
							$formError[] = 'Must Fill The describtion';
						}
						if ($describtion > 2) {
							$formError[] = 'Must Describtion More character than 2';
						}

						if (empty($made)) {
							$formError[] = 'Must Fill The Country-Made';
						}

						if (empty($price)) {
							$formError[] = 'Must Fill The Price';
						}

						if ($status == '0') {
							$formError[] = 'Must choose The Status';
						}

						if ($member == '0') {
							$formError[] = 'Must choose The member';
						}

						if ($cat == '0') {
							$formError[] = 'Must choose The category';
						}

						foreach ($formError as $error) {
							echo "<div class='alert alert-danger'>" . $error . "</div>";
						}

						//update data
						if (empty($formError)) {
							$stmt = $con->prepare("UPDATE items 
								SET
								 Name = ?,
								 Description = ?, 
								 Price = ?, 
								 Add_Date = now(),
								 Country_Made = ?,
								 Status = ?,
								 Cat_ID = ?,
								 Member_ID = ? 
								 WHERE Item_ID = ?");

						$stmt->execute(array($name, $describtion, $price, $made, $status, $cat, $member, $id));
	 
						$theMsg = '<div class="alert alert-danger">' . $stmt->rowCount() . 'update record</div>';

						redectHome($theMsg, 'back');
						} 
					} else {
						echo "<div class='container'>";
						$theMsg = '<div class="alert alert-danger">you can\'t access driectly</div>';

						redectHome($theMsg, 'back');

						echo "</div>";
					}
				echo '</div>';
		} elseif ($do == 'delete') {
			echo "<h1 class='text-center'>Delete Member</h1>";
			echo "<div class='container'>";

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
				$stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ? LIMIT 1");
				$stmt->execute([$itemid]);
				$count = $stmt->rowCount();

				if ($count > 0) {
					$stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitemid");
					$stmt->bindParam(':zitemid', $itemid);
					$stmt->execute();

					$theMsg = '<div class="alert alert-info">' . $stmt->rowCount() . 'Delte record</div>';
					redectHome($theMsg, 'back');

				} else {
					echo "<div class='container'>";
					$theMsg = "<div class='alert alert-danger'>This ID is not exists</div>";
					redectHome($theMsg, 'back');
					echo "<div>";
				}
			echo "</div>";
			
		} elseif ($do == 'approve') {
			echo '<h1 class="text-center">Approve Items</h1>' ;
			echo "<div class='container'>";

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
				// statment Database
				$check = checkItem('Item_ID', 'items', $itemid);

				if ($check > 0) {
					$stmt = $con->prepare('UPDATE items SET Approve = 1 WHERE Item_ID = ?');
					$stmt->execute([$itemid]);
					$stmt->rowCount();

					$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'record Approve</div>';
					redectHome($theMsg);
				} else {
					echo "this ID is Exists";
				}
		}

	} else {
		header("Location: dashboard.php");
		exit();
	}

	include $tpl . 'footer.php';

	ob_end_flush();