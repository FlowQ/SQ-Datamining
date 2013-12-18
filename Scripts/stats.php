<?php
  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('../config/config_dev.php'); //dev
  } else {
    require_once ('../config/config.php'); //prod
  } 
  //bdd
  $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
  $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);

  function stats($bdd) {
    $insert = $bdd->prepare('INSERT INTO Stats (Users_Count, Friends_Count, Friendships_Count, Male_Count, Female_Count) VALUES (:users, :friends, :users_friends, :male, :female)');
    $usersSQL = $bdd->prepare('SELECT COUNT(FBuid) FROM Users');
    $friendsSQL = $bdd->prepare('SELECT COUNT(FBuid) FROM Friends');
    $users_friendsSQL = $bdd->prepare('SELECT COUNT(*) FROM Users_Friends');
    $maleSQL = $bdd->prepare("SELECT COUNT(FBuid) FROM Friends WHERE Sex = 'male'");
    $femaleSQL = $bdd->prepare("SELECT COUNT(FBuid) FROM Friends WHERE Sex = 'female'");

    $usersSQL->execute();
    $friendsSQL->execute();
    $users_friendsSQL->execute();
    $maleSQL->execute();
    $femaleSQL->execute();

    $users = $usersSQL->fetch(PDO::FETCH_COLUMN, 0);
    $friends = $friendsSQL->fetch(PDO::FETCH_COLUMN, 0);
    $users_friends = $users_friendsSQL->fetch(PDO::FETCH_COLUMN, 0);
    $male = $maleSQL->fetch(PDO::FETCH_COLUMN, 0);
    $female = $femaleSQL->fetch(PDO::FETCH_COLUMN, 0);

    $insert->execute(array('users' => $users, 'friends' => $friends, 'users_friends' => $users_friends, 'male' => $male, 'female' => $female));
  }
 
  stats($bdd);
?>