<?php
 ob_start(); // Output Buffering Start
 session_start();
 $pageTitle  = '';

 if(isset($_SESSION['username'])){
	include 'init.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';        
    
    if ($do  == 'Manage'){

    	$sort ='ASC';
    	$sort_array = array('ASC','DESC');

    	if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){
    		$sort = $_GET['sort'];
    	}
          
		$stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
		$stmt2->execute();
		$cats = $stmt2->fetchAll(); ?>

        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
         	<div class="panel panel-default">
         		<div class="panel-heading">
					<i class="fa fa-edit"></i>Manage Categories
					<div class="option pull-right">
						<i class="fa fa-sort"></i> Ordering:[ 
						<a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="?sort=ASC">ASC</a>
						<a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="?sort=DESC">DESC</a> ]
						<i class="fa fa-eye"></i> View:[ 
						<span class="active" data-view = "full">Full</span>
						<span data-view = "classic">classic</span> ]
					</div>
         	    </div>
         		<div class="panel-body">
         			<?php
         			  foreach ($cats as $cat) {
         			  	echo '<div class="cat">';
         			  		echo '<div class="hidden-buttons">';
         			  	    	echo '<a href ="categories.php?do=Edit&catid=' . $cat['ID'] .'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i>Edit</a> ';
         			  	     	echo '<a href ="categories.php?do=Delete&catid=' . $cat['ID'] .'" class="confirm btn btn-xs btn-danger"><i class="fa fa-close"></i>Delete</a> ';
         			  		echo '</div>';

             			  	echo "<h3>" . $cat['Name'] . '</h3>';
             			    echo "<div class='full-view' >";
	             			  	echo "<p>"; if($cat['Description'] == ''){echo 'This categorie has no discription';}else{echo $cat['Description'];}   echo '</p>';
	             			    if($cat['Visibility'] == 1){echo '<span class = "visibility"><i class="fa fa-eye"></i>Hidden</span>';} ;
	             			  	if($cat['Allow_Comment'] == 1){echo '<span class = "commenting"><i class="fa fa-close"></i>Comment Disable</span>';} ;
	             			  	if($cat['Allow_Ads'] == 1){echo '<span class = "advertises"><i class="fa fa-close"></i>Ads Disable</span>';} ;	
         			        echo '</div>';

	         			    //Get child Categories 
	         			    $childCats = getAllFrom("*","categories","where parent = {$cat['ID']}","","ID","ASC");
				         if(! empty($childCats)){ 
				            echo '<h4 class="child-head">Child Categories</h4>';
				            echo '<ul class="list-unstyled child-cats">';
					            foreach ($childCats as $c) {
					              echo  '<li  class= "child-link">
					              			<a href ="categories.php?do=Edit&catid=' . $c['ID'] .'">' . $c['Name'] . '</a>
         			  	                    <a href ="categories.php?do=Delete&catid=' . $c['ID'] .'" class="show-delete confirm">Delete</a>
					              		</li>'; 
					            }
				            echo '</ul>';
				         }
     			   		echo '</div>';					        
     			   		echo '<hr>';
         			  }
         			?>
         		</div>
         	</div>
         	<a class="add-categorie btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>Add Category</a>
         </div>

   <?php 
    } elseif ($do  == 'Add') { ?>
            
        <h1 class = "text-center">Add New Category</h1>		           
        <div class = "container">
            <form class = "form-horizontal" action ='?do=Insert' method="POST">
              	<!--Start name field -->
              	<div class="form-group form-group-lg" >
              	 	<label class="col-sm-2 control-label">Name</label>
              	 	<div class="col-sm-10 col-md-6">
              	 	  	<input type="text" 	name ="name" class ="form-control" autocomplete="off" required= "required" placeholder="Name of the Category"/>
              	 	</div>
              	</div>            	
                <!--End name field -->
                <!--Start description field -->
              	<div class="form-group form-group-lg">
              	 	<label class="col-sm-2 control-label">Description</label>
              	 	<div class="col-sm-10 col-md-6">
              	 	  	<input type="text" 	name ="description"  class ="form-control"  placeholder="Describe the category" />
              	 	</div>
              	</div>            	
                <!--End description field -->
                <!--Start ordering field -->
              	<div class="form-group form-group-lg">
              	 	<label class="col-sm-2 control-label">Ordering</label>
              	 	<div class="col-sm-10 col-md-6">
              	 	  	<input type="text" name ="ordering"  class ="form-control"  placeholder="Number to arrange the gategories"/>
              	 	</div>
              	</div>            	
                <!--End ordering field -->
                <!--Start category type -->
              	<div class="form-group form-group-lg">
              	 	<label class="col-sm-2 control-label">Parent?</label>
              	 	<div class="col-sm-10 col-md-6">
              	 	  	<select name="parent">
              	 	  		<option value="0">none</option>
              	 	  		<?php
              	 	  		 $allCats = getAllFrom('*','categories','where parent = 0','','ID','ASC');
              	 	  		 foreach ($allCats as $cat) {
              	 	  		 	echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
              	 	  		 }
              	 	  		?>
              	 	  	</select>
              	 	</div>
              	</div>            	
                <!--End category type -->
                <!--Start visibility field -->
              	<div class="form-group form-group-lg">
              	 	<label class="col-sm-2 control-label">Visible</label>
              	 	<div class="col-sm-10 col-md-6">
              	 	  	<div>
              	 	  		<input id="vis-yes" type="radio" name="visibility" value="0" checked>
              	 	  		<label for="vis-yes">Yes</label>
              	 	  	</div>
              	 	  	<div>
              	 	  		<input id="vis-no" type="radio" name="visibility" value="1">
              	 	  		<label for="vis-no">No</label>
              	 	  	</div>
              	 	</div>
              	</div>            	
                <!--End visibility field -->
                <!--Start commenting field -->
              	<div class="form-group form-group-lg">
              	 	<label class="col-sm-2 control-label">Allow Commenting</label>
              	 	<div class="col-sm-10 col-md-6">
              	 	  	<div>
              	 	  		<input id="com-yes" type="radio" name="commenting" value="0" checked>
              	 	  		<label for="com-yes">Yes</label>
              	 	  	</div>
              	 	  	<div>
              	 	  		<input id="com-no" type="radio" name="commenting" value="1">
              	 	  		<label for="com-no">No</label>
              	 	  	</div>
              	 	</div>
              	</div>            	
                <!--End commenting field --> 
                <!--Start Ads field -->
              	<div class="form-group form-group-lg">
              	 	<label class="col-sm-2 control-label">Allow Ads</label>
              	 	<div class="col-sm-10 col-md-6">
              	 	  	<div>
              	 	  		<input id="ads-yes" type="radio" name="ads" value="0" checked>
              	 	  		<label for="ads-yes">Yes</label>
              	 	  	</div>
              	 	  	<div>
              	 	  		<input id="ads-no" type="radio" name="ads" value="1">
              	 	  		<label for="ads-no">No</label>
              	 	  	</div>
              	 	</div>
              	</div>            	
                <!--End Ads field -->   
                <!--Start Submit field -->
              	<div class="form-group form-group-lg">
              	 	<div class="col-sm-offset-2 col-sm-10">
              	 	  	<input type="submit" 	value ="Add Category" class ="btn btn-primary btn-lg " />
              	 	</div>
              	</div>            	
                <!--End Submit field -->      
                 
            </form>
        </div>

  <?php
    } elseif ($do  == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo "<h1 class = 'text-center'>Insert Category</h1>";
			echo "<div class ='container'>";

			//Get variables from form	       	 	 
			$name = $_POST['name'];
			$desc = $_POST['description'];
			$parent = $_POST['parent'];
			$order = $_POST['ordering'];
			$visible = $_POST['visibility'];
			$comment = $_POST['commenting'];
			$ads = $_POST['ads'];

			$check = checkItem("name","categories",$name);

			if($check == 1 ) {
				$theMsg = "<div class = 'alert alert-danger'>Sorry this category is exist.</div>";
				redirectHome($theMsg,'back');
			}else{
				//insert categories info in database 
				$stmt = $con->prepare("insert into categories (name,description, parent, ordering, visibility, Allow_comment, Allow_ads ) VALUES (:Zname, :Zdesc, :Zparent, :Zorder, :Zvisible, :Zcomment , :Zads)");
				$stmt ->execute(array(
				'Zname' 	=> $name, 
				'Zdesc' 	=> $desc, 
				'Zparent' 	=> $parent,
				'Zorder' 	=> $order, 
				'Zvisible'	=> $visible,
				'Zcomment'  => $comment, 
				'Zads' 		=> $ads
				));

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
           
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
      	$stmt = $con->prepare("select * FROM categories WHERE ID = ?");
	   	$stmt ->execute(array($catid)); 
	   	$cat = $stmt -> fetch();
	   	$count  = $stmt -> rowcount();
	   	 
        if($count > 0) { ?>

            <h1 class = "text-center">Edit Category</h1>		           
            <div class = "container">
                <form class = "form-horizontal" action ='?do=Update' method="POST">
	              	<input type="hidden" name="catid" value="<?php echo $catid ?>">
	              	<!--Start name field -->
	              	 <div class="form-group form-group-lg" >
	              	 	<label class="col-sm-2 control-label">Name</label>
	              	 	  <div class="col-sm-10 col-md-6">
	              	 	  	<input type="text" 	name ="name" class ="form-control" required= "required" placeholder="Name of the Category" value="<?php echo $cat['Name'] ?>" />
	              	 	  </div>
	              	 </div>            	
	                <!--End name field -->
	                <!--Start description field -->
	              	 <div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Description</label>
	              	 	  <div class="col-sm-10 col-md-6">
	              	 	  	<input type="text" 	name ="description"  class ="form-control"  placeholder="Describe the category" value="<?php echo $cat['Description'] ?>"/>
	              	 	  </div>
	              	 </div>            	
	                <!--End description field -->
	                <!--Start ordering field -->
	              	<div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Ordering</label>
	              	 	<div class="col-sm-10 col-md-6">
	              	 	  	<input type="text" name ="ordering"  class ="form-control"  placeholder="Number to arrange the categories" value="<?php echo $cat['Ordering'] ?>"/>
	              	 	</div>
	              	</div>            	
	                <!--End ordering field -->
	                <!--Start category type -->
	              	<div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Parent?</label>
	              	 	<div class="col-sm-10 col-md-6">
	              	 	  	<select name="parent">
	              	 	  		<option value="0">none</option>
	              	 	  		<?php
	              	 	  		 $allCats = getAllFrom('*','categories','where parent = 0','','ID','ASC');
	              	 	  		 foreach ($allCats as $c) {
	              	 	  		 	echo '<option value="' . $c['ID'] . '"';
	              	 	  		 	if($cat['Parent'] == $c['ID']){ echo ' selected';}
	              	 	  		 	echo '>' . $c['Name'] . '</option>';
	              	 	  		 }
	              	 	  		?>
	              	 	  	</select>
	              	 	</div>
	              	</div>            	
	                <!--End category type -->
	                <!--Start visibility field -->
	              	 <div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Visible</label>
	              	 	  <div class="col-sm-10 col-md-6">
	              	 	  	<div>
	              	 	  		<input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0){echo 'checked'; } ?> />
	              	 	  		<label for="vis-yes">Yes</label>
	              	 	  	</div>
	              	 	  	<div>
	              	 	  		<input id="vis-no" type="radio" name="visibility" value="1"  <?php if($cat['Visibility'] == 1){echo 'checked'; } ?> />
	              	 	  		<label for="vis-no">No</label>
	              	 	  	</div>
	              	 	  </div>
	              	 </div>            	
	                <!--End visibility field -->
	                <!--Start commenting field -->
	              	 <div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Allow Commenting</label>
	              	 	  <div class="col-sm-10 col-md-6">
	              	 	  	<div>
	              	 	  		<input id="com-yes" type="radio" name="commenting" value="0"  <?php if($cat['Allow_Comment'] == 0){echo 'checked'; } ?> />
	              	 	  		<label for="com-yes">Yes</label>
	              	 	  	</div>
	              	 	  	<div>
	              	 	  		<input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1){echo 'checked'; } ?> />
	              	 	  		<label for="com-no">No</label>
	              	 	  	</div>
	              	 	  </div>
	              	 </div>            	
	                <!--End commenting field --> 
	                <!--Start Ads field -->
	              	 <div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Allow Ads</label>
	              	 	  <div class="col-sm-10 col-md-6">
	              	 	  	<div>
	              	 	  		<input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0){echo 'checked'; } ?> />
	              	 	  		<label for="ads-yes">Yes</label>
	              	 	  	</div>
	              	 	  	<div>
	              	 	  		<input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1){echo 'checked'; } ?> />
	              	 	  		<label for="ads-no">No</label>
	              	 	  	</div>
	              	 	  </div>
	              	 </div>            	
	                <!--End Ads field -->   
	                <!--Start Submit field -->
	              	<div class="form-group form-group-lg">
	              	 	<div class="col-sm-offset-2 col-sm-10">
	              	 	  	<input type="submit" value ="Edit Category" class ="btn btn-primary btn-lg " />
	              	 	</div>
	              	</div>            	
	                <!--End Submit field -->      		                 
	            </form>
            </div>
      <?php 
		}else{
			echo "<div class ='container'>";
			$theMsg = "<div class ='alert alert-danger'>theres No such ID.</div>";
			redirectHome($theMsg);
			echo "</div>";
		}
       
    } elseif ( $do == 'Update'){
         
     	echo "<h1 class = 'text-center'>Update Category</h1>";
        echo "<div class ='container'>";
	        
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			//Get variables from form
			$id = $_POST['catid'];
			$name = $_POST['name'];
			$desc = $_POST['description'];
			$order = $_POST['ordering'];
			$parent = $_POST['parent'];
			$visible = $_POST['visibility'];
			$comment = $_POST['commenting'];
			$ads = $_POST['ads'];

			//update the database with this info
			$stmt = $con->prepare("Update categories set 
					       name= ?, description = ?, ordering = ?, parent = ?, visibility = ?, 
					       Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?");
			$stmt ->execute(array($name,$desc,$order,$parent,$visible,$comment,$ads,$id));

			// echo succes message
			$theMsg = "<div class = 'alert alert-success'>" . $stmt->Rowcount() . ' Record Updated.</div>';
			redirectHome($theMsg,'back');		   
		}else{
			$theMsg = "<div class ='alert alert-danger'>Sorry you can't Browse this page directly.</div>";
			redirectHome($theMsg);
		}

		echo "</div>";
      
    } elseif ($do == 'Delete'){
            
        echo "<h1 class = 'text-center'>Delete Category</h1>";
        echo "<div class ='container'>";
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $check =checkItem('id','categories',$catid);
         
		// if there's such id show the form
		if($check > 0) { 
			$stmt = $con->prepare("Delete FROM categories WHERE ID = :zid ");
			$stmt ->bindParam(":zid", $catid); 
			$stmt ->execute(); 
			$theMsg = "<div class = 'alert alert-success'>" . $stmt->Rowcount() . ' Record Deleted.</div>';
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