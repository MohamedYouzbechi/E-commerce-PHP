<?php 
	session_start();
	$pageTitle = 'Login';

	if(isset($_SESSION['user'])){
		header('Location: index.php'); // Redirect To Dashboard page
	}

	include 'init.php'; 

	//chek if user coming from HTTP POST Request
            
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['login'])){     

		   	$user = $_POST['username'];
		   	$pass = $_POST['password'];
		   	$hashedpass = sha1($pass);

		   	//check if the user exist in database
		   	$stmt = $con->prepare("select UserID, Username,password FROM users WHERE username = ? AND password = ? ");
		   	$stmt ->execute(array($user,$hashedpass)); 
		   	$get = $stmt->fetch();
		   	$count  = $stmt -> rowcount();
		   	 
		   	// if count > 0 this mean the database contain record about this username
	        if($count > 0) {
	          	$_SESSION['user'] = $user; //Register Session Name
	          	$_SESSION['uid'] = $get['UserID']; //Register Session ID
	          	header('Location: index.php'); // Redirect To Dashboard page
	          	exit();
	        }	

	    }else{
			$formErrors =array();

			$username = $_POST['username'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$email = $_POST['email'];

			if(isset($username)){	      	  	
	      	  	$filterdUser = filter_var($username,FILTER_SANITIZE_STRING);
	      	  	
	      	  	if(strlen($filterdUser) < 4){
	      	  		$formErrors[] = 'Username Must Be Larger Than 4 Characters';
	      	  	}
			}
			if(isset($password) && isset($password2)){
	      	  	 
                if(empty($password)){
	      	  		$formErrors[] = 'Sorry Password cant Be Empty.';
	      	  	}  

	      	  	if( sha1($password) !== sha1($password2)){
	      	  		$formErrors[] = 'Sorry Password Is Not Match';
	      	  	}
	      	  	
			}
	      	if(isset($email)){      	  	
	      	  	$filterdEmail = filter_var($email,FILTER_SANITIZE_EMAIL);
	      	  	
	      	  	if(filter_var($filterdEmail,FILTER_VALIDATE_EMAIL) != true ){
	      	  		$formErrors[] = 'This Email Is Not Valid.';
	      	  	}
	      	}

		  	// check if there's no error proceed the User Add		       
		    if(empty($formErrors)){
		       	// check if user exist in database
		       	$check = checkItem("Username","users",$username);

		       	if($check == 1 ) {
	      	  		$formErrors[] = 'Sorry This User Is Exist.';
		       	}else{
		       		//insert userinfo in database 
		       	 	$stmt = $con->prepare("insert into users (Username,Password, Email, Fullname, Avatar, RegStatus, Date ) VALUES (:Zuser, :Zpass, :Zmail,'', '', 0, now())");
		       	 	$stmt ->execute(array('Zuser' => $username, 'Zpass' => sha1($password), 'Zmail' => $email));

		       	 	// echo succes message
		       	 	$succesMsg = 'Congrats You Are Now Registred User.';
		        }
		    } 
	    }
    } 
?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class = "login">Login</span> | 
		<span data-class = "signup">SignUP</span>
    </h1>
	<form class = "login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method = "POST">
		<div class="input-container">
			<input class="form-control" type="text" name="username" autocomplete="off" placeholder="username" required>
		</div>
		<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="password">
		<input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
	</form>
	<form class = "signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method = "POST">
		<div class="input-container">
			<input pattern=".{4,}" title="Username Must Be Larger Than 4 Characters" class="form-control" type="text" name="username" autocomplete="off" placeholder="username" required>
		</div>
		<input minlength="4" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type your password" required>
		<input minlength="4" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type your password again" required>
		<input class="form-control" type="text" name="email" placeholder="type your email">
		<input class="btn btn-success btn-block" type="submit" name="signup" value="SignUP">
	</form>
	<div class="the-errors text-center">
		<?php 		
			if(! empty($formErrors)){		 	
				foreach ($formErrors as $error) {
					echo '<div class="msg error">' . $error . '</div>';
				}
			}
			if(isset($succesMsg)){
				echo '<div class = "msg success">' . $succesMsg . '</div>';
			}
		?>
	</div>
</div>

<?php include $tpl . 'footer.php'; ?>