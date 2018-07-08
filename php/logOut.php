<?php
  // Start the session
  session_start();
  if ($_POST['order']=='logOut')
    session_unset(); 
  
  print_r('Log out succeed!');  
?>