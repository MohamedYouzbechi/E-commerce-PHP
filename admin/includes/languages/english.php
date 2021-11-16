<?php

 function  lang( $phrase ) {
 
   static $lang = array(
    
       //Navbar Links
     'HOME_ADMIN' 	=> 'Admin Aria',
     'CATEGORIES'	  => 'Gategories',
     'ITEMS'		    => 'Items',
     'MEMBERS' 		  => 'Members',
     'COMMENTS'     => 'Comments',
     'STATISTICS' 	=> 'Statistics',
     'LOGS'			    => 'Logs',
     '' => '',
     '' => '',
     '' => '',
     '' => ''
   );
     return $lang[$phrase];
 }

