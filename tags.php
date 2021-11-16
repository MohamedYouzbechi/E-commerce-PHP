<?php
 session_start();
 $pageTitle = 'Tag Items';
 include 'init.php';
?>

<div class="container">
	<h1 class="text-center">Show Tag Items</h1>
	 <div class="row">
		<?php
		  if(isset($_GET['name'])){
		  	$tag = $_GET['name'];
		  	echo '<h2 class="text-center">Tag Name:' . $tag . '</h2>';
		  	   		  	   
		  	  $tagItems = getAllFromGen("*","items","where Tags like '%$tag%'","and Approve = 1","item_ID");
			  foreach ($tagItems as $item) {
		          echo '<div class = "col-sm-6 col-md-3">';
			          echo '<div class = "thumbnail item-box">';
			              echo '<span class = "price-tag">' . $item['Price'] . '</span>';
				          if (empty($item['Avatar'])) {
				           echo '<img class = "img-responsive" src = "img.jpg" alt = "" />';
			              }else{
				           echo '<img class = "img-responsive" src="admin/uploads/picture/' . $item['Avatar'] . '" alt = "" />';
			              }
				          echo '<div class = "caption">';
					          echo '<h3><a href="item.php?itemid=' . $item['item_ID'] . '">' . $item['Name'] . '</a></h3>';
					          echo '<p>' . $item['Description'] . '</p>';
					          echo '<div class="date">' . $item['Add_Date'] . '</div>';
				          echo '</div>';
			          echo '</div>';
		          echo '</div>';
			  }
		  }else{
		  	echo 'You Must Add Page ID';
		  }
	   ?>
	</div>	
</div>
<?php include $tpl . "footer.php"; ?>