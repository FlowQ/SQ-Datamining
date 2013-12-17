<?php

include ('User.php');
include ('UserManager.php');
require_once('Toolbox.php');
 

  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('config/config_dev.php'); //dev
    echo "dev'";
  } else {
    require_once ('config/config.php'); //prod
  } 

// Get User ID
$user = $facebook->getUser();
//print_r($user);

if ($user) 
{
  try {
  $my_access_token=$facebook->getAccessToken();
 /* $user_info = $facebook -> api('/me?fields=id,name,picture');
  $user_info = array(
                      'name' => $user_info['name'],
                      'fbuid' => $user_info['id'],
                      'picture' => $user_info['picture']['data']['url']); */
  
  /*
  * BDD
  */
  $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
  $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);

  /*
  * Déclaration des classes
  */
  $manager = new UserManager($bdd);
  //$user_bdd = new User($user_info);
  //Ajout user en BDD
  //$manager -> add($user_bdd);
  $data = $manager ->add($my_access_token);
  $user_info = new User($bdd);
 // $user_info -> monthsBirthdays($user, $my_access_token);
  $manager -> addFriends($user, $my_access_token);
 // $manager -> topPages($user, $my_access_token);
 $test=  $user_info -> ratioPostFriend($user, $my_access_token);
  print_r($test);
  //echo "coucou";
  //echo $test['top10'][0];
  //print_r($test['top10'][0]);

  } 
  catch (FacebookApiException $e) 
    {
      error_log($e);
    }


 
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
  $loginUrl = $facebook->getLoginUrl(array('scope' => AUTHORIZATIONS));
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">


    <title>Licorne everywhere *_*</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">


    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
<?php include ('header.php'); ?>
    <?php if ($user): ?>
    <div class="container">

      <div class="starter-template">

        <div id="home">
          <h1><img src="https://graph.facebook.com/<?php echo $user; ?>/picture"> Bonjour <?php echo "TODO"; ?>!</h1>
          <p class="lead">Vous avez <?php echo "TODO" ?> amis. Et vous avez posté <?php echo "TODO" ?> fois sur facebook </p>
          <?php echo $user_info -> listSchools($user, $my_access_token); ?>
        </div>

        <div id="gender">
        <p class="lead"> Voici le pourcentage d'homme et de femme dans vos amis facebook :  </p>
        <div id="gender_graph"></div>
        </div>

        <div id="friendsstats">
        <p class="lead"> Statistiques de vos amis :  </p>
        <div id="friendsstats_graph_average"></div>
        <div id="friendsstats_graph_top10"></div>
        <div id="friendsstats_graph_low10"></div>
        </div>

        <div id="ratiopost">
        <p class="lead"> Ratio Post :  </p>
        <div id="ratiopost_graph_average"></div>
        <div id="ratiopost_graph_top10"></div>
        <div id="ratiopost_graph_low10"></div>
        </div>

        <div id="school">
        <p class="lead"> School </p>
        <div id="school_graph"></div>
        </div>

        <div id="birthday">
         <p class="lead"> </p>
        <p class="lead"> Voici la répartition des anniversaires de vos amis par mois : </p>
        <div id="birthday_graph"></div>

        </div>

      </div><!-- /.container -->
    </div>
    <?php else: ?> 
    <div class="container">

      <div class="starter-template">
        <h1>You are not Connected.</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>

      </div>
    
    </div><!-- /.container -->
    <?php endif ?>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
  </body>
</html>