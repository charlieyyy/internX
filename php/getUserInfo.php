<?php
    session_start();
    $servername = 'webhost.engr.illinois.edu';
    $username = 'jobhunter_whu14';
    $password = 'Fuckuiuc12';
    $dbname = 'jobhunter_db';
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $uid = $_SESSION['userid'];
    $sql = "SELECT * FROM user WHERE uid = '$uid'";
	$result = $conn->query($sql);	
    if($result -> num_rows > 0){
	    $basic = $result->fetch_assoc();
	}
    $sql = "SELECT * FROM Experience WHERE uid = '$uid'";
	$result = $conn->query($sql);	
    $expArray = array();
    if($result -> num_rows > 0){
	    while($row = $result->fetch_assoc()){
            $expArray[] = $row;
        }
	}

     $sql = "SELECT * FROM hasSkill WHERE uid = '$uid'";
	$result = $conn->query($sql);	
    $skillArray = array();
    if($result -> num_rows > 0){
	    while($row = $result->fetch_assoc()){
            $skillArray[] = $row;
        }
	}
    //the data is not converted very well into Json structure. Needed to be improved!
    $jdata = json_encode(array(array("basicInfo" => $basic), array("experience" => $expArray), array("skill" => $skillArray))); 
    echo $jdata;
?>