<!DOCTYPE html>
<html>
  <head>
	<title><?php getTitle() ?></title>
	<meta charset ="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
	<link rel="stylesheet" href="<?php echo $css; ?>front.css" />
  </head>
  
  <body>
	<div class="upper-bar">
		<div class="container">			        
	        <?php if(isset($_SESSION['user'])){ ?>
		              	               
	                <div class="btn-group my-info">
	               	  <img class="my-img img-thumbnail img-circle" src="img.jpg" alt="">
	               	  <span class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
	               		<?php echo $sessionUser; ?>
	               		<span class="caret"></span>
	               	  </span>
	               	  <ul class="dropdown-menu">
	               	 	<li><a href="profile.php">My Profile</a></li>
	               	 	<li><a href="newad.php">New Item</a></li>
	               	 	<li><a href="profile.php#my-adss">My Items</a></li>
	               	 	<li><a href="logout.php">Logout</a></li>
	               	  </ul>
	                </div>

	 	    <?php }else{ ?>            			
					<a href="login.php">
						<span class="pull-right">Login/Signup</span>
					</a>
			<?php } ?>
		</div>
	</div>
	<nav class="navbar navbar-inverse">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.php">Homepage</a>
	    </div>
	    <div class="collapse navbar-collapse" id="app-nav">
	      <ul class="nav navbar-nav navbar-right">
	        <?php
	         $allCats = getAllFromGen('*','categories','where parent = 0','','ID','ASC');
	          foreach ($allCats as $cat) {
	          	$allCatsCH = getAllFromGen('*','categories','where parent = ' . $cat['ID'] ,'','ID','ASC');
	          	    if (empty($allCatsCH)) {	
			            echo  '<li>
			                      <a href="categories.php?pageid=' . $cat['ID'] . '">
			                      ' . $cat['Name'] . '
			                      </a>
			                  </li>'; 
                    }else{
                    	echo 	'<li class="dropdown">
							      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> ' . $cat['Name'] . ' <span class="caret"></span></a>
							      <ul class="dropdown-menu">'; 
							       		foreach ($allCatsCH as $catCH) {
							       			echo  '<li>
								                      <a href="categories.php?pageid=' . $catCH['ID'] . '">
								                      ' . $catCH['Name'] . '
								                      </a>
								                  </li>'; 
							       		}
						echo	  '</ul>
							    </li>';
                    }
	          }
	        ?>
	      </ul>
	    </div>
	  </div>
	</nav>