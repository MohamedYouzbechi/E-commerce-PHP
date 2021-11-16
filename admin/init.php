<?php

   //Error Reporting
    ini_set('display_errors','On');
    error_reporting(E_ALL);

    include 'connect.php';
   
   $tpl ='includes/templates/'; //template directory
   $lang = 'includes/languages/'; // language directory
   $func = 'includes/functions/'; // funcions directory
   $css = 'layout/css/';  // css directory
   $js = 'layout/js/';  // js directory

   //include the Important Files
   include $func . 'functions.php';
   include $lang . 'english.php';
   include $tpl . 'header.php';

   //include navbar on all pags expect the one with $nonavbar variable 
   if(!isset($nonavbar)){include $tpl . 'navbar.php';}

   

