<?php
	session_start();
	$link = mysql_connect('webhost.engr.illinois.edu', 'jobhunter_whu14', 'Fuckuiuc12');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db('jobhunter_db');
        
	//Log in
	$username = $_POST['username'];
	//$username = htmlspecialchars($_POST['username']);
	$password = $_POST['password'];
	//$password = MD5($_POST['password']);

        
	//check password and username
	$check_query = mysql_query("SELECT * FROM `user` WHERE `Username`='$username' and `Password`='$password' LIMIT 1");
	$result = mysql_fetch_array($check_query);
	if(!$result ){
	    die('Username or password is wrong!');

	} else {
	   //Log in success
	    $_SESSION['username'] = $username;
	    $_SESSION['userid'] = $result['uid'];
	    
	    $_SESSION['lastName'] = $result['Last Name'];
	    $_SESSION['firstName'] = $result['First Name'];
	    
	    echo 'Log in succeed!', $_SESSION['firstName'] ;
	}
?>
