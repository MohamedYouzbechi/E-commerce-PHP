<?php
 ob_start();
 session_start(); 
 $pageTitle  = 'Members';

 if(isset($_SESSION['user'])){
	include 'init.php';
	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
     
    if ($do  == 'Edit') {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
      	$stmt = $con->prepare("select * FROM users WHERE UserID = ? LIMIT 1");
	   	$stmt ->execute(array($userid)); 
	   	$row = $stmt -> fetch();
	   	$count  = $stmt -> rowcount();
	   	 
        if($stmt -> rowcount() > 0) { ?>
	        <h1 class = "text-center">Edit Members</h1>  
            <div class = "container">
	            <form class = "form-horizontal main-form" action ='?do=Update' method="POST" enctype="multipart/form-data">
	              	<input type="hidden" name="userid" value="<?php echo $userid ?>">
	              	<!--Start username field -->
	              	<div class="form-group form-group-lg" >
	              	 	<label class="col-sm-2 control-label">Username</label>
	              	 	<div class="col-sm-10 col-md-6">
	              	 	  	<input type="text" 	name ="username" value = "<?php echo $row['Username']; ?>" class ="form-control" autocomplete="off" required= "required" />
	              	 	</div>
	              	</div>            	
	                <!--End username field -->
	                <!--Start password field -->
	              	<div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Password</label>
	              	 	<div class="col-sm-10 col-md-6">
	              	 	  	<input type="hidden" 	name ="oldpassword" value = "<?php echo $row['Password']; ?>" />
	              	 	  	<input type="password" 	name ="newpassword"  class ="form-control" autocomplete="new password" placeholder="Leave Blank If You Don't Want To Change" />
	              	 	</div>
	              	</div>            	
	                <!--End password field -->
	                <!--Start Email field -->
	              	<div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Email</label>
	              	 	<div class="col-sm-10 col-md-6">
	              	 	  	<input type="email" name ="email" value = "<?php echo $row['Email']; ?>"  class ="form-control" required= "required"/>
	              	 	</div>
	              	</div>            	
	                <!--End Email field -->
	                <!--Start username field -->
	              	<div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">Full Name</label>
	              	 	<div class="col-sm-10 col-md-6">
	              	 	  	<input type="text" 	name ="full" value = "<?php echo $row['Fullname']; ?>"  class ="form-control" required= "required" />
	              	 	</div>
	              	</div>            	
	                <!--End username field -->
	                <!--Start avatar field -->
	              	<div class="form-group form-group-lg">
	              	 	<label class="col-sm-2 control-label">User Avatar</label>
	              	 	<div class="col-sm-10 col-md-6">
	              	 	  	<input type="file" name ="avatar" class ="form-control" required= "required"/>
	              	 	</div>
	              	</div>            	
	                <!--End avatar field --> 
	                <!--Start Submit field -->
	              	<div class="form-group form-group-lg">
	              	 	<div class="col-sm-offset-2 col-sm-10">
	              	 	  	<input type="submit" value ="save" class ="btn btn-primary btn-lg " />
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
	}elseif( $do == 'Update'){
	   
	   	echo "<h1 class = 'text-center'>Update Member</h1>";
	    echo "<div class ='container'>";
	    
	   	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

	   	 	$avatarName = $_FILES['avatar']['name'];
			$avatarSize = $_FILES['avatar']['size'];
			$avatarTmp = $_FILES['avatar']['tmp_name'];
			$avatarType = $_FILES['avatar']['type'];

			//list of allowed file typed To Upload
			$avatarAllowedExtension = array('jpeg','jpg','png','gif');

			//get avatar extension
			$ava = explode('.', $avatarName);
			$avatarExtension = strtolower(end($ava));

			//Get variables from form
			$id = $_POST['userid'];
			$user = $_POST['username'];
			$email = $_POST['email'];
			$name = $_POST['full'];
			/*$avatar = $_POST['avatar'];*/

			// password Trick
			$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']) ;

			// validate the form
			$formErrors = array();

			if (strlen($user) < 4 ) {
				$formErrors[] = 'Username cant be less than <strong> 4 caracters</strong>';
			}
			if (strlen($user) > 20 ) {
				$formErrors[] = 'Username cant be More than <strong> 20 caracters</strong>';
			}
			if (empty($user)) {
				$formErrors[] = 'Username cant be <strong>Empty</strong>';
			}
			if (empty($name)) {
				$formErrors[] = 'Full name cant be <strong>Empty</strong>';
			}
			if (empty($email)) {
				$formErrors[] = 'Email cant be <strong>Empty</strong>';
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
				move_uploaded_file($avatarTmp, "admin\uploads\avatars\\" . $avatar);

				$stmt2=$con->prepare('SELECT * FROM users WHERE Username = ? AND UserID != ?');
				$stmt2->execute(array($user,$id));
				$count = $stmt2->rowCount();

				if($count == 1){
					$theMsg = '<div class= "alert alert-danger">This user is Exist.</div>';
					redirectHome($theMsg,'back');
				}else{
					//update the database with this info
					$stmt = $con->prepare("Update users set Username= ?, Email = ?, Fullname = ?, Password = ?, Avatar = ? WHERE UserID = ?");
					$stmt ->execute(array($user,$email,$name,$pass,$avatar,$id));
					$_SESSION['user'] = $user;

					// echo succes message
					$theMsg = "<div class = 'alert alert-success'>" . $stmt->Rowcount() . ' Record Updated.</div>';
					redirectHome($theMsg,'back');
				}   
			}

		}else{	         
			$theMsg = "<div class ='alert alert-danger'>Sorry you can't Browse this page directly.</div>";
			redirectHome($theMsg);
		}        
	    echo "</div>";

	}
    include $tpl . "footer.php";

 }else{
	header('Location: index.php');
	exit();
 }
  ob_end_flush();