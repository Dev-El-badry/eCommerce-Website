<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo getTitle() ?></title>
		<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.css">
		<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo $css; ?>style.css">
		<link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.min.css">
	</head>
	<body>
		<!-- Start Top bar -->
		<div class="top-bar">
			<div class="container"> 
			<?php
				
				if (isset($_SESSION['user'])) {
				 	echo "Welcome" . '  ' . $_SESSION['user'] . "  <a href='profile.php'>My Profile</a> - ";
				 	echo "<a href='logout.php'>Logout</a>";

				 	$status = CheckUserActivate($_SESSION['user']);

				 	if ($status == 0) {
				 		//not need to activate
				 	}
				 } else {
			?>
				<span class="pull-right">
					<a href="login.php">Login/Signup</a>
				</span>
			<?php } ?>
			</div>
		</div>
		<!-- End Top Bar -->

		<!-- Start Navbar -->
		<nav class="navbar navbar-inverse">
		  <div class="container">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="index.php">Home Page</a>
		    </div>

		   
		    <div class="collapse navbar-collapse" id="app-nav">
		      <ul class="nav navbar-nav pull-right"> <?php
		      	foreach (getCat() as $cat) {
		      		echo "<li><a href='categories.php?id=" . $cat['ID'] . "&name=". str_replace(' ', '-', $cat['Name']) ."'>" . $cat['Name'] . "</a></li>";
		      	}
				?>
		      </ul>	      
		    </div>
		  </div>
		</nav>
		<!-- End Navbar -->