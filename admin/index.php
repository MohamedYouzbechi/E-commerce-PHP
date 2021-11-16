<?php 
   session_start();
   $nonavbar = '';
   $pageTitle = 'Login';

   // print_r($_SESSION);
    if(isset($_SESSION['username'])){
    	header('Location: dashboard.php'); // Redirect To Dashboard page
    }
   include 'init.php';
   include $tpl . "header.php";
   
   // check if user coming from http post request
   if($_SERVER['REQUEST_METHOD'] == 'POST'){
	   	$username = $_POST['user'];
	   	$password = $_POST['pass'];
	   	$hashedpass = sha1($password);

	   	//check if the user exist in database
	   	$stmt = $con->prepare("select UserID, Username,password FROM users WHERE username = ? AND password = ? AND GroupID = 1 LIMIT 1");
	   	$stmt ->execute(array($username,$hashedpass)); 
	   	$row = $stmt -> fetch();
	   	$count  = $stmt -> rowcount();
	   	 
   	  // if count > 0 this mean the database contain record about this username
      if($count > 0) {
      	$_SESSION['username'] = $username; //Register Session Name
      	$_SESSION['ID'] = $row['UserID'] ; //Register Session ID
      	header('Location: dashboard.php'); // Redirect To Dashboard page
      	exit();
      }	
    } 
?>
    <form class = 'login' action="<?php echo $_SERVER['PHP_SELF'] ?>" method = "POST"> 
    	<h4 class="text-center">Admin Login</h4>
	    <input class ="form-control btn-lg" type="text" name = "user" placeholder="username" autocomplete="off" />
	    <input class ="form-control btn-lg" type="password" name ="pass" placeholder="Username" autocomplete="new-password /">
	    <input class ="btn btn-lg btn-primary btn-block" type="submit" value="login" />	
    </form>

<?php include $tpl . "footer.php"; ?>