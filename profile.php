<?php
	
	session_start();

	$pageTitle = 'Profile' ;

	include 'init.php' ;

	if (isset($_SESSION['user'])) {
		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ? ");
		$getUser->execute([$sessionName]);
		$info = $getUser->fetch();
?>

	<div class="information">
		<div class="container">
		<h1 class="text-center">My Profile </h1>
			<div class="panel panel-primary">
				<div class="panel-heading">	My Information</div>
				<div class="panel-body">
					<?php 	echo 'Username : ' . $info['Username'] . '<br />'; 
							echo 'E-mail : ' . $info['Email'] . '<br />'; 
							echo 'Full Name : ' . $info['FullName']; ?>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">My Ads <span class="pull-right">Add <a class='btn btn-xs btn-info' href="add_ads.php">New Ads</a></span></div>
				<div class="panel-body">
					<?php 
						foreach (getItems('Member_ID', $info['UserID']) as $item) {
							echo "<div class='col-md-3 col-sm-4'>";
								echo "<div class='thumbnail items-box'>";
									echo "<span class='price'>".$item['Price']."</span>";
									echo "<img class='img-responsive' src='download.png' />";
									echo "<div class='caption'>";
										echo "<h4><a href='items.php?itemid=" . $item['Item_ID'] . "'>". $item['Name'] ."</a></h1>";
										echo "<p>" . $item['Description'] . "</p>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						}
					?>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">Latest Comments</div>
				<div class="panel-body">
					<?php
						$getCom = $con->prepare("SELECT comment FROM comments WHERE user_id = ?");
						$getCom->execute([$info['UserID']]);

						$rows = $getCom->fetch();

						if(! empty($rows)) {

							echo $rows['comment'];

						} else {
							echo "Not There Any Comments";
						}
					?>
				</div>
			</div>
		</div>
	</div>

<?php } else {

	header('Location: login.php');
	exit();
}
 
 include $tpl . 'footer.php'; ?>