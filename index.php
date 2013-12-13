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
 include ('User.php');
 include ('UserManager.php');
 

  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('config/config_dev.php'); //dev
    echo "dev'";
  } else {
    require_once ('config/config.php'); //prod
  } 

// Get User ID
$user = $facebook->getUser();

if ($user) 
{
  try {
  $user_info = $facebook -> api('/me?fields=id,name,picture');
  $user_info = array(
                      'name' => $user_info['name'],
                      'fbuid' => $user_info['id'],
                      'picture' => $user_info['picture']['data']['url']);
  
  /*
  * BDD
  */
  $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
  $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);

  /*
  * Déclaration des classes
  */
  $manager = new UserManager($bdd);
  $user_bdd = new User($user_info);
  //Ajout user en BDD
  $manager -> add($user_bdd);
  } 
  catch (FacebookApiException $e) 
    {
      error_log($e);
    }


 
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
      <h3> <img src="https://graph.facebook.com/<?php echo $user; ?>/picture"> Bonjour <?php echo $user_info["name"]; ?>!</h3>
     
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