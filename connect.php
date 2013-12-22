<?php 
include ('User.php');
include ('UserManager.php');
require_once('Toolbox.php');

  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('config/config_dev.php'); //dev
  } else if(strpos($_SERVER['HTTP_HOST'], 'innovativepictures')!==false) {
    require_once ('config/config_OVH.php');
  } else {
    require_once ('config/config.php'); //prod
  } 

// Get User ID
  $my_access_token=$facebook->getAccessToken();
  $user = $facebook -> getUser();
  //echo $my_access_token;
  /*
  * BDD
  */
  $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
  $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);

  $manager = new UserManager($bdd);
  $user_info = new User($bdd);
?>