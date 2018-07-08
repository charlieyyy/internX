<?php
    session_start();
?>
<html>
<head>

</head>
<body>
<?php

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

    $sql = "SELECT COUNT(*) AS total FROM Position,Favorite WHERE PositionID=pid AND uid=$uid";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo '<p>Number of Jobs: '.$row['total']."</p>";

	$sql = "SELECT Position.* FROM Position, Favorite WHERE PositionID=pid AND uid=$uid";
	$result = $conn->query($sql);


    echo "<div class='row '>";

    if($result -> num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo    "<div class='col-sm-6 col-md-4'>";
            echo        "<div class='thumbnail fadein'>";
            echo        "<div ng-init=\"favState".$row['PositionID']."='N'\"><span compile=\"favState".$row['PositionID']."\"></span><input type=\"image\" src=\"/img/heart.png\" ng-click=\"fav(".$row['PositionID'].")\"></div>"; 
            echo            "<div class='des'>";
            echo            "<b>Title: </b> " . $row['Title'] . "<br>";
            echo            "<b>Deadline: </b> " . $row['Deadline'] . "<br>";
            echo            "<b>Company: </b>" . $row['Company'] . "<br>";
            echo            "<b>Industry: </b>" . $row['Industry'] . "<br>";
            echo            "<b>JobType: </b> " . $row['JobType'] . "<br>";
            echo            "<b>URL: </b> <a href = '" . $row['URL'] . "' target = '_blank'>Click</a><br>";
            echo            "<b>Location: </b> " . $row['Location'] . "<br>";
            echo            "<b>Description: </b> " . $row['Description'] . "<br>";
            echo            "</div>";
            echo        "</div>";
            echo    "</div>";
        }
    }

    echo "</div>";




    mysqli_close($conn);
?>

</body>
</html>
