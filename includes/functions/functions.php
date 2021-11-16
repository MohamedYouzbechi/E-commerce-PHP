<?php

 /*global function*/
 function getAllFromGen($field, $table, $where=NULL, $and=NULL, $orderField, $ordering='DESC'){
 	global $con;	  
	   $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderField $ordering");	  
	   $getAll->execute();	  
	   $all = $getAll->fetchAll();	  
	   return $all;
 }

 /* Get All Records from any tabe Functions */
 function getAllFrom($tableName,$orderBy,$where = NULL){
    global $con;
       $sql = $where == NULL ? '' : 'WHERE ' . $where;
	   $getAll = $con->prepare("SELECT * FROM $tableName $sql ORDER BY $orderBy DESC");
	   $getAll->execute();
	   $all = $getAll->fetchAll();
	   return $all;
 }

 /* Get Records Functions */
 function getCat(){
    global $con;
	   $getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
	   $getCat->execute();
	   $cats = $getCat->fetchAll();
	   return $cats;
 }

/* Get Records Functions */
 function getItems($where,$value,$approve = NULL){
    global $con;
       /*$sql = $approve == NULL ? 'AND Approve = 1' : '';*/
	   $getItems = $con->prepare("SELECT * FROM items WHERE $where = ? ORDER BY item_ID ASC");
	   $getItems->execute(array($value));
	   $items = $getItems->fetchAll();
	   return $items;
 }

/* check if user is not activated */
function chekUserStatus($user){
	//check if the user exist in database
      global $con;
	   	$stmt = $con->prepare("select Username,Regstatus FROM users WHERE username = ? AND Regstatus = 0 ");
	   	$stmt ->execute(array($user)); 
	   	$status  = $stmt -> rowcount();
	   	return $status;
}

/*
** Title function That Echo The page title in case the page
** Has the variable $pagetitle and echo default title for other pages
*/

function getTitle(){	
	global $pageTitle;

	if(isset($pageTitle)) {	
		echo $pageTitle;	
	} else {	
		echo 'Default';	
	}
}

/* Home Redirect function */
function redirectHome($theMsg,$url = null,$seconds = 3){

	if($url === null){
		$url = 'index.php';
		$link = 'Homepage';
	}else {
	    if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
	      $url = $_SERVER['HTTP_REFERER'];
	      $link = 'Previous Page';
	    }else{
			$url = 'index.php';
	        $link = 'HomePage';
	    }	
	}

	// echo "<div class= 'alert alert-danger'>$errorMsg</div>";
    echo $theMsg;
    echo "<div class= 'alert alert-info'>You Will Be Redirected To $link After $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();	   
}

/* Check Item Function  */
 function checkItem($select,$from,$value){
   global $con;
   $stmt = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
   $stmt->execute(array($value));
   $count = $stmt->rowCount();
   return $count;
 }

/* Function to count Number of Items Rows  */
function countItems($item,$table){
   global $con;
   $stmt = $con->prepare("SELECT COUNT($item) FROM $table");
   $stmt->execute();
   return $stmt->fetchColumn();
}

/* Get latest Records Functions */
 function getLatest($select,$table,$order, $Limit = 5 ){
    global $con;
	   $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $Limit");
	   $stmt->execute();
	   $rows = $stmt->fetchAll();
	   return $rows;
 }