<?php
 
 ob_start(); // Output Buffering Start
 session_start();
 $pageTitle  = 'Items';

 if(isset($_SESSION['username'])){

	include 'init.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    
    if ($do  == 'Manage'){       
 		$stmt = $con->prepare("SELECT 
   	                              items.*,
   	                              categories.Name AS category_name,
   	                              users.username
                              FROM 
                                  items
                              INNER JOIN 
                                  categories
                              ON
                                  categories.ID = items.Cat_ID
                              INNER JOIN 
                                  users
                              ON
                                  users.UserID = items.Member_Id      
                              ORDER BY
   	                              item_ID DESC");
        $stmt ->execute();
        $items = $stmt->fetchAll();

        if(! empty($items)){

?>
            <h1 class = "text-center">Manage Items</h1>    
            <div class = "container">
	           	<div class = "table-responsive">
	           		<table class="main-table text-center table table-bordered">
	           			<tr>
	           				<td>#ID</td>
	           				<td>Name</td>
	           				<td>Description</td>
	           				<td>Price</td>
	           				<td>Adding Date</td>
	           				<td>Category</td>
	           				<td>Username</td>
	           				<td>Control</td>
	           			</tr>

	                    <?php
	                     foreach ($items as $item) {
	                       echo "<tr>";	
	                        	echo "<td>" . $item['item_ID'] . "</td>";
	                        	echo "<td>" . $item['Name'] . "</td>";
	                        	echo "<td>" . $item['Description'] . "</td>";
	                        	echo "<td>" . $item['Price'] . "</td>";
	                        	echo "<td>" . $item['Add_Date'] . "</td>";
	                        	echo "<td>" . $item['category_name'] . "</td>";
	                        	echo "<td>" . $item['username'] . "</td>";
	                        	  echo "<td>
	                        	       <a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' class='btn btn-success'><i class = 'fa fa-edit'></i>Edit</a>
		           					   <a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger confirm'><i class = 'fa fa-close'></i>Delete</a>";		                        	      		                        	    
	                        	      
	                        	       if($item['Approve'] == 0){
	                        	         echo "<a href='items.php?do=Approve&itemid=" . $item['item_ID'] . "' class='btn btn-info activate'><i class = 'fa fa-check'></i>Approve</a>";
	                        	       }
	                        	  echo "</td>";
	                       echo "</tr>";		      
	                     } 
	                    ?>
	           			
	           		</table>
	           	</div>	
    	     	<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Items</a>
    	    </div> 
<?php 

        }else{
         	echo '<div class = "container">';
	         	echo '<div class = "nice-message">There\'s No Items To show.</div>';
	         	echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New Items</a>'; 
         	echo '</div>'; 
        }  

 	} elseif ($do  == 'Add') { ?>
        
        <h1 class = "text-center">Add New Item</h1>          
        <div class = "container">
            <form class = "form-horizontal" action ='?do=Insert' method="POST" enctype="multipart/form-data">
	          	<!--Start name field -->
	          	<div class="form-group form-group-lg" >
          	 	<label class="col-sm-2 control-label">Name</label>
          	 	    <div class="col-sm-10 col-md-6">
          	 	  		<input type="text" 	name ="name" class ="form-control" required= "required" placeholder="Name of the Item"/>
          	 	    </div>
	          	 </div>            	
	            <!--End name field -->
	            <!--Start Description field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Description</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" 	name ="description" class ="form-control"  placeholder="Description of the Item"/>
	          	 	  </div>
	          	 </div>            	
	            <!--End Description field -->
	            <!--Start Price field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Price</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" 	name ="price" class ="form-control"  placeholder="Price of the Item"/>
	          	 	  </div>
	          	 </div>            	
	            <!--End Price field -->
	             <!--Start Country field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Country</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" name ="country" class ="form-control"  placeholder="Contry of Made"/>
	          	 	  </div>
	          	 </div>            	
	            <!--End Country field -->
	             <!--Start Status field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Status</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<select name="status">
	          	 	  		<option value="0">...</option>
	          	 	  		<option value="1">New</option>
	          	 	  		<option value="2">Like New</option>
	          	 	  		<option value="3">Used</option>
	          	 	  		<option value="4">very Old</option>
	          	 	  	</select>
	          	 	  </div>
	          	 </div>            	
	            <!--End Status field -->
	            <!--Start Members field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Member</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<select name="member">
	          	 	  		<option value="0">...</option>
	          	 	  		 <?php
	              	 	  		 $stmt = $con->prepare('SELECT * FROM users');
	              	 	  		 $stmt->execute();
	              	 	  		 $users = $stmt->fetchAll();
	              	 	  		 foreach ($users as $user) {
	              	 	  		 	echo '<option value="' . $user['UserID'] . '">' . $user['Username'] .  '</option>';
	              	 	  		 }
	          	 	  		 ?>
	          	 	  	</select>
	          	 	  </div>
	          	 </div>            	
	           <!-- End Members field -->
	           <!--Start categories field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Category</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<select name="category">
	          	 	  		<option value="0">...</option>
	          	 	  		 <?php		              	 	  	
	              	 	  		 $allCats = getAllFrom("*","categories","where parent = 0","","ID");
	              	 	  		 foreach ($allCats as $cat) {
	              	 	  		 	echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] .  '</option>';
	              	 	  		 	$childCats = getAllFrom("*","categories","where parent = {$cat['ID']}","","ID");
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
	          	 	<label class="col-sm-2 control-label">Tags</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" name ="tags" 
	          	 	  	       class ="form-control"  placeholder="Separate Tags With Comma (,)"/>
	          	 	  </div>
	          	 </div>            	
	            <!--End tags field -->
	            <!--Start avatar field -->
	          	<div class="form-group form-group-lg">
	          	 	<label class="col-sm-2 control-label">Picture of Item</label>
	          	 	<div class="col-sm-10 col-md-6">
	          	 	  	<input type="file" name ="avatar" class ="form-control" required= "required"/>
	          	 	</div>
	          	</div>            	
	            <!--End avatar field --> 
	             <!--Start Submit field -->
	          	 <div class="form-group form-group-lg">
	          	 	  <div class="col-sm-offset-2 col-sm-10">
	          	 	  	<input type="submit" 	value ="Add Item" class ="btn btn-primary btn-lg " />
	          	 	  </div>
	          	 </div>            	
	            <!--End Submit field -->                  
			</form>
        </div>

<?php      
    } elseif ($do  == 'Insert') {       
	   
	    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

       	 	 echo "<h1 class = 'text-center'>Insert Item</h1>";
             echo "<div class ='container'>";
           
            //Get variables from form
            $avatarName = $_FILES['avatar']['name'];
			$avatarSize = $_FILES['avatar']['size'];
			$avatarTmp = $_FILES['avatar']['tmp_name'];
			$avatarType = $_FILES['avatar']['type'];

			//list of allowed file typed To Upload
			$avatarAllowedExtension = array('jpeg','jpg','png','gif');

			//get avatar extension
			$ava = explode('.', $avatarName);
			$avatarExtension = strtolower(end($ava));
       	 	 
			$name = $_POST['name'];
			$desc = $_POST['description'];
			$price = $_POST['price'];
			$country = $_POST['country'];
			$status = $_POST['status'];
			$member = $_POST['member'];
			$cat = $_POST['category'];
			$tags = $_POST['tags'];

			// validate the form
			$formErrors = array();

       	 	 if (empty($name)) {
       	 	 	$formErrors[] = 'name can\'t be <strong>Empty</strong>';
       	 	 }
       	 	  if (empty($desc)) {
       	 	 	$formErrors[] = 'description can\'t be <strong>Empty</strong>';
       	 	 }
       	 	  if (empty($price)) {
       	 	 	$formErrors[] = 'price can\'t be <strong>Empty</strong>';
       	 	 }
       	 	  if (empty($country)) {
       	 	 	$formErrors[] = 'country can\'t be <strong>Empty</strong>';
       	 	 }
       	 	 if ($status == 0) {
       	 	 	$formErrors[] = 'You Must choose the <strong>Status</strong>';
       	 	 }
       	 	  if ($member == 0) {
       	 	 	$formErrors[] = 'You Must choose the <strong>Member</strong>';
       	 	 }
       	 	  if ($cat == 0) {
       	 	 	$formErrors[] = 'You Must choose the <strong>Category</strong>';
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
       	 	 
       	 	 foreach ( $formErrors as $error) {
       	 	 	echo '<div class = "alert alert-danger">' . $error . '</div>' ;
       	 	 }

       	 	   // check if there's no error proceed the update operation              
               if(empty($formErrors)){		               	 
           		 //insert userinfo in database
           		 $avatar = rand(0,10000000000) . '_' . $avatarName;
				 move_uploaded_file($avatarTmp, "uploads\picture\\" . $avatar); 

	       	 	 $stmt = $con->prepare("insert into items (name,description, price, country_made, Status, Add_Date, Cat_ID, Member_ID, Tags, Approve, Avatar) 
	       	 	 	           VALUES (:Zname, :Zdesc, :Zprice, :Zcountry, :Zstatus, now(), :Zcat, :Zmember, :Ztags, :Zapp, :Zavatar )");
	       	 	 $stmt ->execute(array(
	       	 	 	    'Zname' => $name, 'Zdesc' => $desc, 
	       	 	 	    'Zprice' => $price,'Zcountry' => $country,
	       	 	 	    'Zstatus' => $status,'Zcat' => $cat,
	       	 	 	    'Zmember' => $member,'Ztags' => $tags,
	       	 	 	    'Zapp' => 1,'Zavatar' => $avatar));

	       	 	 // echo succes message
	       	 	 $theMsg = "<div class = 'alert alert-success'>" . $stmt->Rowcount() . ' Record Inserted.</div>';
                 redirectHome($theMsg,'back');
                }
                		       	 	 
       	}else{
            echo "<div class ='container'>";
            $theMsg = "<div class ='alert alert-danger'>Sorry you can't Browse this page directly.</div>";
       	    redirectHome($theMsg);
       	}		          
        echo "</div>";               

    } elseif ($do  == 'Edit') {			
		$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
      	
      	$stmt = $con->prepare("select * FROM items WHERE item_ID = ?");
	   	$stmt ->execute(array($itemid)); 
	   	$item = $stmt -> fetch();
	   	$count  = $stmt -> rowcount();
   	 
    	if($count > 0) { ?>
 
			<h1 class = "text-center">Edit Item</h1>          
	        <div class = "container">
	          <form class = "form-horizontal" action ='?do=Update' method="POST" enctype="multipart/form-data">
		         <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
	          	<!--Start name field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Name</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" 	name ="name" class ="form-control" required= "required" placeholder="Name of the Item" value="<?php echo $item['Name'] ?>" />
	          	 	  </div>
	          	 </div>            	
	            <!--End name field -->
	            <!--Start Description field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Description</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" 	name ="description" class ="form-control"  placeholder="Description of the Item" value="<?php echo $item['Description'] ?>" />
	          	 	  </div>
	          	 </div>            	
	            <!--End Description field -->
	            <!--Start Price field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Price</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" 	name ="price" class ="form-control"  placeholder="Price of the Item" value="<?php echo $item['Price'] ?>" />
	          	 	  </div>
	          	 </div>            	
	            <!--End Price field -->
	             <!--Start Country field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Country</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="country" name ="country" class ="form-control"  placeholder="Contry of Made" value="<?php echo $item['Country_Made'] ?>" />
	          	 	  </div>
	          	 </div>            	
	            <!--End Country field -->
	             <!--Start Status field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Status</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<select name="status">
	          	 	  		<option value="1" <?php if($item['Status'] == 1 ){echo 'selected';} ?> >New</option>
	          	 	  		<option value="2" <?php if($item['Status'] == 2 ){echo 'selected';} ?> >Like New</option>
	          	 	  		<option value="3" <?php if($item['Status'] == 3 ){echo 'selected';} ?> >Used</option>
	          	 	  		<option value="4" <?php if($item['Status'] == 4 ){echo 'selected';} ?> >very Old</option>
	          	 	  	</select>
	          	 	  </div>
	          	 </div>            	
	            <!--End Status field -->
	            <!--Start Members field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Member</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<select name="member">
	          	 	  		 <?php
	              	 	  		 $stmt = $con->prepare('SELECT * FROM users');
	              	 	  		 $stmt->execute();
	              	 	  		 $users = $stmt->fetchAll();
	              	 	  		 foreach ($users as $user) {
	              	 	  		 	echo '<option value="' . $user['UserID'] . '"';
	              	 	  		 	if($item['Member_Id'] == $user['UserID'] ){echo 'selected';}
	              	 	  		 	echo '>' . $user['Username'] .  '</option>';
	              	 	  		 }
	          	 	  		 ?>
	          	 	  	</select>
	          	 	  </div>
	          	 </div>            	
	           <!-- End Members field -->
	           <!--Start categories field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Category</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<select name="category">
	          	 	  		 <?php
	              	 	  		 $stmt2 = $con->prepare('SELECT * FROM categories');
	              	 	  		 $stmt2->execute();
	              	 	  		 $cats = $stmt2->fetchAll();
	              	 	  		 foreach ($cats as $cat) {
	              	 	  		 	echo '<option value="' . $cat['ID'] . '"';
	              	 	  		 	if($item['Cat_ID'] == $cat['ID'] ){echo 'selected';}
	              	 	  		 	echo '>' . $cat['Name'] .  '</option>';
	              	 	  		 }
	          	 	  		 ?>
	          	 	  	</select>
	          	 	  </div>
	          	 </div>            	
	           <!-- End categories field -->
	           <!--Start tags field -->
	          	 <div class="form-group form-group-lg" >
	          	 	<label class="col-sm-2 control-label">Tags</label>
	          	 	  <div class="col-sm-10 col-md-6">
	          	 	  	<input type="text" name ="tags" 
	          	 	  	       class ="form-control"  placeholder="Separate Tags With Comma (,)"
	          	 	  	       value="<?php echo $item['Tags'] ?>" />
	          	 	  </div>
	          	 </div>            	
	            <!--End tags field -->
	            <!--Start avatar field -->
	          	<div class="form-group form-group-lg">
	          	 	<label class="col-sm-2 control-label">Picture of Item</label>
	          	 	<div class="col-sm-10 col-md-6">
	          	 	  	<input type="file" name ="avatar" class ="form-control" required= "required"/>
	          	 	</div>
	          	</div>            	
	            <!--End avatar field --> 
	             <!--Start Submit field -->
	          	 <div class="form-group form-group-lg">
	          	 	  <div class="col-sm-offset-2 col-sm-10">
	          	 	  	<input type="submit" value ="Save Item" class ="btn btn-primary btn-lg " />
	          	 	  </div>
	          	 </div>            	
	            <!--End Submit field -->          
	        </form>

<?php
            $stmt = $con->prepare("SELECT 
   	                          comments.*, users.Username AS Member
   	                      FROM 
   	                          comments		           	                     
   	                      INNER join
   	                          users
   	                      ON
   	                          users.UserID = comments.user_id
   	                      WHERE
   	                          item_ID = ?");
            $stmt ->execute(array($itemid));
            $rows = $stmt->fetchAll();

       		if( ! empty($rows)){
        
?>
                <h1 class = "text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>    
	           	<div class = "table-responsive">
	           		<table class="main-table text-center table table-bordered">
	           			<tr>
	           				<td>Comment</td>
	           				<td>User Name</td>
	           				<td>Added Date</td>
	           				<td>Control</td>
	           			</tr>

                        <?php
                        foreach ($rows as $row) {
                          echo "<tr>";	
	                        	echo "<td>" . $row['comment'] . "</td>";
	                        	echo "<td>" . $row['Member'] . "</td>";
	                        	echo "<td>" . $row['comment_date'] . "</td>";
	                        	echo "<td>
	                        	       <a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class = 'fa fa-edit'></i>Edit</a>
		           					   <a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class = 'fa fa-close'></i>Delete</a>";
	                        	      
	                        	      if($row['status'] == 0){
	                        	        echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "' class='btn btn-info activate'><i class = 'fa fa-check'></i>Approve</a>";
	                        	      }
	                        	  echo "</td>";
                         echo "</tr>";		      
                        } 

                        ?>
	           			
	           		</table>
        	   </div> 
		<?php 	
			} ?>   
       		</div>

<?php 
	    }else{
			echo "<div class ='container'>";
		    	$theMsg = "<div class ='alert alert-danger'>theres No such ID.</div>";
			    redirectHome($theMsg);
	 	    echo "</div>";
	    }
   
    } elseif ( $do == 'Update'){
		echo "<h1 class = 'text-center'>Update Item</h1>";
        echo "<div class ='container'>";
        
       	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
             //Get variables from form
	  		$avatarName = $_FILES['avatar']['name'];
			$avatarSize = $_FILES['avatar']['size'];
			$avatarTmp = $_FILES['avatar']['tmp_name'];
			$avatarType = $_FILES['avatar']['type'];

			//list of allowed file typed To Upload
			$avatarAllowedExtension = array('jpeg','jpg','png','gif');

			//get avatar extension
			$ava = explode('.', $avatarName);
			$avatarExtension = strtolower(end($ava)); 

			$id = $_POST['itemid'];
			$name = $_POST['name'];
			$desc = $_POST['description'];
			$price = $_POST['price'];

			$country = $_POST['country'];
			$status = $_POST['status'];
			$cat = $_POST['category'];
			$member = $_POST['member'];
			$tags = $_POST['tags'];


			// validate the form
			$formErrors = array();

       	 	 if (empty($name)) {
       	 	 	$formErrors[] = 'name can\'t be <strong>Empty</strong>';
       	 	 }
       	 	  if (empty($desc)) {
       	 	 	$formErrors[] = 'description can\'t be <strong>Empty</strong>';
       	 	 }
       	 	  if (empty($price)) {
       	 	 	$formErrors[] = 'price can\'t be <strong>Empty</strong>';
       	 	 }
       	 	  if (empty($country)) {
       	 	 	$formErrors[] = 'country can\'t be <strong>Empty</strong>';
       	 	 }
       	 	 if ($status == 0) {
       	 	 	$formErrors[] = 'You Must choose the <strong>Status</strong>';
       	 	 }
       	 	  if ($member == 0) {
       	 	 	$formErrors[] = 'You Must choose the <strong>Member</strong>';
       	 	 }
       	 	  if ($cat == 0) {
       	 	 	$formErrors[] = 'You Must choose the <strong>Category</strong>';
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
       	 	 
       	 	 foreach ( $formErrors as $error) {
       	 	 	echo '<div class = "alert alert-danger">' . $error . '</div>' ;
       	 	 }

   	 	    // check if there's no error proceed the update operation		               
            if(empty($formErrors)){
           		$avatar = rand(0,10000000000) . '_' . $avatarName;
				move_uploaded_file($avatarTmp, "uploads\picture\\" . $avatar);
           		 
           		 //update the database with this info
	       	 	 $stmt = $con->prepare("UPDATE
	       	 	                               items 
	       	 	 	                    SET 
	       	 	 	                           Name = ?, Description = ?, Price = ?,
	       	 	                               Country_Made = ?, Status = ?, Cat_ID = ?, Member_Id = ?, Tags = ?, Avatar = ?
	       	 	                        WHERE 
	       	 	                               item_ID = ?");

	       	 	 $stmt ->execute(array($name,$desc,$price,$country,$status,$cat,$member,$tags,$avatar,$id));

	       	 	 // echo succes message
	       	 	 $theMsg = "<div class = 'alert alert-success'>" . $stmt->Rowcount() . ' Record Updated.</div>';                 
                 redirectHome($theMsg,'back');
            } 
       	 	 
       	 }else{             
             $theMsg = "<div class ='alert alert-danger'>Sorry you can't Browse this page directly.</div>";
       	     redirectHome($theMsg);
       	 }
          
          echo "</div>";
  
    } elseif ($do == 'Delete'){

		echo "<h1 class = 'text-center'>Delete Item</h1>";
        echo "<div class ='container'>";

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check =checkItem('item_ID','items',$itemid);
         
		// if there's such id show the form
		if($check > 0) { 
			$stmt = $con->prepare("Delete FROM items WHERE item_ID = :zid ");
			$stmt ->bindParam(":zid", $itemid); 
			$stmt ->execute(); 
			$theMsg = "<div class = 'alert alert-success'>" . $stmt->Rowcount() . ' Record Deleted.</div>';
			redirectHome($theMsg,'back');
		}else{
			$theMsg = "<div class = 'alert alert-danger'>This ID is not exist.</div>";
			redirectHome($theMsg);
		}
		echo "</div>";

    }elseif ($do == 'Approve'){
        
        echo "<h1 class = 'text-center'>Approve Item</h1>";
        echo "<div class ='container'>";

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $check =checkItem('item_ID','items',$itemid);
         
        // if there's such id show the form
		if($check > 0) { 
			$stmt = $con->prepare("UPDATE items SET Approve = 1  WHERE item_ID = ? ");
			$stmt ->execute(array($itemid)); 
			$theMsg = "<div class = 'alert alert-success'>" . $stmt->Rowcount() . ' Record Approved.</div>';
			redirectHome($theMsg,'back');
		}else{
			$theMsg = "<div class = 'alert alert-danger'>This ID is not exist.</div>";
			redirectHome($theMsg);
		}
        echo "</div>";    
    }

    include $tpl . "footer.php";

 }else{
	header('Location: index.php');
	exit();
 }
 ob_end_flush(); // Realise the Output
?>    