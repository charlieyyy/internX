<?php
	$link = mysql_connect('webhost.engr.illinois.edu', 'jobhunter_whu14', 'Fuckuiuc12');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db('jobhunter_db');
	
	$username = $_POST['username'];
	$password = $_POST['password1'];
        $password2 = $_POST['password2'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];

	$check_query = mysql_query("select uid from user where username='$username'");
        
	if($result = mysql_fetch_array($check_query)){
	    //email already exists
	    die('Email already exists');
	} 
	
        if ($password!=$password2){
              	//password validation
              	die('Passwords do not match');
        }
        if  (strlen($password)<6){
        	//password too short
        	die('Passwords is too short');
        }
                	
	//assign uid
	$check_query = mysql_query("SELECT MAX(uid) AS 'uid' FROM user");
	$result = mysql_fetch_array($check_query)['uid'];
	$result = $result+1;
	                
	//insert new tuple
	$check_query = mysql_query("INSERT INTO `user`(`uid`, `Username`, `Password`, `First Name`, `Last Name`) VALUES('$result','$username','$password','$firstName','$lastName')");
	
	//check if succeed
	if ($check_query){
		print_r('Sign up succeed!');
	}
	else{
		die('Sign up failed!');
	}
	
?>