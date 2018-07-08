<?php
session_start();
$uid = $_SESSION['userid'];
$target_dir = "resumes/";
$target_file = $target_dir . $uid . ".pdf";
$uploadOk = 1;
$fileType = $_FILES["file"]["type"];
if($fileType != "application/pdf") {
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded. ";
    echo "Please check the size and type of your file!";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// $cmd = '/home/jobhunter/mypython/bin/python /home/jobhunter/public_html/python/newParser.py '. $uid.;
$cmd = '/Users/ZeyuWu/anaconda2/bin/python /Users/ZeyuWu/GitHub/InternX/python/newParser.py '. $uid;
//$cmd = '/Users/mac/anaconda/bin/python /Users/mac/WebstormProjects/InternX/python/newParser.py '. $uid;
exec($cmd, $output, $retval);

$python = '/Users/mac/anaconda/bin/python ';  //$python = '/home/jobhunter/mypython/bin/python ';
$file = '/Users/mac/WebstormProjects/InternX/python/parser/distance.py '; //$file = '/home/jobhunter/public_html/python/parser/distance.py '
$cmd = $python. $file. $uid;

exec($cmd, $output, $retval);

/*echo $retval;
for( $i = 0; $i<sizeof($output); $i++ )
            echo $output[$i];*/



?>
