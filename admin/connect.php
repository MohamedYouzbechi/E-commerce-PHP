<?php

$dsn = 'mysql:host=localhost;dbname=shop';
$user = 'root';
$pass = '';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'set NAMES utf8',

);

try {
	$con = new PDO($dsn,$user,$pass, $option);
	$con -> setattribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	// echo 'you are connected welcome to database';	 
}
catch(PDOException $e) {
	echo 'Failed To connect' . $e->getMessage();
}