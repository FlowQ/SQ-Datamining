<?php
$action = isset($_POST['action']) ?  $_POST['action'] : false;

if($action=="gender")
{
  include('connect.php');

  $user_info -> sex($user, $my_access_token);
 // print_r($user_info);
  //$user_name = $user_info -> name();
  //$user_info -> monthsBirthdays($user, $my_access_token);
} 
else if($action=="country_current")
{
  include('connect.php');

  $current_country= $user_info -> currentCountry($user, $my_access_token);
  foreach ($current_country as $country) {
    $stat_current_country[] = array($country[0], (int)$country[1]);
  }
  //$stat_school = array_reverse($stat_school);
  echo json_encode($stat_current_country);
 // print_r($user_info);
  //$user_name = $user_info -> name();
  //$user_info -> monthsBirthdays($user, $my_access_token);
} 
else if($action=="city_current")
{
  include('connect.php');

  $current_country= $user_info -> currentCity($user, $my_access_token);
  foreach ($current_country as $country) {
    $stat_current_country[] = array($country[0], (int)$country[1]);
  }
  //$stat_school = array_reverse($stat_school);
  echo json_encode($stat_current_country);
 // print_r($user_info);
  //$user_name = $user_info -> name();
  //$user_info -> monthsBirthdays($user, $my_access_token);
} 
else if($action=="country_origin")
{
  include('connect.php');

  $current_country= $user_info -> originCountry($user, $my_access_token);
  foreach ($current_country as $country) {
    $stat_current_country[] = array($country[0], (int)$country[1]);
  }
  //$stat_school = array_reverse($stat_school);
  echo json_encode($stat_current_country);
  //$gender[]=array("Femelle", (int)$female);
 // print_r($user_info);
  //$user_name = $user_info -> name();
  //$user_info -> monthsBirthdays($user, $my_access_token);
} 
else if($action=="list_school")
{
  include('connect.php');
  $list_school = $user_info -> listSchools($user, $my_access_token);
 // print_r($list_school);

//  $stat_friend_average[]=array('name'=>"Average", 'data' =>array((int)$friendsstats_average['average']));
  $stat_school = array();
  foreach ($list_school as $school) {
    $stat_school[] = array('name' => $school[0], 'data' => array((int)$school[1]));
  }
  $stat_school = array_reverse($stat_school);
  echo json_encode($stat_school); 
  //$stat_list_school = array_reverse($stat_list_school);
  //echo json_encode($stat_list_school);

}
else if($action=="birthday")
{
  include('connect.php');

  $user_info -> monthsBirthdays($user, $my_access_token);
}
else if($action=="friendsstats_average")
{
  include('connect.php');

  $friendsstats_average = $user_info -> friendsStats($user, $my_access_token);
  //print_r($friendsstats_average);
  $stat_friend_average[]=array('name'=>"Moi", 'data' =>array((int)$friendsstats_average['me']));
  $stat_friend_average[]=array('name'=>"Median", 'data' =>array((int)$friendsstats_average['median']));
  $stat_friend_average[]=array('name'=>"Average", 'data' =>array((int)$friendsstats_average['average']));
  echo json_encode($stat_friend_average);


}
else if($action=="friendsstats_top10")
{
  include('connect.php');

  $friendsstats_top10 = $user_info -> friendsStats($user, $my_access_token);
  //print_r($friendsstats_average);
  for ($i=0; $i <10 ; $i++) { 
      $stats_friends_top10[] = array('name'=>$friendsstats_top10['top10'][$i][0], 'data' =>array((int)$friendsstats_top10['top10'][$i][1]));
    }
  $stats_friends_top10 = array_reverse($stats_friends_top10);
  echo json_encode($stats_friends_top10);


}
else if($action=="friendsstats_low10")
{
  include('connect.php');

  $friendsstats_low10 = $user_info -> friendsStats($user, $my_access_token);
  //print_r($friendsstats_average);
  for ($i=0; $i <10 ; $i++) { 
      $stats_friends_low10[] = array('name'=>$friendsstats_low10['low10'][$i][0], 'data' =>array((int)$friendsstats_low10['low10'][$i][1]));
    }
  $stats_friends_low10 = array_reverse($stats_friends_low10);
  echo json_encode($stats_friends_low10);

}
else if($action=="ratiopost_average")
{
  include('connect.php');

  $ratiopost_average = $user_info -> ratioPostFriend($user, $my_access_token);
  //print_r($friendsstats_average);
  $stat_ratiopost_average[]=array('name'=>"Moi", 'data' =>array((float)$ratiopost_average['me']));
  $stat_ratiopost_average[]=array('name'=>"Median", 'data' =>array((float)$ratiopost_average['median']));
  $stat_ratiopost_average[]=array('name'=>"Average", 'data' =>array((float)$ratiopost_average['average']));
  echo json_encode($stat_ratiopost_average);


}
else if($action=="ratiopost_top10")
{
  include('connect.php');

  $ratiopost_top10 = $user_info -> ratioPostFriend($user, $my_access_token);
  //print_r($friendsstats_average);
  for ($i=0; $i <10 ; $i++) { 
      $stats_ratiopost_top10[] = array('name'=>$ratiopost_top10['top10'][$i][0], 'data' =>array((float)$ratiopost_top10['top10'][$i][1]));
    }
  $stats_ratiopost_top10 = array_reverse($stats_ratiopost_top10);
  echo json_encode($stats_ratiopost_top10);


}
else if($action=="ratiopost_low10")
{
  include('connect.php');

  $ratiopost_low10 = $user_info -> ratioPostFriend($user, $my_access_token);
  //print_r($friendsstats_average);
  for ($i=0; $i <10 ; $i++) { 
      $stats_ratiopost_low10[] = array('name'=>$ratiopost_low10['low10'][$i][0], 'data' =>array((float)$ratiopost_low10['low10'][$i][1]));
    }
  $stats_ratiopost_low10 = array_reverse($stats_ratiopost_low10);
  echo json_encode($stats_ratiopost_low10);


}

