<?php
//Cons = 632255706
//Flow = 1015676688

  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('config/config_dev.php'); //dev
  echo "dev'";
  } else {
    require_once ('config/config.php'); //prod
  } 

  $app_id = APP_ID;
  $app_secret = APP_SECRET;
  $my_url = CALLBACK_URL;

  $code = $_REQUEST["code"];

 // auth user
 if(empty($code)) {
    $dialog_url = 'https://www.facebook.com/dialog/oauth?client_id=' 
    . $app_id . '&redirect_uri=' . urlencode($my_url) ;
    echo("<script>top.location.href='" . $dialog_url . "'</script>");
  }

  $loginUrl = $facebook->getLoginUrl(array('scope' => 'read_stream, user_friends, friends_relationships, user_likes, friends_likes, friends_birthday'));

  // get user access_token
  $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
    . $app_id . '&redirect_uri=' . urlencode($my_url) 
    . '&client_secret=' . $app_secret 
    . '&code=' . $code;

  // response is of the format "access_token=AAAC..."
  $access_token = substr(file_get_contents($token_url), 13);

  //bdd
  $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
  $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);

  echo '<a href="FQL.php">Refresh</a>';
//Facebook exemples
/*
  // run fql query
  $fql_query_url = 'https://graph.facebook.com/'
    . 'fql?q=SELECT+uid2+FROM+friend+WHERE+uid1=me()'
    . '&access_token=' . $access_token;
  $fql_query_result = file_get_contents($fql_query_url);
  $fql_query_obj = json_decode($fql_query_result, true);

  // display results of fql query
  echo '<pre>';
//  print_r("query results:");
//  print_r($fql_query_obj);
  echo '</pre>';

  // run fql multiquery
  $fql_multiquery_url = 'https://graph.facebook.com/'
    . 'fql?q={"all+friends":"SELECT+uid2+FROM+friend+WHERE+uid1=me()",'
    . '"my+name":"SELECT+name+FROM+user+WHERE+uid=me()"}'
    . '&access_token=' . $access_token;
  $fql_multiquery_result = file_get_contents($fql_multiquery_url);
  $fql_multiquery_obj = json_decode($fql_multiquery_result, true);

  // display results of fql multiquery
  echo '<pre>';
//  print_r("multi query results:");
//  print_r($fql_multiquery_obj);
  echo '</pre>';*/

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
  function signin($bdd, $access_token){
    $meFB = "SELECT name,uid,friend_count,pic_big FROM user WHERE uid=me()";
    $listUserDB = $bdd->prepare("SELECT FBuid from Users WHERE FBuid = :fbuid");
    $addUser = $bdd->prepare("INSERT INTO Users (FBuid, Name, FriendCount, Picture) VALUES (:fbuid, :name, :friendcount, :picture)");

    $result = queryRun($meFB, $access_token)['data'][0];
    $listUserDB->execute(array('fbuid' => $result['uid']));
    if($already = $listUserDB->fetch(PDO::FETCH_COLUMN, 0)) {
      echo "inscrit";
    } else {
      echo "ajoute";
      $addUser->execute(array('fbuid' => $result['uid'], 'name' => $result['name'], 'friendcount' => $result['friend_count'], 'picture' => $result['pic_big']));
    }
    //print_r($result);
  }

  $query[] = 'SELECT name FROM user WHERE uid = me()';
  //$query[] = 'fql?q=SELECT+uid2+FROM+friend+WHERE+uid1=me()';
  $query[] = 'SELECT uid,name,education.school,likes_count,mutual_friend_count,relationship_status,religion,wall_count,significant_other_id FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())';
  $users = 'SELECT uid,name,friend_count,birthday_date,current_location.city,current_location.country, hometown_location.city, hometown_location.country,pic_big,sex,work.employer FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())';
  $listFriends[] = 'SELECT uid,mutual_friend_count FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())';
//sélectionne la liste des page de type film liké par 20 de mes amis 
// -- Plante si trop de données à traiter
//SELECT page_id,name FROM page WHERE page_id IN (
//SELECT page_id FROM page_fan 
//WHERE type="MOVIE" 
//AND uid IN (SELECT uid2 FROM friend WHERE uid1=me() LIMIT 20))
  //foreach ($listFriends as $value) {
    echo '<pre>';
    //echo "Query : ".$value."\n";
    signin($bdd, $access_token);
    //print_r(queryRun($value, $access_token));
    echo '</pre>';
  //}
  
?>











