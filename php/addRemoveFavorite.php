<?php
    session_start();
    $uid=$_SESSION['userid'];
    $servername = 'webhost.engr.illinois.edu';
    $username = 'jobhunter_whu14';
    $password = 'Fuckuiuc12';
    $dbname = 'jobhunter_db';
    $posID = json_decode(file_get_contents("php://input"))->posID;
    
    if (empty($uid)){
        echo "N<script>alert('Please sign in first!')</script>";
        exit();
    }
    $conn = mysql_connect($servername, $username, $password);
    mysql_select_db($dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $check_exist = mysql_fetch_array(mysql_query("SELECT EXISTS(SELECT 1 FROM `Favorite` WHERE `uid`='$uid' and `pid`='$posID')"))[0];
    if ($check_exist){
        $query = mysql_query("DELETE FROM Favorite WHERE uid='$uid' and pid='$posID'");
        echo "N";
    }else{
        $query = mysql_query("INSERT INTO Favorite Values($uid,$posID)");
        echo "Y";
    }
    
    
    
?>