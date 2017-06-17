<?php 
	session_start();
	$pageTitle = 'Add New Adds';
	include 'init.php';

	if (isset($_SESSION['user'])) {

		// check on the request
				if($_SERVER['REQUEST_METHOD'] == 'POST') {
					
					$name 			= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
					$describtion 	= filter_var($_POST['describtion'], FILTER_SANITIZE_STRING);
					$made 			= filter_var($_POST['made'], FILTER_SANITIZE_STRING);
					$price 			= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
					$status 		= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
					$cat 			= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

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
						'zmember'	=> $_SESSION['ID'],
						'zcat'		=> $cat
						]);

						if($stmt) {
							echo "Item Added";
						}
					} 
					
				} else {
					echo "<div>";
						
					echo "</div>";
				}
				echo '</div>';

		$getUser = $con->prepare("SELECT * FROM users WHERE Username = ? ");
		$getUser->execute([$sessionName]);
		$info = $getUser->fetch();

?>

<div class="add-ads">
	<div class="container">
	<h1 class="text-center">Add Items</h1>
		<div class="panel panel-primary">
			<div class="panel-heading">Add New Ads</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal" action="?do=insert" method="POST">
					<!-- Start name field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Name: </label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="name" class="form-control live" required="required" data-class=".live-name" />
							</div>
						</div>
					<!--End name field -->
					<!-- Start describtion field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Describtion :</label>
							<div class="col-sm-10 col-md-8">
								<input class="form-control live" name="describtion" required="required" type="text" data-class=".live-desc" />
							</div>
						</div>
					<!--End describtion field -->
					<!-- Start price field -->
						<div class="form-group form-group-lg">
							<label class="control-label col-sm-2 col-md-4">Price: </label>
							<div class="col-sm-10 col-md-8">
								<input type="text" name="price" class="form-control live" required="required" data-class=".live-price" />
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
								<input type="submit" name="add" value="Add Items" class="btn btn-primary btn-lg" />
							</div>
						</div>
					<!--End submit field -->
						</form>
					</div>

					<div class="col-md-4">
						
							<div>
								<div class='thumbnail items-box'>
									<span class='price live-price'>0</span>$
									<img class='img-responsive' src='download.png' />";
									<div class='caption live'>
										<h4 class="live-name">Title</h1>
										<p class="live-desc">Describtion</p>
									</div>
								</div>
							</div>
					
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<?php } else {
	header('Location: inde.php');
	exit();
}


	include $tpl . 'footer.php';
?>