<?php 

	/*
	==========================
	==	Manage Comments
	==	You Can [Edit - Delete]
	==========================
	*/

	include 'init.php';

	$pageTitle = 'Comments';

	$do = isset($_GET['do']) ? $_GET['do'] : 'manage' ;

	if ($do == 'manage') { 

		// to show Pending members
		$query = '';

		if(isset($_GET['status']) && $_GET['status'] == 'pending') {

			$query = 'AND status = 0';
		}

		$stmt = $con->prepare("SELECT
								 comments.*, items.Name AS item_name, users.Username AS user
								  FROM comments
								  INNER JOIN
								  	items ON items.Item_ID = comments.item_id
								  INNER JOIN 
								  	users ON users.UserID = comments.user_id $query
								  ");
		$stmt->execute();
		$rows = $stmt->fetchAll();

		if (!empty($rows)) {
		?>
		
		<div class="container">
			<h1 class="text-center">Manage Comments</h1>

			<div class="table-responsive">
				<table class="main-table text-center table table-bordered">

						<tr>
							<th>#</th>
							<th>comment</th>
							<th>Comment Date</th>
							<th>User</th>
							<th>Item</th>
							<th>Controls</th>
						</tr>

						
							<?php
								foreach ($rows as $row) {
									echo "<tr>";
									echo "<th>" . $row['c_id'] . '</th>';
									echo "<td>" . $row['comment'] . '</td>';
									echo "<td>" . $row['comment_date'] . '</td>';
									echo "<td>" . $row['user'] . '</td>';
									echo "<td>" . $row['item_name'] . '</td>';
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
		</div>

	<?php
	 } else { echo '<div class="container"><span class="nice-msg">This Page Not There Records</span></div>'; }
	} elseif ($do == 'edit') { //Edit Page 
		//check user is set and is number
		$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0 ;

		$stmt = $con->prepare('select * from comments where c_id = ?');
		
		$stmt->execute([$comid]);

		$row = $stmt->fetch();

		$count = $stmt->rowCount();

		if($count > 0 ) 
		{
	?>
		
		
		<div class="container">
		<h1 class="text-center">Edit Comment</h1>
			<form class="form-horizontal" action="?do=update" method="POST">
				<input type="hidden" name="comment-id" value="<?php echo $comid ?>" />
			<!-- Start Comment field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Comment: </label>
					<div class="col-sm-10 col-md-6">
						<input type="text" name="com" class="form-control" value="<?php echo $row['comment']; ?>" autocomplete="off" required="required" />
					</div>
				</div>
			<!--End Comment field -->
			
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

					$id 		= $_POST['comment-id'];
					$comment 	= $_POST['com'];

					echo '<div class="container">';

					//update data
					$stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");

					$stmt->execute(array($comment, $id));
 
					$theMsg = '<div class="alert alert-danger">' . $stmt->rowCount() . 'update Comment</div>';

					redectHome($theMsg, 'back');

				} else {
					echo "<div class='container'>";
					$theMsg = '<div class="alert alert-danger">you can\'t access driectly</div>';

					redectHome($theMsg, 'back');

					echo "</div>";
				}
				echo '</div>';
			} elseif ($do == 'delete') { //Delete member 

				echo "<h1 class='text-center'>Delete Comment</h1>";
				echo "<div class='container'>";

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
				$stmt = $con->prepare("SELECT * FROM comments WHERE c_id = ?");
				$stmt->execute([$comid]);
				$count = $stmt->rowCount();

				if ($count > 0) {
					$stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zcomid");
					$stmt->bindParam(':zcomid', $comid);
					$stmt->execute();

					$theMsg = '<div class="alert alert-info">' . $stmt->rowCount() . 'Delte Comment</div>';
					redectHome($theMsg, 'back');
				} else {
					echo "<div class='container'>";
					$theMsg = "<div class='alert alert-danger'>This ID is not exists</div>";
					redectHome($theMsg, 'back');
					echo "<div>";
				}
				echo "</div>";
			} elseif ($do == 'activate') { //Activate Members
				
				echo '<h1 class="text-center">Approve Comment</h1>' ;
				echo "<div class='container'>";

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;
				// statment Database
				$check = checkItem('c_id', 'comments', $comid);

				if ($check > 0) {
					$stmt = $con->prepare('UPDATE comments SET status = 1 WHERE c_id = ?');
					$stmt->execute([$comid]);
					$stmt->rowCount();

					$theMsg = '<div class="alert alert-primary">' . $stmt->rowCount() . 'Aprrove Comment/div>';
					redectHome($theMsg);
				} else {
					echo "this ID is Exists";
				}

			}
		

	include $tpl . 'footer.php'; ?>