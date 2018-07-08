<?php
  session_start();
  $uid=$_SESSION['userid'];
  $k = 3;
  $minSupport = 0.5;

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

  //$cmd = '/home/jobhunter/mypython/bin/python /home/jobhunter/public_html/python/knn.py '. $uid. " ". $k. " ". $minSupport;
  $cmd = '/Users/Aslkayn/anaconda2/bin/python /Users/Aslkayn/Development/Web/InternX/python/knn.py '. $uid. " ". $k. " ". $minSupport;
  exec($cmd, $output, $retval);
  //echo($output[0]);
  echo "<div class='row '>";

 
 if ($retval==0){
  	    for ($x = 0; $x <= sizeof($output); $x++) {
            $pid=(int)($output[$x]);
            $sql = "SELECT * FROM Position WHERE PositionID=$pid";
            $result = $conn->query($sql);
            if($row = $result->fetch_assoc()){
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
            };
                
           
        } 
     }



  echo "</div>";

  mysqli_close($conn);

?>