else if($action=="wallpost_average")
{
  include('connect.php');

  $wallpost_average = $user_info -> wallStats($user, $my_access_token);
  //print_r($friendsstats_average);
  $stat_wallpost_average[]=array('name'=>"Moi", 'data' =>array((int)$wallpost_average['me']));
  $stat_wallpost_average[]=array('name'=>"Median", 'data' =>array((int)$wallpost_average['median']));
  $stat_wallpost_average[]=array('name'=>"Average", 'data' =>array((int)$wallpost_average['average']));
  echo json_encode($stat_wallpost_average);


}
else if($action=="wallpost_top10")
{
  include('connect.php');

  $wallpost_top10 = $user_info -> wallStats($user, $my_access_token);
  //print_r($friendsstats_average);
  for ($i=0; $i <10 ; $i++) { 
      $stats_wallpost_top10[] = array('name'=>$wallpost_top10['top10'][$i][0], 'data' =>array((int)$wallpost_top10['top10'][$i][1]));
    }
  $stats_wallpost_top10 = array_reverse($stats_wallpost_top10);
  echo json_encode($stats_wallpost_top10);


}
else if($action=="wallpost_low10")
{
  include('connect.php');

  $wallpost_low10 = $user_info -> wallStats($user, $my_access_token);
  //print_r($friendsstats_average);
  for ($i=0; $i <10 ; $i++) { 
      $stats_wallpost_low10[] = array('name'=>$wallpost_low10['low10'][$i][0], 'data' =>array((int)$wallpost_low10['low10'][$i][1]));
    }
  $stats_wallpost_low10 = array_reverse($stats_wallpost_low10);
  echo json_encode($stats_wallpost_low10);
} 
else if($action == "sevenDays_Birthdays") 
{
  include('connect.php');
  $listBirthday = $user_info -> sevenDaysBirthdays($user, $my_access_token);
}
else if($action == "relationship_count")
{
  include('connect.php');
  $data = $user_info ->couplesSingles($user, $my_access_token);
  echo json_encode($data['graph']);
}
else if($action == 'couples_list')
{
  include('connect.php');
  $data = $user_info -> couplesSingles($user, $my_access_token);
  //print_r($data["lists"]['couples']);
  if(count($data["lists"]['couples']) == 1) {
    echo "<p>Le seul de vos amis en couple est ".$data["lists"]['couples'][0][0].", qui est en couple avec ".$data["lists"]['couples'][0][1]."</p>";
  } else if(count($data["lists"]['couples']) > 1) {
    echo "<p>Les couples de vos amis sont :</p>";
    foreach ($data["lists"]['couples'] as $couple) {
      if($couple[0!=null])
        echo "<p>* ".$couple[0]." et ".$couple[1]."</p>";
    }
  }
}
?>