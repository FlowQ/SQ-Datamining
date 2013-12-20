<?php
class UserManager extends Toolbox
{
  private $_db; // Instance de PDO
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  /*
  * Fonction d'ajout en bdd des infos de l'utilisateur
  */
  public function add($access_token){
    
    $meFB = "SELECT name,uid,friend_count,wall_count,pic_big FROM user WHERE uid=me()";
    $listUserDB = $this->_db->prepare("SELECT FBuid from Users WHERE FBuid = :fbuid");
    $addUser = $this->_db->prepare("INSERT INTO Users (FBuid, Name, FriendCount, PostCount, Picture) VALUES (:fbuid, :name, :friendcount, :postcount, :picture)");
    $res = $this -> queryRun($meFB, $access_token);
    $result = $res['data'][0];
    //$user_bdd = new User($result);
    $listUserDB->execute(array('fbuid' => $result['uid']));
    if($already = $listUserDB->fetch(PDO::FETCH_COLUMN, 0)) {
     // echo "inscrit";
    } else {
     //echo "ajoute";
      $addUser->execute(array('fbuid' => $result['uid'], 'name' => $result['name'], 'friendcount' => $result['friend_count'],'postcount' => $result['wall_count'], 'picture' => $result['pic_big']));
    }
    return $result;
  }
  /*
  * Fonction d'ajout en bdd des infos des amis de l'utilisateur
  */
  public function addFriends($user, $access_token) {
    $query = 'SELECT uid,name,mutual_friend_count,education.school,current_location.city,current_location.country,hometown_location.country,pic_big,sex,work.employer,likes_count,friend_count,sex,wall_count,birthday FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())';
    $addFriend = $this->_db->prepare("INSERT INTO Friends (FBuid, Name, FriendCount, PostCount, Sex, Birthday, Picture,CurrentCountry, CurrentCity, OriginCountry, WorkCompany, School, AddUser) ".
                              "VALUES (:fbuid, :name, :fcount, :pcount, :sex, :birthday, :picture, :ccountry, :ccity, :ocountry, :company, :school, :adduser)");
    $modifyFriend = $this->_db->prepare("UPDATE Friends SET FriendCount = :fcount, PostCount = :pcount, CurrentCountry = :ccountry, CurrentCity = :ccity, WorkCompany = :company, School = :school, UpdateDate = :udate WHERE FBuid = :fbuid");
    $isInDB = $this->_db->prepare("SELECT FBuid FROM Friends WHERE FBuid = :fbuid");
    $relationshipExists = $this->_db->prepare("SELECT App_FBuid FROM Users_Friends WHERE App_FBuid = $user AND FB_FBuid = :fbuid");

    $addRelationship = $this->_db->prepare("INSERT INTO Users_Friends (App_FBuid, FB_FBuid, MutualFriends) VALUES ($user, :friend, :mfriend)");

    $result = $this->queryRun($query, $access_token);
    foreach ($result['data'] as $friend ) {
      $isInDB->execute(array('fbuid' => $friend['uid']));
      if(($t = $isInDB->fetch(PDO::FETCH_COLUMN, 0)) && true) {
        //echo "<p>".print_r($friend)."</p>";
      } else {
       $addFriend->execute(array('fbuid' => $friend['uid'], 'name' => $this->exists($friend['name']), 'fcount' => $this->exists($friend['friend_count']), 'pcount' => $this->exists($friend['wall_count']), 
                                  'sex' => $this->exists($friend['sex']), 'birthday' => $this->dateFQLtoSQL($this->exists($friend['birthday'])), 'picture' => $this->exists($friend['pic_big']), 'ccountry' => $this->exists($friend['current_location']['country']), 
                                  'ccity' => $this->exists($friend['current_location']['city']), 'ocountry' => $this->exists($friend['hometown_location']['country']), 'company' => $this->exists($friend['work'][0]['employer']['name']), 
                                  'school' => $this->exists($friend['education'][0]['school']['name']), 'adduser' => $this->exists($user)));
      }

      $relationshipExists->execute(array('fbuid' => $this->exists($friend['uid'])));
      if(!$relationshipExists->fetchall())
        $addRelationship->execute(array('friend' => $this->exists($friend['uid']), 'mfriend' => $this->exists($friend['mutual_friend_count'])));
    }
  }

  //Pas utilisée
  //Compte les pages aimées par les amis
  public function topPages($user, $access_token) {
    $likeInsert = $this->_db->prepare('INSERT INTO Likes (FBuid, FBpid) VALUES (:uid, :pid)');
    $likeExists = $this->_db->prepare('SELECT FBpid FROM Likes WHERE (FBuid = :uid AND FBpid = :pid)');
    $listFriendsIDSQL = $this->_db->prepare('SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user); 

    $listLikesFQL = 'SELECT page_id FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid = '; //ne pas oublier de fermer la paranthèse dans la requete finale
    
    $listFriendsIDSQL->execute();
    $listFriendsID = $listFriendsIDSQL->fetchall(PDO::FETCH_COLUMN, 0);

    foreach ($listFriendsID as $friend) {
      $listLikes = $this->queryRun($listLikesFQL.$friend.')', $access_token);
      foreach ($listLikes['data'] as $like) {
        $likeExists->execute(array('uid' => $friend, 'pid' => $like['page_id']));
        if(!$likeExists->fetchall())
          $likeInsert->execute(array('uid' => $friend, 'pid' => $like['page_id']));
      }
    }

    echo "done";
  }

  //Pas utilisée
  //Détermine quels sont les amis qui aiment le plus les posts sur son mur
  public function top10likes($user, $access_token, $facebook) {
    $insert = $this->_db->prepare('UPDATE Users SET Top10 = :top WHERE FBuid = '.$user);

    $statut = $facebook->api('/me?fields=feed.fields(likes.limit(9999).fields(id,name))',array('access_token'=>$access_token));

    $paging = $statut["feed"]["paging"];
    $next = $paging["next"];
    $next = $next.'&access_token='.$access_token;  
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $next);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_COOKIESESSION, true); 
    $return = curl_exec($curl);
    curl_close($curl);
    
    $return_array = json_decode($return, true);

    $result = array_merge($statut['feed']['data'], $return_array['data']);



    // While there is still data in response
    while (array_key_exists("paging", $return_array))
    {
        $paging = $return_array["paging"];
        $next = $paging["next"];

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $next);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_COOKIESESSION, true); 
      $return = curl_exec($curl);
      curl_close($curl);
      
      $return_array = json_decode($return, true);

      $result = array_merge($result, $return_array['data']);
    }
    print_r($result);
    foreach($result as $li3)
    {
      foreach($li3 as $li4)
      {
        foreach($li4 as $li5)
        {
          foreach($li5 as $likes) {
            $like[]=$likes["id"];
          }
        }
      }
    }


    $likes= array_count_values($like);
    $likes[$user] = 0;
    arsort($likes);
    $likes = array_slice($likes, 0, 10, true);

    foreach($likes as $key => $value)
    {
      $list .= $key.'_'.$value.'-';
    }

    $insert->execute(array('top' => $list));
    echo json_encode($stat_likes);
  }
  
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}