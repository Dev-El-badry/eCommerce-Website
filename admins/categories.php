<?php

	ob_start();


	session_start();

	

	$pageTitle = 'Categories';



		include 'init.php' ;

		$do = isset($_GET['do']) ? $_GET['do'] : 'manage' ;

		if ($do == 'manage') { 

			$sort = 'ASC';

			$sort_array = ['ASC', 'DESC'];

			if(isset($_GET['sort']) && in_array($sort, $sort_array)) {
				$sort = $_GET['sort'];
			}

			$stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
			$stmt2->execute();
			$row = $stmt2->fetchAll();

			if (!empty($row)) {
				
			
			?>
		<div class="cotnainer categories">
			<h1 class="text-center">Manage Categories</h1>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="pull-right">
									Ordering:[
									<a href='?do=manage&sort=ASC' class="<?php if($sort == 'ASC') { echo "active"; } ?>">ASC</a> |
									<a href='?do=manage&sort=DESC' class="<?php if($sort == 'DESC') { echo "active"; } ?>">DESC</a>]
									View: [
									<span data-view="full" class="active">Full</span> | 
									<span data-view="classic">Classic</span>]
								</h4>
								<h4>Manage categories</h4>
							</div>
							<div class="panel-body">
								<?php
									foreach ($row as $cat) { 
										echo "<div class='cat'>";
											echo '<div class="hidden-btn">';
												echo '<a href="?do=edit&catid='. $cat['ID'] .'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i>Edit</a>';
												echo '<a href="?do=delete&catid='. $cat['ID'] .'" class="btn btn-danger btn-xs"><i class="fa fa-tag"></i>Delete</a>';
											echo '</div>';
											echo "<h5>" . $cat['Name'] . "</h5>";
												echo "<div class='full-view'>";
													echo "<p>"; 
													if($cat['Description'] == '') { echo "describtion of cateory is empty"; } else { echo $cat['Description']; } 
													echo "</p>";
													if($cat['Visibility'] == 1) { echo "<span class = 'vis'>Hidden</span>"; }
													if($cat['Allow_Comment'] == 1) { echo "<span class = 'com'>Allow_Comment Displayed</span>"; }
													if($cat['Allow_Ads'] == 1) { echo "<span class = 'ads'>Ads Displayed</span>"; }
												echo "</div>";
											echo "<hr />";
										echo "</div>";
									}
								?>
							</div>
						</div>
					<a href="?do=add" class="btn btn-info"><i class="fa fa-plus"></i>  Add New Categories</a>
				</div>
			</div>
		</div>
			
<?php } else { echo '<div class="container"><span class="nice-msg">This Page Not There Records</span></div>'; }
		} elseif ($do == 'add') { // add categories ?>

		<div class="container">
			<h1 class="text-center">Add Categories</h1>
			<div class="col-md-8 col-md-offset-2">
				<form class="form-horizontal" action="?do=insert" method="POST">
			<!-- Start username field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Name: </label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="name" class="form-control" autocomplete="off" required="required" />
					</div>
				</div>
			<!--End username field -->
			<!-- Start describtion field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Describtion :</label>
					<div class="col-sm-10 col-md-8">
						<textarea class="form-control" name="des"></textarea>
					</div>
				</div>
			<!--End describtion field -->
			<!-- Start ordering field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Ordering</label>
					<div class="col-sm-10 col-md-8">
						<input type="text" name="ordering" class="form-control"  />
					</div>
				</div>
			<!--End ordering field -->
			<!-- Start visibility field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Visibilty</label>
					<div class="col-sm-10 col-md-8">
						<input type="radio" id="vis" name="visibility" value="0" checked />
						<label for="vis">Yes</label>
					</div>
					<div class="col-sm-10 col-md-8">
						<input type="radio" id="vis" name="visibility" value="1" />
						<label for="vis">No</label>
					</div>
				</div>
			<!--End visibility field -->
			<!-- Start commenting field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">Commenting</label>
					<div class="col-sm-10 col-md-8">
						<input type="radio" id="com" name="commenting" value="0" checked />
						<label for="com">Yes</label>
					</div>
					<div class="col-sm-10 col-md-8">
						<input type="radio" id="com" name="commenting" value="1" />
						<label for="com">No</label>
					</div>
				</div>
			<!--End commenting field -->
			<!-- Start advertisement field -->
				<div class="form-group form-group-lg">
					<label class="control-label col-sm-2 col-md-4">advertisement</label>
					<div class="col-sm-10 col-md-8">
						<input type="radio" id="ads" name="advertisement" value="0" checked />
						<label for="ads">Yes</label>
					</div>
					<div class="col-sm-10 col-md-8">
						<input type="radio" id="ads" name="advertisement" value="1" />
						<label for="ads">No</label>
					</div>
				</div>
			<!--End advertisement field -->
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
		} elseif ($do == 'insert') { //insert categories

				// check on the request
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Insert Member</h1>";
					//$id 		= $_POST['user'];
					$name 			= $_POST['name'];
					$describtion 	= $_POST['des'];
					$order 			= $_POST['ordering'];
					$vis 			= $_POST['visibility'];
					$com 			= $_POST['commenting'];
					$ads 			= $_POST['advertisement'];

					echo '<div class="container">';
					//check from server side
					$formError = [];// collect in array

					


					//echo $id . "<br />" . $username . "<br />" . $email . "<br />" . $fullname;
					//update data
					if (empty($formError)) {
						// check username in database
						$value = $name ;

						$check = checkItem('name', 'categories', $value);

						if($check == 1) {
							$theMsg = '<div class="alert alert-danger">This is Name Is Exist</div>';
							redectHome($theMsg, 'back');
						} else {

						//insert datainfo to database 
					$stmt = $con->prepare('INSERT INTO 
												categories(name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads)
												VALUES(:zname, :zdes, :zord, :zvis, :zcom, :zads)');

					$stmt->execute([
						'zname' => $name,
						'zdes' 	=> $describtion,
						'zord' 	=> $order,
						'zvis' 	=> $vis,
						'zcom' 	=> $com,
						'zads' 	=> $ads
						]);

					$theMsg =  '<div class="alert alert-info">' . $stmt->rowCount() . 'update record</div>';
					redectHome($theMsg, 'back');
					} 
					}
				} else {
					echo "<div>";
					redectHome($theMsg, 'back');
					echo "</div>";
				}
				echo '</div>';
			
		} elseif ($do == 'edit') { //edit categories

			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;

			$stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");
			$stmt->execute([$catid]);
			$row = $stmt->fetch();

			$count = $stmt->rowCount();

			
			if ($count > 0) { ?>
				
				<div class="container">
					<h1 class="text-center">Edit Categories</h1>
					<div class="col-md-8 col-md-offset-2">
						<form class="form-horizontal" action="?do=update" method="POST">
							<input type="hidden" name="id" value="<?php echo $row['ID']; ?>">
					<!-- Start username field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Name: </label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" autocomplete="off" required="required" />
							</div>
						</div>
					<!--End username field -->
					<!-- Start describtion field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Describtion :</label>
							<div class="col-sm-10 col-md-8">
								<input type="text" class="form-control" name="des" value="
								<?php echo $row['Description']; ?>" />
							</div>
						</div>
					<!--End describtion field -->
					<!-- Start ordering field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Ordering</label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="ordering" value="<?php echo $row['Ordering']; ?>" class="form-control"  />
							</div>
						</div>
					<!--End ordering field -->
					<!-- Start visibility field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Visibilty</label>
							<div class="col-sm-10 col-md-8">
								<input type="radio" id="vis" name="visibility" value="0" 
								<?php if($row['Visibility'] == 0) { echo "checked"; } ?> />
								<label for="vis">Yes</label>
							</div>
							<div class="col-sm-10 col-md-8">
								<input type="radio" id="vis" name="visibility" value="1" <?php if($row['Visibility'] == 1) { echo "checked"; } ?>/>
								<label for="vis">No</label>
							</div>
						</div>
					<!--End visibility field -->
					<!-- Start commenting field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Commenting</label>
							<div class="col-sm-10 col-md-8">
								<input type="radio" id="com" name="commenting" value="0"
								<?php if($row['Allow_Comment'] == 0) { echo "checked"; } ?> />
								<label for="com">Yes</label>
							</div>
							<div class="col-sm-10 col-md-8">
								<input type="radio" id="com" name="commenting" value="1" 
								<?php if($row['Allow_Comment'] == 1) { echo "checked"; } ?> />
								<label for="com">No</label>
							</div>
						</div>
					<!--End commenting field -->
					<!-- Start advertisement field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">advertisement</label>
							<div class="col-sm-10 col-md-8">
								<input type="radio" id="ads" name="advertisement" value="0"
								<?php if($row['ads'] == 0) { echo "checked"; } ?> />
								<label for="ads">Yes</label>
							</div>
							<div class="col-sm-10 col-md-8">
								<input type="radio" id="ads" name="advertisement" value="1"
								<?php if($row['ads'] == 1) { echo "checked"; } ?> />
								<label for="ads">No</label>
							</div>
						</div>
					<!--End advertisement field -->
					<!-- Start submit field -->
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-md-offset-4 col-sm-2 col-md-4">
								<input type="submit" name="add" value="Save Changes" class="btn btn-primary btn-lg" />
							</div>
						</div>
					<!--End submit field -->
					</form>
					</div>
				</div>

			<?php	
			}
			
		} elseif ($do == 'update') { //update categories

			echo "<div class='container'>";
			echo "<h1 class='text-center'>Update Categories</h1>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = $_POST['id'];
				$name = $_POST['name'];
				$des = $_POST['des'];
				$ord = $_POST['ordering'];
				$vis = $_POST['visibility'];
				$com = $_POST['commenting'];
				$ads = $_POST['advertisement'];	

				$formError = [];

				if (empty($name)) {
					$formError = 'Name is Empty';
				} 

				foreach ($formError as $error) {
					
					echo "<div class='alert alert-danger'>" . $error . "</div>";
				}

				if (empty($formError)) {
					$stmt = $con->prepare("UPDATE categories SET name = ?, Description = ?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? where ID = ? ");
					$stmt->execute([$name, $des, $ord, $vis, $com, $ads, $id]);
					echo '<div class="container"';
					$theMsg =  '<div class="alert alert-info">' . $stmt->rowCount() . 'Update Record</div>' ;
					redectHome($theMsg, 'back');
					echo "</div>";
				}
			} else {
				echo '<div class="container"';
					$theMsg =  '<div class="alert alert-danger">You Can\'t access to this page</div>' ;
					redectHome($theMsg, 'back');
					echo "</div>";
			}
			
		} elseif ($do == 'delete') {
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
			echo "<div class='container'>";
			$check = checkItem('ID', 'categories', $catid);
			if ($check > 0) {
				$stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
				$stmt->bindParam('zid', $catid);
				$stmt->execute();

				$theMsg =  $stmt->rowCount();
				redectHome($theMsg, 'back');
			} else {
				$theMsg = '<div class="alert alert-info">Not Exists this ID</div>';
				redectHome($theMsg, 'back');
			}
			echo "</div>";
		}

		include $tpl . 'footer.php';