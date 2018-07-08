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
  	  $skillName = $_POST["skillName{$x}"];
  	  $skillsProficiency = $_POST["skillsProficiency{$x}"];
  	  
  	  $check_exist = mysql_fetch_array(mysql_query("SELECT EXISTS(SELECT 1 FROM `hasSkill` WHERE `skillNum`='$x' and `uid`='$uid')"))[0];
  	  if($check_exist){
  	    $check_success = mysql_query("UPDATE `hasSkill` SET `SkillName`='$skillName',`Proficiency`='$skillsProficiency' WHERE `skillNum`='$x' and `uid`='$uid' ");
  	    if (!$check_success){
  	      die("Update skill failed!");
  	    }
  	  } 
  	  else{
  	  $check_insertHasSkill = mysql_query("INSERT INTO `hasSkill`(`uid`, `SkillName`, `Proficiency`, `skillNum`) VALUES ('$uid','$skillName','$skillsProficiency','$x')");
  	    if (!$check_insertHasSkill){
  	      die("Update skill failed");
  	    }
  	  }
	} 
  	mysql_query("DELETE FROM `hasSkill` WHERE `uid`='$uid' and `skillNum`>'$size'");
    
    die("Update succeed!");
?>