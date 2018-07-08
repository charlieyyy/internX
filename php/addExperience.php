<?php
	session_start();
	$uid=$_SESSION['userid'];
	$size=$_POST['size'];
	$link = mysql_connect('webhost.engr.illinois.edu', 'jobhunter_whu14', 'Fuckuiuc12');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db('jobhunter_db');
        
       	for ($x=1; $x<=$size; $x++) {
    		$check_exist = mysql_fetch_array(mysql_query("SELECT EXISTS(SELECT 1 FROM `Experience` WHERE `uid`='$uid' and `uidExpNum`='$x')"))[0];
    		
    		$experienceType = $_POST["experienceType{$x}"];
   		$endDate=$_POST["endDate{$x}"];
   		$Description=$_POST["experienceDescription{$x}"];
   		$Title=$_POST["experienceTitle{$x}"];
   		$Location=$_POST["experienceLocation{$x}"];
   		$OrganizationName=$_POST["organizationName{$x}"];
   		$StartDate=$_POST["startDate{$x}"];
   		
    		if ($check_exist){
      		  $check_query = mysql_query("UPDATE `Experience` SET `Experience Type`='$experienceType',`End Date`='$endDate',`Description`='$Description',`Title`='$Title',`Location`='$Location',`Organization Name`='$OrganizationName',`Start Date`='$StartDate' WHERE `uidExpNum`='$x' and `uid`='$uid'");	
      	    	}
      	    	else{
      	    	   $check_query = mysql_query("INSERT INTO `Experience`(`uid`, `Experience Type`, `End Date`, `Description`, `Title`, `Location`, `Organization Name`, `Start Date`, `uidExpNum`) VALUES ('$uid','$experienceType','$endDate','$Description','$Title','$Location','$OrganizationName','$StartDate','$x')");
      	    	}
      	    	
      	    	if(!$check_query){
    		  die("Update failed!");
   		}    	
        }
        
        mysql_query("DELETE FROM `Experience` WHERE `uid`='$uid' and `uidExpNum`>'$size'");
        die("Update succeed!");
?>