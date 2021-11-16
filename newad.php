<?php 
 session_start();
 $pageTitle = 'New Ad';
 include 'init.php';

 if(isset($_SESSION['user'])){

  	if($_SERVER['REQUEST_METHOD'] == 'POST'){

  		$avatarName = $_FILES['avatar']['name'];
		$avatarSize = $_FILES['avatar']['size'];
		$avatarTmp = $_FILES['avatar']['tmp_name'];
		$avatarType = $_FILES['avatar']['type'];

		//list of allowed file typed To Upload
		$avatarAllowedExtension = array('jpeg','jpg','png','gif');

		//get avatar extension
		$ava = explode('.', $avatarName);
		$avatarExtension = strtolower(end($ava));
  	 	
  	 	$formErrors = array();

  	 	$name     = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
  	 	$desc 	  = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
  	 	$price    = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
  	 	$country  = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
  	 	$status   = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
  	 	$category = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
  	 	$tags = filter_var($_POST['tags'],FILTER_SANITIZE_STRING);

  	 	if(strlen($name) < 4){
  	 		$formErrors[] = 'Item Title Must Be AT Least 4 Character';
  	 	}
  	 	if(strlen($desc) < 10){
  	 		$formErrors[] = 'Item Description Must Be AT Least 10 Character';
  	 	}
  	 	if(strlen($country) < 2){
  	 		$formErrors[] = 'Item country Must Be AT Least 2 Character';
  	 	}
  	 	if(empty($price)){
  	 		$formErrors[] = 'Item Price Must Be Not Empty';
  	 	}
  	 	if(empty($status)){
  	 		$formErrors[] = 'Item status Must Be Not Empty';
  	 	}
  	 	if(empty($category)){
  	 		$formErrors[] = 'Item category Must Be Not Empty';
  	 	}
  	 	if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
			$formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
		}
		if (empty($avatarName)) {
			$formErrors[] = 'Avatar Is <strong>Required</strong>';
		}
		if ($avatarSize > 4194304) {
			$formErrors[] = 'Avatar can\'t Be larger than <strong>4MB</strong>';
		}

  	 	// check if there's no error proceed the update operation
		               
        if(empty($formErrors)){
        	$avatar = rand(0,10000000000) . '_' . $avatarName;
			move_uploaded_file($avatarTmp, "admin\uploads\picture\\" . $avatar);		               	 

   	 		$stmt = $con->prepare("insert into items (name,description, price, country_made, Status, Add_Date, Cat_ID, Member_ID, Tags, Avatar) 
   	 	 	        	VALUES (:Zname, :Zdesc, :Zprice, :Zcountry, :Zstatus, now(), :Zcat, :Zmember, :Ztags, :Zavatar )");
   	 		$stmt ->execute(array(
		   	 	 	    'Zname'		=> $name, 'Zdesc'	 => $desc, 
		   	 	 	    'Zprice'	=> $price,'Zcountry' => $country,
		   	 	 	    'Zstatus'	=> $status,'Zcat' 	 => $category,
		   	 	 	    'Zmember'	=> $_SESSION['uid'],
		   	 	 	    'Ztags' => $tags,'Zavatar' => $avatar));
   	 	    if($stmt){
   	 	   		$succesMsg = 'Item Added';
   	 	    }
        }

  	}
?>
 
<h1 class="text-center">Create New Ad</h1>
<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Create New Ad</div>
			<div class="panel-body">
			    <div class="row">
			    	<div class="col-md-8">
						<form class = "form-horizontal main-form" action ='<?php echo $_SERVER['PHP_SELF'] ?>' method="POST" enctype="multipart/form-data">
			              	<!--Start name field -->
			              	<div class="form-group form-group-lg" >
			              	 	<label class="col-sm-3 control-label">Name</label>
			              	 	<div class="col-sm-10 col-md-9">
			              	 	  	<input pattern=".{4,}" title="This Field requier At least 4 Character" 
			              	 	  	       type="text" 	name ="name" 
			              	 	  	       class ="form-control live" 
			              	 	  	       required= "required" placeholder="Name of the Item"
			              	 	  	       data-class=".live-title" />
			              	 	</div>
			              	</div>            	
			                <!--End name field -->
			                <!--Start Description field -->
			              	<div class="form-group form-group-lg" >
			              	 	<label class="col-sm-3 control-label">Description</label>
			              	 	<div class="col-sm-10 col-md-9">
			              	 	  	<input pattern=".{10,}" title="This Field requier At least 10 Character"
			              	 	  	       type="text" 	name ="description" 
			              	 	  	       class ="form-control live"  
			              	 	  	       placeholder="Description of the Item"
			              	 	  	       data-class=".live-desc" required />
			              	 	</div>
			              	</div>            	
			                <!--End Description field -->
			                <!--Start Price field -->
			              	<div class="form-group form-group-lg" >
			              	 	<label class="col-sm-3 control-label">Price</label>
		              	 	 	<div class="col-sm-10 col-md-9">
			              	 	  	<input type="text"	name ="price" 
			              	 	  	       class ="form-control live"  
			              	 	  	       placeholder="Price of the Item"
			              	 	  	       data-class=".live-price" required />
		              	 	 	</div>
			              	</div>            	
			                <!--End Price field -->
			                <!--Start Country field -->
			              	<div class="form-group form-group-lg" >
			              	 	<label class="col-sm-3 control-label">Country</label>
		              	 	    <div class="col-sm-10 col-md-9">
		              	 	  		<input type="country" name ="country" class ="form-control" placeholder="Contry of Made" required />
		              	 	    </div>
			              	</div>            	
			                <!--End Country field -->
			                <!--Start Status field -->
			              	<div class="form-group form-group-lg" >
			              	 	<label class="col-sm-3 control-label">Status</label>
			              	 	<div class="col-sm-10 col-md-9">
			              	 	  	<select name="status" required>
			              	 	  		<option value="">...</option>
			              	 	  		<option value="1">New</option>
			              	 	  		<option value="2">Like New</option>
			              	 	  		<option value="3">Used</option>
			              	 	  		<option value="4">very Old</option>
			              	 	  	</select>
			              	 	</div>
			              	</div>            	
			                <!--End Status field -->
			                <!--Start categories field -->
			              	<div class="form-group form-group-lg" >
			              	 	<label class="col-sm-3 control-label">Category</label>
			              	 	<div class="col-sm-10 col-md-9">
			              	 	  	<select name="category" required>
			              	 	  		<option value="0">...</option>  		
			              	 	  		  <?php	              	 	  	
				              	 	  		 $allCats = getAllFromGen("*","categories","where parent = 0","","ID");
				              	 	  		 foreach ($allCats as $cat) {
				              	 	  		 	echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] .  '</option>';
				              	 	  		 	$childCats = getAllFromGen("*","categories","where parent = {$cat['ID']}","","ID");
				              	 	  		 	foreach ($childCats as $child) {
				              	 	  		 	     echo '<option value="' . $child['ID'] . '">---' . $child['Name'] .  '</option>';
				              	 	  		 	}     
				              	 	  		 }
			              	 	  		  ?>
			              	 	  	</select>
			              	 	</div>
			              	</div>            	
			                <!-- End categories field -->
			                <!--Start tags field -->
			              	 <div class="form-group form-group-lg" >
			              	 	<label class="col-sm-3 control-label">Tags</label>
			              	 	<div class="col-sm-10 col-md-9">
			              	 	  	<input type="text" name ="tags" class ="form-control"  placeholder="Separate Tags With Comma (,)"/>
			              	 	</div>
			              	</div>            	
			                <!--End tags field -->
			                <!--Start avatar field -->
			              	<div class="form-group form-group-lg">
			              	 	<label class="col-sm-3 control-label">Picture of Item</label>
			              	 	<div class="col-sm-10 col-md-9">
			              	 	  	<input id="mycls" type="file" onchange="readURL(this);" name ="avatar" class ="form-control" required= "required"/>
			              	 	</div>
			              	</div>            	
			                <!--End avatar field --> 
			                <!--Start Submit field -->
			              	 <div class="form-group form-group-lg">
			              	 	<div class="col-sm-offset-3 col-sm-9">
			              	 	  	<input type="submit" value ="Add Item" class ="btn btn-primary btn-lg " />
			              	 	</div>
			              	 </div>            	
			                <!--End Submit field -->      		                
		                </form>
			    	</div>

			    	<div class="col-md-4">
			    		<div class="thumbnail item-box live-preview">
			    			<span class="price-tag">
			    			  $<span class="live-price">0</span>
			    		    </span>
			    			<img id="image_preview" class="img-responsive" src="no_thumb.jpg" alt="">
			    			<div class="caption">
			    				<h3 class="live-title">Title</h3>
			    				<p class="live-desc">Description</p>
			    			</div>
			    		</div>
			    	</div>
			    </div>
			    <!--Start looping Through Errors -->
			    <?php
			        if(! empty($formErrors)){
	                    foreach ($formErrors as $error) {
	                    	echo '<div class ="alert alert-danger">' . $error . '</div>';
	                    }
			        }
			        if(isset($succesMsg)){
						echo '<div class = "alert alert-success">' . $succesMsg . '</div>';
					}
			    ?>
			    <!--End looping Through Errors -->
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