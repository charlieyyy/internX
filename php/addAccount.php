<?php
  session_start();
  $uid=$_SESSION['userid'];
  
  $link = mysql_connect('webhost.engr.illinois.edu', 'jobhunter_whu14', 'Fuckuiuc12');
  if (!$link) {
    die('Could not connect: ' . mysql_error());
  }
  mysql_select_db('jobhunter_db');

  $password = $_POST['password1'];

  //update user db
  $check_query = mysql_query("UPDATE `user` SET `Password`='$password' WHERE `uid`='$uid'");
  if(!$check_query){
    die("Update fail!"); 
  }
    
  die("Update succeed!");

?>