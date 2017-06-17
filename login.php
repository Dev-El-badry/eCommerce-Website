<?php
	session_start();

	$pageTitle = 'Login';

	if (isset($_SESSION['user'])) {
		
		header('Location: index.php');
		exit();
	}

	include "init.php" ; 

	//check
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
		if (isset($_POST['login'])) {
			$user = $_POST['username'];
			$pass = $_POST['pass'];
			$hashedpass = sha1($pass);

			$stmt = $con->prepare("select UserID, Username , Password from users where Username = ? AND Password = ?");

			$stmt->execute(array($user, $hashedpass));

			$get = $stmt->fetch();

			$count = $stmt->rowCount();
			

			//check if count > 0
			if ($count > 0) {

				$_SESSION['user'] = $user;
				
				$_SESSION['ID'] = $get['UserID'];

				header('Location: index.php');
				exit();
			}
		} else {
			$formError = [];

			$user = $_POST['username'];
			$password = $_POST['pass'];
			$email = $_POST['email'];
			 
			if (isset($user)) {
				$filterUser = filter_var($_POST['username'], FILTER_SANITIZE_STRING);

				echo $filterUser;

				if(strlen($filterUser) < 4) {
					$formError[] = "Must Be Username More Than 4 chars";
				}
			}

			if (isset($password)) {

				if (strlen($_POST['pass']) < 4) {
					$formError[] = "Must Be Password More Than 4 Chars";
				}

				$pass = sha1($_POST['pass']);
			}

			if (isset($email)) {
				$filterMail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

				if (filter_var($filterMail, FILTER_VALIDATE_EMAIL) !== true) {
					$formError[] = 'Plz Enter Valid Email';
				}
			}

			if (! empty($formError)) {
				// check username in database
					$value = $user ;

					$check = checkItem('Username', 'users', $value);

					if($check == 1) {
						$theMsg = '<div class="alert alert-info">This is User Is Exist</div>';
						redectHome($theMsg, 'back');
					} else {

						//insert datainfo to database 
					$stmt = $con->prepare('INSERT INTO 
												users(Username, Password, Email, RegStatus, `Date`)
												VALUES(:zuser, :zpass, :zemail, 0, now())');

					$stmt->execute([
						'zuser' 	=> $filterUser,
						'zpass' 	=> $pass,
						'zemail' 	=> $filterMail
						]);

					$theMsg =  '<div class="alert alert-danger">' . $stmt->rowCount() . 'update record</div>';
					redectHome($theMsg, 'back');
					} 
				}
			}
		}?>   
	<!-- start Form -->
	<div class="container login-form">
		<h1 class="text-center">
			<span class="selected" data-class="login">Login</span> | 
			<span data-class="signup">Signup</span>
		</h1>
		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- Login Form -->
			<input type="text" name="username" autocomplete="off" placeholder="Type Username " class="form-control" required="required" />
			<input type="password" name="pass" autocomplete="off" placeholder="Type password" class="form-control" required="required" />
			<input type="submit" name="login" value="Login" class="btn btn-primary btn-block" />
		</form> <!-- End Login Form -->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- signup Form -->
			<input type="text" name="username" autocomplete="off" placeholder="Type Username " class="form-control" required="required"/>

			<input type="password" name="pass" autocomplete="off" placeholder="Type password" class="form-control" required="required" />

			<input type="text" name="email" autocomplete="off" placeholder="Type E-Mail " class="form-control" required="required" />
			
			<input type="submit" name="signup" value="Signup" class="btn btn-success btn-block" required="required" />

		</form> <!-- End signup Form -->
	<div class="center-text error-group">
			<?php
				if (! empty($formError)) {
					foreach($formError as $error) {
						echo "<div class='container'>";
							echo "<p class='error'>" . $error . "</p>";
						echo "</div>";
					}
				}
			?>
		</div>
	</div>
	<!-- end Form -->
<?php 
	include $tpl . 'footer.php'; ?>