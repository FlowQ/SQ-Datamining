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
 
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl(array('scope' => 'read_stream, user_friends, friends_relationships, user_likes, friends_likes, friends_birthday'));
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
	
    <?php else: ?>
      <strong><em>You are not Connected.</em></strong>
    <?php endif ?>
</div>
  </body>
</html>
