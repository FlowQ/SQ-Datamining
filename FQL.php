<?php
//Cons = 632255706
//Flow = 1015676688

  if(strpos($_SERVER['HTTP_HOST'], 'localhost')!==false) {
    require_once ('Config/config_dev.php'); //dev
  echo "dev'";
  } else {
    require_once ('Config/config.php'); //prod
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

  // get user access_token
  $token_url = 'https://graph.facebook.com/oauth/access_token?client_id='
    . $app_id . '&redirect_uri=' . urlencode($my_url) 
    . '&client_secret=' . $app_secret 
    . '&code=' . $code;

  // response is of the format "access_token=AAAC..."
  $access_token = substr(file_get_contents($token_url), 13);

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

  // test Flow
  function runQuery($query, $access_token) {
    $fql_query_url = 'https://graph.facebook.com/'
    . $query
    . '&access_token=' . $access_token;
    $fql_query_result = file_get_contents($fql_query_url);
    $fql_query_obj = json_decode($fql_query_result, true);
    return $fql_query_obj;
  }
  $query[] = 'fql?q=SELECT+name+FROM+user+WHERE+uid=me()';
  //$query[] = 'fql?q=SELECT+uid2+FROM+friend+WHERE+uid1=me()';
  $query[] = 'fql?q=SELECT+uid,name+FROM+user+WHERE+uid+IN+(SELECT+uid2+FROM+friend+WHERE+uid1=me())';


//sélectionne la liste des page de type film liké par 20 de mes amis 
// -- Plante si trop de données à traiter
//SELECT page_id,name FROM page WHERE page_id IN (
//SELECT page_id FROM page_fan 
//WHERE type="MOVIE" 
//AND uid IN (SELECT uid2 FROM friend WHERE uid1=me() LIMIT 20))
  foreach ($query as $value) {
    echo '<pre>';
    echo "Query : ".$value."\n";
    print_r(runQuery($value, $access_token));
    echo '</pre>';
  }
  
?>











