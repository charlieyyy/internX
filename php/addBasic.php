<?php
  session_start();
  $uid=$_SESSION['userid'];

  $link = mysql_connect('webhost.engr.illinois.edu', 'jobhunter_whu14', 'Fuckuiuc12');
  if (!$link) {
    die('Could not connect: ' . mysql_error());
  }
  mysql_select_db('jobhunter_db');

  //update user db variables
  $attributes=array();						$attributesName=array();
  $attributes[0]=$_POST['phoneNumber'];  			$attributesName[0]='Phone Number';
  $attributes[1]=$_POST['school'];	   			$attributesName[1]='School';
  $attributes[2]=$_POST['major'];		   		$attributesName[2]='Major';
  $attributes[3]=$_POST['minor'];		   		$attributesName[3]='Minor';
  $attributes[4]=$_POST['secondMajor'];  			$attributesName[4]='Second Major';
  $attributes[5]=$_POST['gpa'];		   			$attributesName[5]='GPA';
  $attributes[6]=$_POST['workAuthorization']; 			$attributesName[6]='Work Authorization';
  $attributes[7]=$_POST['degree'];		   	$attributesName[7]='Degree Type';
  $attributes[8]=$_POST['address'];	        $attributesName[8]='Address';
  if ($_Post['school']=='other'){
    $$attributes[1]=$_POST['otherSchool'];
  }

  //update user db
  for ($x=0; $x<=8; $x++) {
    if (!empty($attributes[$x])){
      $check_query = mysql_query("UPDATE `user` SET `$attributesName[$x]`='$attributes[$x]' WHERE `uid`='$uid'");
      if(!$check_query){
    	die("Update failed!");
      }
    }
  }
  die("Update succeeded!");

?>
