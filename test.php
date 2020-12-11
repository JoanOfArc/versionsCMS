<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>
<?php include_once "admin/functions.php"; ?> 

<?php


echo loggedInUserId();

if (userLikedPost(30)) {

  echo " USER LIKED IT";

} else {
      echo " User did not like it";
  }



  
  

?>