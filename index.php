<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */


  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('config/config_dev.php'); //dev
    echo "dev'";
  } else {
    require_once ('config/config.php'); //prod
  } 
  //bdd
  $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
  $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);

// Get User ID
$user = $facebook->getUser();


// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me' );
    $my_access_token=$facebook->getAccessToken();
		try {

		  $friends = $facebook->api('/me?fields=friends.fields(name,gender)&limit=9999&offset=9999',array('access_token'=>$my_access_token));
		} catch (FacebookApiException $e) {
		  error_log($e);
		}
		
		} catch (FacebookApiException $e) {
		  error_log($e);
		  $user = null;
		}
	}
	// Login or logout url will be needed depending on current user state.
if ($user) {
  signin($user, $bdd, $my_access_token);
 
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl(array('scope' => AUTHORIZATIONS));
}

    //construit la Query de forme FQL en URL
  function queryConstructor($query) {
    $result = 'fql?q=';
    return $result.str_replace(' ', '+', $query);
  }

  // test Flow
  function queryRun($query, $access_token) {
    $fql_query_url = 'https://graph.facebook.com/'
    . queryConstructor($query)
    . '&access_token=' . $access_token;
    $fql_query_result = file_get_contents($fql_query_url);
    $fql_query_obj = json_decode($fql_query_result, true);
    return $fql_query_obj;
  }

  //test si l'utilisateur est deja inscrit, sinon l'inscrit
  function signin($user, $bdd, $access_token){
    $meFB = "SELECT name,uid,friend_count,pic_big FROM user WHERE uid=me()";
    $listUserDB = $bdd->prepare("SELECT FBuid from Users WHERE FBuid = ".$user);
    $addUser = $bdd->prepare("INSERT INTO Users (FBuid, Name, FriendCount, Picture) VALUES (:fbuid, :name, :friendcount, :picture)");

    $result = queryRun($meFB, $access_token)['data'][0];
    $listUserDB->execute();
    if($already = $listUserDB->fetch(PDO::FETCH_COLUMN, 0)) {
      echo "inscrit";
    } else {
      echo "ajoute";
      $addUser->execute(array('fbuid' => $result['uid'], 'name' => $result['name'], 'friendcount' => $result['friend_count'], 'picture' => $result['pic_big']));
    }
    //print_r($result);
  }

  function exists($var) {
    if(isset($var)) {
      return $var;
    } else {
      return null;
    }
  }

  function dateFQLtoSQL($date) {
    $result = str_word_count($date, 1, '0123456789');
    if(count($result) > 2) {
      $month = "01";
      switch ($result[0]) {
        case 'January':
          $month = '01';
        break;
        case 'February':
          $month = '02';
        break;
        case 'March':
          $month = '03';
        break;
        case 'April':
          $month = '04';
        break;
        case 'May':
          $month = '05';
        break;
        case 'June':
          $month = '06';
        break;
        case 'July':
          $month = '07';
        break;
        case 'August':
          $month = '08';
        break;
        case 'September':
          $month = '09';
        break;
        case 'October':
          $month = '10';
        break;
        case 'November':
          $month = '11';
        break;
        case 'December':
          $month = '12';
        break;
      }
      return $result[2]."-".$month."-".$result[1];
    }
  }

  function sdf($user, $bdd, $access_token) {
    $query = 'SELECT uid,name,mutual_friend_count,education.school,current_location.city,current_location.country,hometown_location.country,pic_big,sex,work.employer,likes_count,friend_count,sex,wall_count,birthday FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())';
    $addFriend = $bdd->prepare("INSERT INTO Friends (FBuid, Name, FriendCount, PostCount, Sex, Birthday, Picture,CurrentCountry, CurrentCity, OriginCountry, WorkCompany, School, AddUser) ".
                              "VALUES (:fbuid, :name, :fcount, :pcount, :sex, :birthday, :picture, :ccountry, :ccity, :ocountry, :company, :school, :adduser)");
    $modifyFriend = $bdd->prepare("UPDATE Friends SET FriendCount = :fcount, PostCount = :pcount, CurrentCountry = :ccountry, CurrentCity = :ccity, WorkCompany = :company, School = :school, UpdateDate = :udate WHERE FBuid = :fbuid");
    $isInDB = $bdd->prepare("SELECT FBuid FROM Friends WHERE FBuid = :fbuid");

    $addRelationship = $bdd->prepare("INSERT INTO App_FB_Users (App_FBuid, FB_FBuid, MutualFriends) VALUES ($user, :friend, :mfriend)");

    $result = queryRun($query, $access_token);
    foreach ($result['data'] as $friend ) {
      $isInDB->execute(array('fbuid' => $friend['uid']));
      if(($t = $isInDB->fetch(PDO::FETCH_COLUMN, 0)) && true) {
        echo "<p>".print_r($friend)."</p>";
      } else {
       $addFriend->execute(array('fbuid' => $friend['uid'], 'name' => exists($friend['name']), 'fcount' => exists($friend['friend_count']), 'pcount' => exists($friend['wall_count']), 
                                  'sex' => exists($friend['sex']), 'birthday' => dateFQLtoSQL(exists($friend['birthday'])), 'picture' => exists($friend['pic_big']), 'ccountry' => exists($friend['current_location']['country']), 
                                  'ccity' => exists($friend['current_location']['city']), 'ocountry' => exists($friend['hometown_location']['country']), 'company' => exists($friend['work'][0]['employer']['name']), 
                                  'school' => exists($friend['education'][0]['school']['name']), 'adduser' => exists($user)));
      }
      $addRelationship->execute(array('friend' => exists($friend['uid']), 'mfriend' => exists($friend['mutual_friend_count'])));
    }
  }

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>Licorne everywhere *_*</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
 </head>
  <body>
  	  <div id="container" align="center">
    <h1>Facebook Dashboard Profil</h1>

    <?php if ($user): ?>
      <a href="<?php echo $facebook->getLogoutUrl(); ?>">Logout</a>
    <?php else: ?>
      <div>
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

    <?php if ($user): ?>
      <h3> <img src="https://graph.facebook.com/<?php echo $user; ?>/picture"> Bonjour <?php echo $user_profile["name"]; ?>!</h3>
     
     <!-- <pre><?php echo print_r($friends) ?></pre> -->
  <!--   <a href="stats.php?token=<?php echo $my_access_token; ?>">Me</a> -->
  <div id="filters">
   <select id="friends_stats" name="friends_stats">
   	  <option value="default" selected="selected">Choisissez une option</option>
      <option value="gender">Gender</option>
      <option value="top">Top 10 Like</option>
      <option value="relationship">Relationship</option>
      <option value="age_range">Age range</option>
   </select>
  </div>
  <div id="target">
	 	</div>
	 	<center>
	 	<div id="ajax-loading">
	 		<p>Le chargement est un peu long, vous pouvez aller prendre un café... (environ 2 min)</p>
   	    <img src="http://www.ajaxload.info/images/exemples/5.gif" alt="Loading" />
   	    </div>
   	    </center>
    <?php 
      echo '<pre>';
      sdf($user, $bdd, $my_access_token);
      echo '</pre>';
	 ?>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>
</div>
  </body>
</html>
