<?php
	
	session_start();

	$pageTitle = 'Show Items';

	include 'init.php';

	//$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0 ;

	$stmt = $con->prepare("SELECT items.*, categories.Name AS Cat_Name, users.Username
								 FROM
								  items
								 INNER JOIN 
								 	categories ON categories.ID = items.Cat_ID
								  INNER JOIN 
								 	users ON users.UserID = items.Member_ID
								  WHERE Item_ID = ? AND Approve = 1 ");

	$h = $stmt->execute([$itemid]);
	print_r($h);
	$count = $stmt->rowCount();

	if ($count > 0) {
		$get = $stmt->fetch(); ?>
		<div class="show-item">
			<div class="container">
				<h1 class="text-center"><?php echo $get['Name'] ?></h1>
				<!-- Start Show Items -->
				<div class="row">
					<div class="col-md-3">
						<img class="img-responsive img-thumbnail center-block" src="download.png">
					</div>
					<div class="col-md-9">
						<h2><?php echo $get['Name']; ?></h2>
						<p><?php echo $get['Description']; ?></p>
						<ul class="list-unstyled">
							<li><i class="fa fa-money"></i><span>Price</span>:<?php echo $get['Price']; ?></li>
							<li><i class="fa fa-calendar"></i><span>Date</span>:<?php echo $get['Add_Date']; ?></li>
							<li><i class="fa fa-building"></i><span>Made</span>:<?php echo $get['Country_Made']; ?></li>
							<li><i class="fa fa-tag"></i><span>Category</span>:<?php echo '<a href="categories.php?catid=' . $get['Cat_ID'] . '">' . $get['Cat_Name'] . '</a>'; ?></li>
							<li><i class="fa fa-user"></i><span>Added By:</span>:<?php echo '<a href="profile.php?catid=' . $get['Member_ID'] . '">' . $get['Username'] . '</a>'; ?></li>
						</ul>
					</div>
				</div>
				<!-- End Show Items-->
				<hr />
				<!-- Start Section Add Comments -->
				<?php 
				if (isset($_SESSION['user'])) { ?>
					<section class="add-comment">
						<div class="row">
							<div class="col-md-offset-3">
								<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $get['Item_ID'] ?>" method="POST">
									<textarea name="comment" placeholder="Type Here A Comment" class="form-control"></textarea>
									<input type="submit" name="add-comment" class="btn btn-success add-com" value="Add Comment" />
								</form>
							</div>
						</div>
					</section>
					<?php 
					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
						$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING );
						$itemid 	= $get['Item_ID'];
						$userid 	= $_SESSION['ID'];

						if (! empty($comment)) {
							$stmt = $con->prepare("INSERT INTO 
							comments(comment, status, comment_date, item_id, user_id)
							VALUES(:zcom, 0, NOW(), :zitem, :zuser)");
							$stmt->execute([
								'zcom' 		=> $comment,
								'zitem' 	=> $itemid,
								'zuser' 	=> $userid
								]);

							if ($stmt) {
								echo "<div class='alert alert-success'>Success Added Comment</div>";
							}
						}
					}
				} else { echo "<a href='login.php'>Login</a> OR <a href='login.php'>Register</a> To Added Comment"; } ?>
				<hr />
				<!-- End Section Add Comment -->

				<!-- Start Show Comments -->
				<?php
					$stmt = $con->prepare("SELECT comments.*, users.Username AS member
								 FROM
								  comments
								  INNER JOIN 
								 	users ON users.UserID = comments.user_id
								  WHERE item_id = ? AND status = 1 ORDER BY c_id DESC");

					$stmt->execute([$get['Item_ID']]);

					$comments = $stmt->fetchAll();
				?><div class="show-comment">
				<?php foreach ($comments as $comment) {
					
					 echo $comment['member']; 
					echo $comment['comment']; 
					
				
				 } ?>
				</div>
				<!-- End Show Comments -->


	
<?php } else {
	echo "There Not Such This Is ID";
}
	include $tpl . 'footer.php'; 

?>
