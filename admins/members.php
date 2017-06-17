<?php 

	/*
	==	Manage Page
	==	You Can [Edit - Delete - Add ]
	*/

	include 'init.php';

	$pageTitle = 'Members';

	$do = isset($_GET['do']) ? $_GET['do'] : 'manage' ;

	if ($do == 'manage') { 

		// to show Pending members
		$query = '';

		if(isset($_GET['page']) && $_GET['page'] == 'pending') {

			$query = 'AND RegStatus = 0';
		}

		$stmt = $con->prepare("select * from users where GroupID != 1 $query");
		$stmt->execute();
		$rows = $stmt->fetchAll();

		if (!empty($rows)) {
		?>
		
		<div class="container">
			<h1 class="text-center">Manage Page</h1>

			<div class="table-responsive">
				<table class="main-table text-center table table-bordered">

						<tr>
							<th>#</th>
							<th>Username</th>
							<th>E-mail</th>
							<th>Full Name</th>
							<th>Register Date</th>
							<th>Controls</th>
						</tr>

						
							<?php
								foreach ($rows as $row) {
									echo "<tr>";
									echo "<th>" . $row['UserID'] . '</th>';
									echo "<td>" . $row['Username'] . '</td>';
									echo "<td>" . $row['Email'] . '</td>';
									echo "<td>" . $row['FullName'] . '</td>';
									echo "<td>" . $row['Date'] . '</td>';
									echo "<td>" . 
									'<a href="?do=edit&userid=' . $row['UserID'] . '" class="btn btn-primary"><i class="fa fa-edit"></i>Edit</a>
									 <a href="?do=delete&userid=' . $row['UserID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i>Delete</a>
									';

									if($row['RegStatus'] == 0) {
										echo '<a href="?do=activate&userid=' . $row['UserID'] . '" class="btn btn-info"><i class="fa fa-close"></i>Activate</a>';
									}

									 echo "</td>";
									}
									echo "</tr>";
								
							?>	
						

				</table>
				<a href="?do=add" class="btn btn-success"><i class="fa fa-plus"></i> Add Member</a>
			</div>
		</div>

	<?php } else { echo '<div class="container"><span class="nice-msg">This Page Not There Records</span></div>'; } } elseif ($do == 'add'){ ?>

		<div class="container">
		<h1 class="text-center">Add Member</h1>
			<form class="form-horizontal" action="?do=insert" method="POST">
			<!-- Start username field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Username</label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="username" class="form-control" autocomplete="off" required="required" />
					</div>
				</div>
			<!--End username field -->
			<!-- Start password field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Password</label>
					<div class="col-sm-10 col-md-6">
						<input type="password" name="password" class="password form-control" autocomplete="off" required='required|min:8' />
						<i class="show-pass fa fa-eye fa-2x"></i>
					</div>
				</div>
			<!--End password field -->
			<!-- Start email field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Email</label>
					<div class="col-sm-10 col-md-6">
						<input type="email" name="email" class="form-control" autocomplete="off" required="required" />
					</div>
				</div>
			<!--End email field -->
			<!-- Start fullname field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Full Name</label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="full" class="form-control" required="required" />
					</div>
				</div>
			<!--End fullname field -->
			<!-- Start submit field -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-md-offset-4 col-sm-2 col-md-4">
						<input type="submit" name="add" value="Add Member" class="btn btn-primary btn-lg" />
					</div>
				</div>
			<!--End submit field -->
			</form>
		</div> <!-- End Container -->
		<?php
	} elseif ($do == 'insert') { //insert data
				// check on the request
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Update Member</h1>";
					//$id 		= $_POST['user'];
					$username 	= $_POST['username'];
					$pass 		= $_POST['password'];
					$email 		= $_POST['email'];
					$fullname 	= $_POST['full'];

					$hashpass = sha1($pass);
					echo '<div class="container">';
					//check from server side
					$formError = [];// collect in array

					if(strlen($username) < 4) {
						$formError[] = 'Must be more than 4 characters';
					}
					if(strlen($pass) < 8) {
						$formError[] = 'Must be more than 8 characters';
					}

					if(strlen($fullname) < 8) {
						$formError[] = 'Must be more than 8 characters';
					}

					if (empty($username)) {
						$formError[] = "username is required";
					}

					if (empty($pass)) {
						$formError[] = "Password is required";
					}

					if (empty($email)) {
						$formError[] = "email is required";
					}

					if (empty($fullname)) {
						$formError[] = "fullname is required";
					}

					foreach ($formError as $error) { // show the error in array :D
						echo '<div class="alert alert-danger">' . $error . "</div>";
					}


					//echo $id . "<br />" . $username . "<br />" . $email . "<br />" . $fullname;
					//update data
					if (empty($formError)) {
						// check username in database
						$value = $username ;

						$check = checkItem('Username', 'users', $value);

						if($check == 1) {
							$theMsg = '<div class="alert alert-info">This is User Is Exist</div>';
							redectHome($theMsg, 'back');
						} else {

						//insert datainfo to database 
					$stmt = $con->prepare('INSERT INTO 
												users(Username, Password, Email, FullName, RegStatus, Reg_Date)
												VALUES(:zuser, :zpass, :zemail, :zfull, 1, now())');

					$stmt->execute([
						'zuser' 	=> $username,
						'zpass' 	=> $hashpass,
						'zemail' 	=> $email,
						'zfull' 	=> $fullname
						]);

					$theMsg =  '<div class="alert alert-danger">' . $stmt->rowCount() . 'update record</div>';
					redectHome($theMsg, 'back');
					} 
				}
				} else {
					echo "<div class='container'>";
					$theMsg = '<div class="alert alert-danger">you can\'t access driectly</div>';

					redectHome($theMsg, 'back');
					echo "</div>";
				}
				echo '</div>';

	} elseif ($do == 'edit') { //Edit Page 
		//check user is set and is number
		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;

		$stmt = $con->prepare('select * from users where UserID = ? limit 1');
		
		$stmt->execute([$userid]);

		$row = $stmt->fetch();

		$count = $stmt->rowCount();

		if($count > 0 ) 
		{
	?>
		
		
		<div class="container">
		<h1 class="text-center">Edit Member</h1>
			<form class="form-horizontal" action="?do=update" method="POST">
				<input type="hidden" name="user" value="<?php echo $userid ?>" />
			<!-- Start username field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Username</label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="username" class="form-control" value="<?php echo $row['Username']; ?>" autocomplete="off" required="required" />
					</div>
				</div>
			<!--End username field -->
			<!-- Start password field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Password</label>
					<div class="col-sm-10 col-md-6">
						<input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>">
						<input type="password" name="newpassword" class="form-control" autocomplete="off" />
					</div>
				</div>
			<!--End password field -->
			<!-- Start email field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Email</label>
					<div class="col-sm-10 col-md-6">
						<input type="email" name="email" class="form-control" value="<?php echo $row['Email']; ?>" autocomplete="off" required="required" />
					</div>
				</div>
			<!--End email field -->
			<!-- Start fullname field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Full Name</label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="full" value="<?php echo $row['FullName']; ?>" class="form-control" required="required" />
					</div>
				</div>
			<!--End fullname field -->
			<!-- Start submit field -->
				<div class="form-group form-group-lg">
					<div class="col-sm-offset-2 col-md-offset-4 col-sm-2 col-md-4">
						<input type="submit" name="edit" value="Save Changes" class="btn btn-primary btn-lg" />
					</div>
				</div>
			<!--End submit field -->
			</form>
		</div> <!-- End Container -->

	<?php 
		} else {
			$theMsg = 'Don\'t Exist Such ID';

			redectHome($theMsg, 'back');
		}
			} elseif ($do == 'update') {
				echo "<h1 class='text-center'>Update Member</h1>";
				// check on the request
				if($_SERVER['REQUEST_METHOD'] == 'POST') {

					$id 		= $_POST['user'];
					$username 	= $_POST['username'];
					$email 		= $_POST['email'];
					$fullname 	= $_POST['full'];

					//password trick
					$pass = '';
					if (empty($_POST['newpassword'])) {
						$pass = $_POST['oldpassword'];
					} else {
						$pass = sha1($_POST['newpassword']);
					}
					echo '<div class="container">';
					//check from server side
					$formError = [];// collect in array

					if (empty($username)) {
						$formError[] = "username is required";
					}

					if (empty($email)) {
						$formError[] = "email is required";
					}

					if (empty($fullname)) {
						$formError[] = "fullname is required";
					}

					foreach ($formError as $error) { // show the error in array :D
						echo '<div class="alert alert-danger">' . $error . "</div>";
					}


					//echo $id . "<br />" . $username . "<br />" . $email . "<br />" . $fullname;
					//update data
					if (empty($formError)) {
						$stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?");
					$stmt->execute(array($username, $email, $fullname, $pass, $id));
 
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
			} elseif ($do == 'delete') { //Delete member 

				echo "<h1 class='text-center'>Delete Member</h1>";
				echo "<div class='container'>";

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
				$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
				$stmt->execute([$userid]);
				$count = $stmt->rowCount();

				if ($count > 0) {
					$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuserid");
					$stmt->bindParam(':zuserid', $userid);
					$stmt->execute();

					echo '<div class="alert alert-info">' . $stmt->rowCount() . 'Delte record</div>';
				} else {
					echo "<div class='container'>";
					$theMsg = "<div class='alert alert-danger'>This ID is not exists</div>";
					redectHome($theMsg, 'back');
					echo "<div>";
				}
				echo "</div>";
			} elseif ($do == 'activate') { //Activate Members
				
				echo '<h1 class="text-center">Activate Mambers</h1>' ;
				echo "<div class='container'>";

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
				// statment Database
				$check = checkItem('UserID', 'users', $userid);

				if ($check > 0) {
					$stmt = $con->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ?');
					$stmt->execute([$userid]);
					$stmt->rowCount();

					$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'record activate</div>';
					redectHome($theMsg);
				} else {
					echo "this ID is Exists";
				}

				echo "<div class='container'";
			}
		

	include $tpl . 'footer.php'; ?>