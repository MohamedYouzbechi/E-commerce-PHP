<?php
  

  /*
  ********************************************************
  ** Template page
  ********************************************************
  */

    ob_start(); // Output Buffering Start

    session_start();
     
    $pageTitle  = '';

    if(isset($_SESSION['username'])){

    	include 'init.php';

    	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
         
        
         //start manage page
        
	        if ($do  == 'Manage'){  // manage page 
	          

	      } elseif ($do  == 'Add') { // add page 
	              

	       } elseif ($do  == 'Insert') { // insert page         
			                  

	       } elseif ($do  == 'Edit') { // edit page 
	           
	       
	       } elseif ( $do == 'Update'){
	       
	      
	       } elseif ($do == 'Delete'){ // Delete page
	            

	       }elseif ($do == 'Activate'){ // Activate page
	            
	           
	       }


        include $tpl . "footer.php";

    }else{

    	header('Location: index.php');
    	exit();

    }

    ob_end_flush(); // Realise the Output

?>    