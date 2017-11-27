<?php

	include 'init.php' ; ?>

	<div class="container">
		<h1 class="text-center">Show Category</h1>
		<div class="row">
			<?php 
				foreach (getItems('Cat_ID', $_GET['id']) as $item) {
					echo "<div class='col-md-3 col-sm-4'>";
						echo "<div class='thumbnail items-box'>";
							if ($item['Approve'] == 0) { echo "Not Approved"; }
							echo "<span class='price'>".$item['Price']."</span>";
							echo "<img class='img-responsive' src='download.png' />";
							echo "<div class='caption'>";
								echo "<h4><a href='items.php?itemid=". $item['Item_ID'] ."'>". $item['Name'] ."</a></h1>";
								echo "<p>" . $item['Description'] . "</p>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
				}
			?>
		</div>
	</div>

<?php
	include $tpl . 'footer.php' ;