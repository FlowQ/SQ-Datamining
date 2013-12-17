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
else if($action=="list_school")
{
  include('connect.php');

  $user_info -> listSchools($user, $my_access_token);
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
  $stat_ratiopost_average[]=array('name'=>"Moi", 'data' =>array((int)$ratiopost_average['me']));
  $stat_ratiopost_average[]=array('name'=>"Median", 'data' =>array((int)$ratiopost_average['median']));
  $stat_ratiopost_average[]=array('name'=>"Average", 'data' =>array((int)$ratiopost_average['average']));
  echo json_encode($stat_ratiopost_average);


}
else if($action=="ratiopost_top10")
{
  include('connect.php');

  $ratiopost_top10 = $user_info -> ratioPostFriend($user, $my_access_token);
  //print_r($friendsstats_average);
  for ($i=0; $i <10 ; $i++) { 
      $stats_ratiopost_top10[] = array('name'=>$ratiopost_top10['top10'][$i][0], 'data' =>array((int)$ratiopost_top10['top10'][$i][1]));
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
      $stats_ratiopost_low10[] = array('name'=>$ratiopost_low10['low10'][$i][0], 'data' =>array((int)$ratiopost_low10['low10'][$i][1]));
    }
  $stats_ratiopost_low10 = array_reverse($stats_ratiopost_low10);
  echo json_encode($stats_ratiopost_low10);


}
?>