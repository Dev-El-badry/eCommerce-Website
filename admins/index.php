	<?php 
	session_start();

	$pageTitle = 'Login';

	$noNavbar = '';
	if (isset($_SESSION['Username'])) {
		
		header('Location: dashboard.php');
		exit();
	}

	include "init.php" ; 

	//check
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		$username = $_POST['username'];
		$pass = $_POST['password'];
		$hashedpass = sha1($pass);

		$stmt = $con->prepare("select UserID, Username , Password from users where Username = ? AND Password = ? And GroupID = 1 limit 1");
		$stmt->execute(array($username, $hashedpass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
		
		//check if count > 0
		if ($count > 0) {
			
			$_SESSION['ID'] = $row['UserID'];

			$_SESSION['Username'] = $username;

			header('Location: dashboard.php');
			exit();
		}
	}

	?>
	<!-- Start Form -->
	<form class="login" action="<?php 	echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<h2 class="text-center">Login Form</h2>
		<input class="form-control input-lg" type="text" name="username" placeholder="username" autocomplete="off" />
		<input class="form-control input-lg" type="password" name="password" placeholder="password" autocomplete="new-password" />
		<input class="btn btn-block btn-primary btn-lg" type="submit" value="Login" />
	</form>
	<!-- End Form -->
	<?php include $tpl . 'footer.php' ; ?>