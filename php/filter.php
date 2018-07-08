<html>
<head>

</head>
<body>
<?php
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


    $data = json_decode(file_get_contents('php://input'), true);
	$jobtype = $data["JobType"];
	$industry = $data["Industry"];
	$location = $data["Location"];
	$time = $data["Time"];



    $sql = "SELECT COUNT(*) AS total FROM Position WHERE (JobType='$jobtype' OR '$jobtype'='all') AND (Industry='$industry' OR '$industry'='all') AND (Location='$location' OR '$location'='all') AND (Time='$time' OR '$time'='all')";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $totalNum = $row['total'];

    echo '<p>Number of Jobs Found: '.$totalNum."</p>";

    $sql = "SELECT * FROM Position WHERE (JobType='$jobtype' OR '$jobtype'='all') AND (Industry='$industry' OR '$industry'='all') AND (Location='$location' OR '$location'='all') AND (Time='$time' OR '$time'='all')";
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
