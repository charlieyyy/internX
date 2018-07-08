<?php
    session_start();
    $servername = 'webhost.engr.illinois.edu';
    $username = 'jobhunter_whu14';
    $password = 'Fuckuiuc12';
    $dbname = 'jobhunter_db';
    $uid = $_SESSION['userid'];
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //if the user is not logged in, return TODO:
    if(empty($uid)){
       exit();
    }
    $sql = "SELECT pid FROM Favorite WHERE uid = '$uid'";
	$result = $conn->query($sql);	
    $favInfoArray = array();
    if($result -> num_rows > 0){//Note:In php, 'if' does not have its own scope 
        while($row = $result->fetch_assoc()){
            $favInfoArray[] = $row['pid'];
        }
	}
    $jdata = json_encode($favInfoArray);
    echo $jdata;
?>