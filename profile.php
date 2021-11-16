<?php 
 session_start();
 $pageTitle = 'Profile';
 include 'init.php';

 if(isset($_SESSION['user'])){

  	$getUser = $con->prepare('SELECT * FROM users WHERE Username = ?');
  	$getUser->execute(array($sessionUser));
  	$info = $getUser->fetch(); ?>

	<h1 class="text-center">My Profile</h1>
	<div class="information block">
	    <div class="container">
	    	<div class="panel panel-primary">
	    		<div class="panel-heading">My Information</div>
	    		<div class="panel-body">
	    		    <ul class="list-unstyled">	
		    			<li><i class="fa fa-unlock-alt fa-fw"></i><span>Login Name</span>: <?php echo $info['Username'] ?> </li>
		    			<li><i class="fa fa-envelope-o fa-fw"></i><span>Email</span>: <?php echo $info['Email'] ?> </li>  
		    			<li><i class="fa fa-user fa-fw"></i><span>Full Name</span>: <?php echo $info['Fullname'] ?> </li> 
		    			<li><i class="fa fa-calendar fa-fw"></i><span>Register Date</span>: <?php echo $info['Date'] ?> </li> 
		    			<li><i class="fa fa-tags fa-fw"></i><span>Fav category</span>: </li>
	    		    </ul>
	    		    <a href="member.php?do=Edit&userid=<?php echo $info['UserID'] ?>" class="btn btn-default">Edit Information</a>	
	    		</div>
	    	</div>
	    </div>  	
	</div>
	<div id="my-adss" class="my-adsE block">
	    <div class="container">
	    	<div class="panel panel-primary">
	    		<div class="panel-heading">My Items</div>
	    		<div class="panel-body">                    
					<?php
						if(! empty(getItems('Member_ID',$info['UserID']))){
						    echo '<div class="row">';	
							   foreach (getItems('Member_ID',$info['UserID']) as $item) {
						          echo '<div class = "col-sm-6 col-md-3">';
							          echo '<div class = "thumbnail item-box">';
							              if($item['Approve'] == 0){echo '<span class="approve-status">Waiting Approval</span>';}
							              echo '<span class = "price-tag">$' . $item['Price'] . '</span>';
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
							echo '</div>';   
					    }else{
					    	echo 'There\'s No Ads To Show, Create <a href = "newad.php">New Ads</a>';
					    }
					?>
	    		</div>
	    	</div>
	    </div>  	
	</div>
	<div class="my-comments block">
	    <div class="container">
	    	<div class="panel panel-primary">
	    		<div class="panel-heading">Latest Comments</div>
	    		<div class="panel-body">
		    		<?php
				        $stmt = $con->prepare("SELECT comment FROM comments WHERE user_id = ?");
			            $stmt ->execute(array($info['UserID']));
			            $comments = $stmt->fetchAll();
			           
			            if(! empty($comments)){
				           	foreach ($comments as $comment) {
				           		echo '<p>' . $comment['comment'] . '</p>'; 
				           	}
			            }else{
			           	    echo 'There\'s No Comment To Show.';
			            }
			        ?>
	    		</div>
	    	</div>
	    </div>  	
	</div>
<?php 

  }else{
  	header('Location: login.php');
  	exit();
  }

  include $tpl . "footer.php"; 
?>