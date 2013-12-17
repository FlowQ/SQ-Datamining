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
  include 'Toolbox.php';

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

  function listSchools($user, $bdd, $access_token) {
    $listSchoolsSQL = $bdd->prepare('SELECT School FROM Friends WHERE School is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listSchoolsSQL->execute();
    $listSchools = $listSchoolsSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listSchools);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      if($value > 1)
        $result[] = array($key, $value);
    }
    print_r($result);
  }

  function listCompanys($user, $bdd, $access_token) {
    $listCompanysSQL = $bdd->prepare('SELECT WorkCompany FROM Friends WHERE WorkCompany is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listCompanysSQL->execute();
    $listCompanys = $listCompanysSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listCompanys);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      if($value > 1)
        $result[] = array($key, $value);
    }
    print_r($result);
  }

  //outil pour sevenDaysBirthdays
  function semaine() {
    $jour = (int) date('j');
    $mois = (int) date('m');
    $annee = (int) date('Y');

    $return = array();
    $return[] = "-$mois-$jour";
    $jour++;
    for($i=0; $i<7; $i++) {
      while(!checkdate($mois, $jour, $annee)) {
        if($jour>27 && $jour <= 31) {
          $jour++; 
        }else if($jour > 31) {
          $jour = 1;
          $mois++;
        } else if ($mois > 12) {
          $mois = 1;
          $annee++;
        }
      }
      $return[]="-$mois-$jour";
      $jour++;
    }
    return $return;
  }

  function sevenDaysBirthdays($user, $bdd, $access_token) {
    $listBirthdaysSQL = $bdd->prepare('SELECT Birthday,Name FROM Friends WHERE Birthday is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listBirthdaysSQL->execute();
    $listDates = $listBirthdaysSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $listBirthdaysSQL->execute();
    $listName = $listBirthdaysSQL->fetchall(PDO::FETCH_COLUMN, 1);
    $listSemaine = semaine();

    $return = array();
    foreach ($listSemaine as $jour) {
      for($i = 0 ; $i<count($listDates) ; $i++) {
        //echo "<p>$listDates[$i] - $jour</p>";
        echo '<p>'.strpos($listDates[$i], $jour).'</p>';
        if(strpos($listDates[$i], $jour)) {
          $return[] = array($listName[$i], $listDates[$i]);
        }
      }
    }
    print_r($return);



    echo "string";
    //print_r($listBirthdays);
  }

  function monthsBirthdays($user, $bdd, $access_token) {
    $listBirthdaysSQL = $bdd->prepare('SELECT Birthday FROM Friends WHERE Birthday is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listBirthdaysSQL->execute();
    $listBirthdays = $listBirthdaysSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $result = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
    foreach ($listBirthdays as $friend) {
      $month = $friend[5].$friend[6];
      $result[$month]++;
    }
    print_r($result);
  }

  function currentCountry($user, $bdd, $access_token) {
    $listCountrySQL = $bdd->prepare('SELECT CurrentCountry FROM Friends WHERE CurrentCountry is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listCountrySQL->execute();
    $listCountry = $listCountrySQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listCountry);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      $result[] = array($key, $value);
    }
    print_r($result);
  }

  function originCountry($user, $bdd, $access_token) {
    $listCountrySQL = $bdd->prepare('SELECT OriginCountry FROM Friends WHERE OriginCountry is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listCountrySQL->execute();
    $listCountry = $listCountrySQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listCountry);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      $result[] = array($key, $value);
    }
    print_r($result);
  }

  function currentCity($user, $bdd, $access_token) {
    $listCitySQL = $bdd->prepare('SELECT CurrentCity FROM Friends WHERE CurrentCity is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listCitySQL->execute();
    $listCity = $listCitySQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listCity);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      $result[] = array($key, $value);
    }
    print_r($result);
  }

  //nombre d'amis
  function friendsStats($user, $bdd, $access_token) {
    $meSQL = $bdd->prepare("SELECT FriendCount FROM Users WHERE FBuid = ".$user);
    $listFriendsSQL = $bdd->prepare('SELECT FriendCount,Name FROM Friends WHERE FriendCount is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listFriendsSQL->execute();
    $listCount = $listFriendsSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $listFriendsSQL->execute();
    $listFriends = $listFriendsSQL->fetchall(PDO::FETCH_COLUMN, 1);

    $meSQL->execute();

    array_multisort($listCount, $listFriends);
    $stats = array();
    $stats['me'] = $meSQL->fetch(PDO::FETCH_COLUMN, 0);

    $mid = floor(count($listCount)/2);
    $stats['median'] = $listCount[$mid];

    $sum = 0;
    foreach ($listCount as $friend) {
      $sum += $friend;
    }

    $stats['average'] = floor($sum/count($listCount));
    $top10 = array(array_slice($listFriends, -10), array_slice($listCount, -10));
    $low10 = array(array_slice($listFriends, 0, 10), array_slice($listCount, 0, 10));
    for ($i=0; $i <10 ; $i++) { 
      $stats['top10'][] = array($top10[0][$i], $top10[1][$i]);
      $stats['low10'][] = array($low10[0][$i], $low10[1][$i]);  
    }
    print_r($stats); 
  }

  //nombre de posts sur le mur
  function wallStats($user, $bdd, $access_token) {
    $meSQL = $bdd->prepare("SELECT PostCount FROM Users WHERE FBuid = ".$user);
    $listFriendsSQL = $bdd->prepare('SELECT PostCount,Name FROM Friends WHERE PostCount is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listFriendsSQL->execute();
    $listCount = $listFriendsSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $listFriendsSQL->execute();
    $listFriends = $listFriendsSQL->fetchall(PDO::FETCH_COLUMN, 1);

    $meSQL->execute();

    array_multisort($listCount, $listFriends);
    $stats = array();
    $stats['me'] = $meSQL->fetch(PDO::FETCH_COLUMN, 0);

    $mid = floor(count($listCount)/2);
    $stats['median'] = $listCount[$mid];

    $sum = 0;
    foreach ($listCount as $friend) {
      $sum += $friend;
    }

    $stats['average'] = floor($sum/count($listCount));
    $top10 = array(array_slice($listFriends, -10), array_slice($listCount, -10));
    $low10 = array(array_slice($listFriends, 0, 10), array_slice($listCount, 0, 10));
    for ($i=0; $i <10 ; $i++) { 
      $stats['top10'][] = array($top10[0][$i], $top10[1][$i]);
      $stats['low10'][] = array($low10[0][$i], $low10[1][$i]);  
    }
    print_r($stats); 
  }

  //nombre de posts sur le mur par ami
  function ratioPostFriend($user, $bdd, $access_token) {
    $meSQL = $bdd->prepare("SELECT PostCount/FriendCount FROM Users WHERE FBuid = ".$user);
    $listFriendsSQL = $bdd->prepare('SELECT PostCount/FriendCount,Name FROM Friends WHERE FriendCount is not null AND PostCount is not null AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listFriendsSQL->execute();
    $listCount = $listFriendsSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $listFriendsSQL->execute();
    $listFriends = $listFriendsSQL->fetchall(PDO::FETCH_COLUMN, 1);

    $meSQL->execute();

    array_multisort($listCount, $listFriends);
    $stats = array();
    $stats['me'] = $meSQL->fetch(PDO::FETCH_COLUMN, 0);

    $mid = floor(count($listCount)/2);
    $stats['median'] = $listCount[$mid];

    $sum = 0;
    foreach ($listCount as $friend) {
      $sum += $friend;
    }

    $stats['average'] = $sum/count($listCount);
    $top10 = array(array_slice($listFriends, -10), array_slice($listCount, -10));
    $low10 = array(array_slice($listFriends, 0, 10), array_slice($listCount, 0, 10));
    for ($i=0; $i <10 ; $i++) { 
      $stats['top10'][] = array($top10[0][$i], $top10[1][$i]);
      $stats['low10'][] = array($low10[0][$i], $low10[1][$i]);  
    }
    print_r($stats); 
  }

  //cree un top50 des pages likees par les amis que le user ne like pas
  //prend environ 2 minutes 
  function listLikes($user, $bdd, $access_token) {
    $listMyLikesFQL = 'SELECT page_id FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid = me())';
    $likeName = "SELECT name, page_id FROM page WHERE page_id IN (";

    $listFriendsIDSQL = $bdd->prepare('SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user); 
    $listLikesSQL = $bdd->prepare('SELECT FBpid FROM Likes WHERE FBuid = :uid');

    $listTotale = array();

    $listFriendsIDSQL->execute();
    $listFriendsID = $listFriendsIDSQL->fetchall(PDO::FETCH_COLUMN, 0);

    foreach ($listFriendsID as $friend) {
      $listLikesSQL->execute(array('uid' => $friend));
      $result = $listLikesSQL->fetchall(PDO::FETCH_COLUMN, 0);
      $listTotale = array_merge($listTotale, $result);
    }

    $listCount = array_count_values($listTotale);

    $listMyLikes = queryRun($listMyLikesFQL, $access_token);

    foreach ($listMyLikes['data'] as $myLike) {
      $listCount[$myLike['page_id']] = 0;
    }

    asort($listCount);

    $top50 = array_slice($listCount, -50, 50, true);

    foreach ($top50 as $index => $value) {
      $likeName .= $index.", ";
    }
    $likeName .= '0)';
    $names = queryRun($likeName, $access_token);

    $result = array();
    foreach ($names['data'] as $name) {
      $result[] = array($name['name'], $name['page_id'], $top50[$name['page_id']]);
    }

    print_r($result);
  }

  function sameCountry($user, $bdd, $access_token) {  
    $listSQL = $bdd->prepare("SELECT Name FROM Friends WHERE OriginCountry = CurrentCountry AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = $user)");
    $listSQL->execute();

    $list = $listSQL->fetchall(PDO::FETCH_COLUMN, 0);

    print_r($list);
  }

  function sex($user, $bdd, $access_token) {  
    $listMaleSQL = $bdd->prepare("SELECT Count(FBuid) FROM Friends WHERE Sex = 'male' AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = $user)");
    $listFemaleSQL = $bdd->prepare("SELECT Count(FBuid) FROM Friends WHERE Sex = 'female' AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = $user)");
    $listMaleSQL->execute();
    $listFemaleSQL->execute();

    $result['male'] = $listMaleSQL->fetch(PDO::FETCH_COLUMN, 0);
    $result['female'] = $listFemaleSQL->fetch(PDO::FETCH_COLUMN, 0);

    print_r($result);
  }

  function age($user, $bdd, $access_token) {
    $listSQL = $bdd->prepare("SELECT Birthday FROM Friends WHERE Birthday AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = $user)");
    $year = Date('Y');

    $listSQL->execute();
    $list = $listSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $result['0-18'] = 0;
    $result['19-30'] = 0;
    $result['31-50'] = 0;
    $result['50+'] = 0;

    foreach ($list as $friend) {
      $getYear = explode('-', $friend);
      $diff = $year - intval($getYear[0]);
      if($diff < 19) {
        $result['0-18']++;
      } elseif ($diff < 31) {
        $result['19-30']++;
      } elseif ($diff < 51) {
        $result['31-50']++;
      } else {
        $result['50+']++;
      }
    }

    print_r($result);
  }

  function pictureWall($user, $bdd, $access_token) {
    $listPictSQL = $bdd->prepare('SELECT Picture FROM Friends WHERE Picture IS NOT NULL AND FBuid IN (SELECT FB_FBuid FROM APP_FB_Users WHERE APP_FBuid = '.$user.')');
    $listPictSQL->execute();
    $listPict = $listPictSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $randList = shuffle($listPict);
    print_r($randList);
    echo '<p>';
    $i=0;
    foreach ($listPict as $picture) {
      echo '<img src="'.$picture.'"/>';
      if($i++ == 24) {
        echo "</p><p>";
        $i=0;
      }
    }
    echo "</p>";
  }

  function printTop10($user, $bdd, $access_token) {
    $listSQL = $bdd->prepare('SELECT Top10 FROM Users WHERE FBuid = '.$user);
    $nameSQL = $bdd->prepare('SELECT Name FROM Friends WHERE FBuid = :fbuid');
    $listSQL->execute();

    $list = $listSQL->fetch(PDO::FETCH_COLUMN, 0);
    $list_ex = explode('-', $list);
    $result =array();
    for ($i=0; $i <10 ; $i++) { 
      $friend_ex = explode('_', $list_ex[$i]);
      $nameSQL->execute(array('fbuid' => $friend_ex[0]));
      $name = $nameSQL->fetch(PDO::FETCH_COLUMN, 0);
      $result[] = array($name, $friend_ex[1]);
    }

    print_r($result);

  }

  function call($user, $bdd, $access_token) {
    printTop10($user, $bdd, $access_token);
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
      call($user, $bdd, $my_access_token);
      echo '</pre>';
	 ?>
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>
</div>
  </body>
</html>
