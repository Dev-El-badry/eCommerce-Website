<?php

	session_start();
		$pageTitle = 'Dashboard';
	if (isset($_SESSION['Username'])) {
		include 'init.php'; 

		?>
	<!--Start Dashboard -->


	<div class="stat">
		<h1 class="text-center">Dashboard</h1>
		<div class="container text-center">
			<div class="col-md-3">
				<div class="box st-member">
					<p>Total Member</p>
					<i class="fa fa-users"></i>
					<div class="info">
						<span><a href="members.php?do=manage"><?php echo itemCount('UserID', 'users'); ?></a></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="box st-pending">
					<p>Pending Member</p>
					<i class="fa fa-user-plus"></i>
					<div class="info">
						<span>
							<a href="categories.php?do=manage"><?php echo itemCount('ID', 'categories'); ?></a>
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="box st-item">
					<p>Total Item</p>
					<i class="fa fa-tag"></i>
					<div class="info">
						<span>
							<a href="items.php?do=manage"><?php echo itemCount('Item_ID', 'items'); ?></a>
						</span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="box st-comments">
				<p>Total Comments</p>
				<i class="fa fa-comments"></i>
					<div class="info">
						<span><a href="comments.php?do=manage"><?php echo itemCount('c_id', 'comments'); ?></a></span>
					</div>
				</div>
			</div>
		</div> <!-- end container -->
	</div>

	<div class="lastest">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"></i>Latest Registers Users
							<span class="pull-right note">
								<i class="fa fa-plus"></i>
							</span>
						</div>
						<div class="panel-body">
							<?php 
							$lastes = getLastest('*', 'users', 'UserID') ;

							
							echo "<ul class='list-unstyled record'>";
								foreach ($lastes as $names) {
									echo '<li>';
										echo $names['Username'];
										echo "<a href='members.php?do=edit&userid=" . $names['UserID'] ."'>";
											echo "<span class='btn btn-xs btn-primary pull-right'>" ;
												echo "<i class='fa fa-edit'></i>Edit";
												if ($names['RegStatus'] == 0) {
													echo "<a href='members.php?do=activate&userid=" . $names['UserID'] ."' class='btn btn-xs btn-success pull-right button'>
														<i class='fa fa-tag'></i>
														Activate</a>";
												}
											echo "</span>";
										echo '</a>';
									echo "</li>";
								}
							echo "</ul>";
							?>
						</div>
					</div> <!-- End panel -->
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-tag"></i>Latest Items
							<span class="pull-right note">
								<i class="fa fa-plus"></i>
							</span>
						</div>
						<div class="panel-body"><?php
						$lastestItem = getLastest('*', 'items', 'Item_ID');
							echo "<ul class='list-unstyled record'>";
							foreach ($lastestItem as $item) {
									echo '<li>';
										echo $item['Name'];
										echo "<a href='items.php?do=edit&itemid=" . $item['Item_ID'] ."'>";
											echo "<span class='btn btn-xs btn-primary pull-right'>" ;
												echo "<i class='fa fa-edit'></i>Edit";
												if ($item['Approve'] == 0) {
													echo "<a href='members.php?do=activate&userid=" . $item['Item_ID'] ."' class='btn btn-xs btn-success pull-right button'>
														<i class='fa fa-tag'></i>
														Activate</a>";
												}
											echo "</span>";
										echo '</a>';
									echo "</li>";
								} 
							echo "</ul>";
								?>
						</div>
					</div> <!-- End panel -->
				</div>
			</div> <!-- End first .row-->

			<div class="row">
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-comments"></i>Latest Comments
							<span class="pull-right note">
								<i class="fa fa-plus"></i>
							</span>
						</div>
						<div class="panel-body">
							<?php
								$stmt = $con->prepare("SELECT comments.*, users.Username FROM comments
									INNER JOIN 
										users ON users.UserID = comments.user_id
									");
								$stmt->execute();
								$comment = $stmt->fetchAll();

								foreach ($comment as $com) {
									echo "<div class='comment-box'>";
										echo "<span class='member-n col-md-1'>" . $com['Username'] . "</span>";
										echo "<p class='member-c col-md-10 col-md-offset-1'>" . $com['comment'] . "</p>";
									echo "</div>";			
								}
							?>
						</div>
					</div> <!-- End panel -->
				</div>
				<div class="col-md-6">	
				
				</div>
			</div><!-- End Second .row -->
		</div>
	</div>

	<!-- End Dashboard -->
<?php
		include $tpl . 'footer.php';
	} else {
		header('Location: index.php');
		exit();
	}