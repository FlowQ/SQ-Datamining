<?php
require_once('Toolbox.php');
class User extends Toolbox
{

  private $_db;
  

   // Function d'hydratation 
  public function hydrate(array $donnees)
  {
    foreach ($donnees as $key => $value)
    {
      // On récupère le nom du setter correspondant à l'attribut.
      $method = 'set'.ucfirst($key);
          
      // Si le setter correspondant existe.
      if (method_exists($this, $method))
      {
        // On appelle le setter.
        $this->$method($value);
      }
    }
  }

  //OK
  public function infoUser($user, $access_token) {
    $meSQL = $this->_db->prepare('SELECT * FROM Users WHERE FBuid = '.$user);
    $meSQL->execute();

    $me = $meSQL->fetch();
    return $me;
  }

  public function sex($user, $access_token) {  
    $listMaleSQL = $this->_db->prepare("SELECT Count(FBuid) FROM Friends WHERE Sex = 'male' AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = $user)");
    $listFemaleSQL = $this->_db->prepare("SELECT Count(FBuid) FROM Friends WHERE Sex = 'female' AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = $user)");
    $listMaleSQL->execute();
    $listFemaleSQL->execute();

    $male= $listMaleSQL->fetch(PDO::FETCH_COLUMN, 0);
    $female = $listFemaleSQL->fetch(PDO::FETCH_COLUMN, 0);

    //print_r($result);
    $gender[]=array("Femelle", (int)$female);
    $gender[]=array("Male", (int)$male);

    echo json_encode($gender);
    
  }
  public function countRelationStatus($list) {
    $single=0;
    $not_single=0;
    $open_relationship=0;
    $domestic_relationship=0;
    $married=0;
    $engaged=0;
    $widow=0;
    $complicated=0;
    $divorced=0;
    $separated=0;
    $civil_union=0;
    $couple = array();
    $singleList = array();
    $relationship=array();

    foreach ($list as $friend) {
      if($friend["relationship_status"]=="Single")
      {
        $single++;
        $singleList[] = array($friend['name'], $friend['sex']);
      }
      else if($friend["relationship_status"]=="In a relationship"){
        $not_single++;
      }
      else if($friend["relationship_status"]=="In an open relationship"){
        $open_relationship++;
      }
      else if($friend["relationship_status"]=="Married"){
        $married++;
      }
      else if($friend["relationship_status"]=="Engaged"){
        $engaged++;
      }
      else if($friend["relationship_status"]=="In a domestic partnership"){
        $domestic_relationship++;
      }
      else if($friend["relationship_status"]=="Widowed"){
        $widow++;
      }
      else if($friend["relationship_status"]=="It's complicated"){
        $complicated++;
      }
      else if($friend["relationship_status"]=="Divorced"){
        $divorced++;
      }
      else if($friend["relationship_status"]=="Separated"){
        $separated++;
      }
      else if($friend["relationship_status"]=="In a civil union"){
        $civil_union++;
      }

      if($friend['significant_other_id']) {
        $couple[] = array($friend['uid'], $friend['significant_other_id']);
      }
    }
    $relationship['graph'][]=array("Single", (int)$single);
    $relationship['graph'][]=array("In a relationship", (int)$not_single);
    $relationship['graph'][]=array("In a open relationship", (int)$open_relationship);
    $relationship['graph'][]=array("Married", (int)$married);
    $relationship['graph'][]=array("Engaged", (int)$engaged);
    $relationship['graph'][]=array("In a domestic partnership", (int)$domestic_relationship);
    $relationship['graph'][]=array("It's complicated", (int)$complicated);
    $relationship['graph'][]=array("Divorced", (int)$divorced);
    $relationship['graph'][]=array("Separated", (int)$separated);
    $relationship['graph'][]=array("In a civil union", (int)$civil_union);
    $relationship['graph'][]=array("Widowed", (int)$widow);
    $relationship['lists']['couples']=$couple;
    $relationship['lists']['singles']=$singleList;

    return $relationship;
  }

  public function couplesSingles($user, $access_token) {
    $listRelationshipsFQL = "SELECT uid, relationship_status, significant_other_id, name, sex FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me()) AND relationship_status";
    $getNameCouplesFQL = "SELECT name, uid FROM user WHERE uid IN";

    $return = array();
    $result = $this->queryRun($listRelationshipsFQL, $access_token);

    //compte les relations par type et retourne les listes de couples et les celibataires
    $result = $this->countRelationStatus($result['data']);
    $return['graph'] = $result['graph'];
    $return['lists']['singles'] = $result['lists']['singles'];
    //determine les noms des couples
    $start = microtime(true);

    $list = " (";
    foreach ($result['lists']['couples'] as $couple) {
      $list .= $couple[0].", ".$couple[1].", ";   //cree la liste des id a requeter
    }
    $list .= "0)";
    $get = $this->queryRun($getNameCouplesFQL.$list, $access_token); //requete qui retourne, les noms des couples dans le bon ordre !

    $listCouples = array();
    $i = 0;
    foreach ($result['lists']['couples'] as $user) {  //boucle pour faire correspondre les noms aux ID
      $nom1 = $get['data'][$i++]['name'];
      $nom2 = $get['data'][$i++]['name'];
      $listCouples[] = array($nom1, $nom2);
    }

    $return['lists']['couples'] = $listCouples;
    return $return;
  }
  public function listSchools($user, $access_token) {
    $listSchoolsSQL = $this->_db->prepare('SELECT School FROM Friends WHERE School is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
    $listSchoolsSQL->execute();
    $listSchools = $listSchoolsSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listSchools);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      if($value > 1)
        $result[] = array($key, $value);
    }
    return $result;
  }

  public function listCompanys($user, $access_token) {
    $listCompanysSQL = $this->_db->prepare('SELECT WorkCompany FROM Friends WHERE WorkCompany is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
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
  public function semaine() {
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
  //OK
  public function sevenDaysBirthdays($user, $access_token) {
    $listBirthdaysSQL = $this->_db->prepare('SELECT Birthday,Name FROM Friends WHERE Birthday is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
    $listBirthdaysSQL->execute();
    $listDates = $listBirthdaysSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $listBirthdaysSQL->execute();
    $listName = $listBirthdaysSQL->fetchall(PDO::FETCH_COLUMN, 1);
    $listSemaine = $this->semaine();

    $return = array();
    foreach ($listSemaine as $jour) {
      for($i = 0 ; $i<count($listDates) ; $i++) {
        if(strpos($listDates[$i], $jour)) {
          $return[] = array($listName[$i], $listDates[$i]);
        }
      }
    }
    if(count($return)>0 && count($return) < 2) {
      echo "<p>Cette semaine ne pas oublier l'anniversaire de ".$return[0][0].", né(e) le ".$return[0][1]."</p>";
    } elseif (count($return) > 1) {
      echo "<p>Cette semaine ce sont les anniversaires de :</p>";
      foreach ($return as $ami) {
        echo "<p>* ".$ami[0].", né(e) le ".$ami[1]."</p>";
      }
    }

  }
  //OK
  public function monthsBirthdays($user, $access_token) {
    $listBirthdaysSQL = $this->_db->prepare('SELECT Birthday FROM Friends WHERE Birthday is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
    $listBirthdaysSQL->execute();
    $listBirthdays = $listBirthdaysSQL->fetchall(PDO::FETCH_COLUMN, 0);
    $result = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
    foreach ($listBirthdays as $friend) {
      $month = $friend[5].$friend[6];
      $result[$month]++;
    }
    //print_r($result);
    //echo $result['01'];
    $stat_age[]=array('name'=>"Janvier", 'data' =>array((int)$result['01']));
    $stat_age[]=array('name'=>"Février", 'data' =>array((int)$result['02']));
    $stat_age[]=array('name'=>"Mars", 'data' =>array((int)$result['03']));
    $stat_age[]=array('name'=>"Avril", 'data' =>array((int)$result['04']));
    $stat_age[]=array('name'=>"Mai", 'data' =>array((int)$result['05']));
    $stat_age[]=array('name'=>"Juin", 'data' =>array((int)$result['06']));
    $stat_age[]=array('name'=>"Juillet", 'data' =>array((int)$result['07']));
    $stat_age[]=array('name'=>"Aout", 'data' =>array((int)$result['08']));
    $stat_age[]=array('name'=>"Septembre", 'data' =>array((int)$result['09']));
    $stat_age[]=array('name'=>"Octobre", 'data' =>array((int)$result['10']));
    $stat_age[]=array('name'=>"Novembre", 'data' =>array((int)$result['11']));
    $stat_age[]=array('name'=>"Décembre", 'data' =>array((int)$result['12']));
    echo json_encode($stat_age);
  }

  public function currentCountry($user, $access_token) {
    $listCountrySQL = $this->_db->prepare('SELECT CurrentCountry FROM Friends WHERE CurrentCountry is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
    $listCountrySQL->execute();
    $listCountry = $listCountrySQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listCountry);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      $result[] = array($key, $value);
    }
    return $result;
  }

  public function originCountry($user, $access_token) {
    $listCountrySQL = $this->_db->prepare('SELECT OriginCountry FROM Friends WHERE OriginCountry is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
    $listCountrySQL->execute();
    $listCountry = $listCountrySQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listCountry);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      $result[] = array($key, $value);
    }
    return $result;
  }

  public function currentCity($user, $access_token) {
    $listCitySQL = $this->_db->prepare('SELECT CurrentCity FROM Friends WHERE CurrentCity is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
    $listCitySQL->execute();
    $listCity = $listCitySQL->fetchall(PDO::FETCH_COLUMN, 0);
    $classe = array_count_values($listCity);
    asort($classe);
    $result = array();
    foreach ($classe as $key => $value) {
      $result[] = array($key, $value);
    }
    return $result;
  }

  //nombre d'amis
  //OK
  public function friendsStats($user, $access_token) {
    $meSQL = $this->_db->prepare("SELECT FriendCount FROM Users WHERE FBuid = ".$user);
    $listFriendsSQL = $this->_db->prepare('SELECT FriendCount,Name FROM Friends WHERE FriendCount is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
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
    return $stats;
  }

  //nombre de posts sur le mur
  //OK
  public function wallStats($user, $access_token) {
    $meSQL = $this->_db->prepare("SELECT PostCount FROM Users WHERE FBuid = ".$user);
    $listFriendsSQL = $this->_db->prepare('SELECT PostCount,Name FROM Friends WHERE PostCount is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
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
    return $stats; 
  }

  //nombre de posts sur le mur par ami
  //OK
  public function ratioPostFriend($user, $access_token) {
    $meSQL = $this->_db->prepare("SELECT PostCount/FriendCount FROM Users WHERE FBuid = ".$user);
    $listFriendsSQL = $this->_db->prepare('SELECT PostCount/FriendCount,Name FROM Friends WHERE FriendCount is not null AND PostCount is not null AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
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
   return $stats; 
  }

  //Pas utilisée
  //cree un top50 des pages likees par les amis que le user ne like pas
  //prend environ 2 minutes 
  public function listLikes($user, $access_token) {
    $listMyLikesFQL = 'SELECT page_id FROM page WHERE page_id IN (SELECT page_id FROM page_fan WHERE uid = me())';
    $likeName = "SELECT name, page_id FROM page WHERE page_id IN (";

    $listFriendsIDSQL = $this->_db->prepare('SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user); 
    $listLikesSQL = $this->_db->prepare('SELECT FBpid FROM Likes WHERE FBuid = :uid');

    $listTotale = array();

    $listFriendsIDSQL->execute();
    $listFriendsID = $listFriendsIDSQL->fetchall(PDO::FETCH_COLUMN, 0);

    foreach ($listFriendsID as $friend) {
      $listLikesSQL->execute(array('uid' => $friend));
      $result = $listLikesSQL->fetchall(PDO::FETCH_COLUMN, 0);
      $listTotale = array_merge($listTotale, $result);
    }

    $listCount = array_count_values($listTotale);

    $listMyLikes = $this->queryRun($listMyLikesFQL, $access_token);

    foreach ($listMyLikes['data'] as $myLike) {
      $listCount[$myLike['page_id']] = 0;
    }

    asort($listCount);

    $top50 = array_slice($listCount, -50, 50, true);

    foreach ($top50 as $index => $value) {
      $likeName .= $index.", ";
    }
    $likeName .= '0)';
    $names = $this->queryRun($likeName, $access_token);

    $result = array();
    foreach ($names['data'] as $name) {
      $result[] = array($name['name'], $name['page_id'], $top50[$name['page_id']]);
    }

    print_r($result);
  }

  //Pas utilisée
  public function sameCountry($user, $access_token) {  
    $listSQL = $this->_db->prepare("SELECT Name FROM Friends WHERE OriginCountry = CurrentCountry AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = $user)");
    $listSQL->execute();

    $list = $listSQL->fetchall(PDO::FETCH_COLUMN, 0);

    print_r($list);
  }

  //Pas utilisée
  public function age($user, $access_token) {
    $listSQL = $this->_db->prepare("SELECT Birthday FROM Friends WHERE Birthday AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = $user)");
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

  //Pas utilisée
  public function pictureWall($user, $access_token) {
    $listPictSQL = $this->_db->prepare('SELECT Picture FROM Friends WHERE Picture IS NOT NULL AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = '.$user.')');
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

  //Pas utilisée
  public function printTop10($user, $access_token) {
    $listSQL = $this->_db->prepare('SELECT Top10 FROM Users WHERE FBuid = '.$user);
    $nameSQL = $this->_db->prepare('SELECT Name FROM Friends WHERE FBuid = :fbuid');
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

  //Pas utilisée
  public function firstName($user, $access_token) {
    $listSQLmale = $this->_db->prepare("SELECT Name FROM Friends WHERE Sex = 'male' AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = ".$user.')');
    $listSQLfemale = $this->_db->prepare("SELECT Name FROM Friends WHERE Sex = 'female' AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = ".$user.')');
    $listSQLmale->execute();
    $listSQLfemale->execute();
    $list = $listSQLmale->fetchall(PDO::FETCH_COLUMN, 0);
    $result = array();
    foreach ($list as $name) {
      $name_ex = explode(' ', $name);
      $result[] = $name_ex[0];
    }
    $count = array_count_values($result);
    asort($count);
    $count = array_reverse($count);
    $names = array_keys($count);
    echo "<p>".$names[0]." est le prénom masculin le plus commun, avec ".$count[$names[0]]." personnes le portant.</p>";
    $list = $listSQLfemale->fetchall(PDO::FETCH_COLUMN, 0);
    $result = array();
    foreach ($list as $name) {
      $name_ex = explode(' ', $name);
      $result[] = $name_ex[0];
    }
    $count = array_count_values($result);
    asort($count);
    $count = array_reverse($count);
    $names = array_keys($count);
    echo "<p>".$names[0]." est le prénom féminin le plus commun, avec ".$count[$names[0]]." personnes le portant.</p>";
  }

  public function avgSex($user, $access_token) {
    $SQL = $this->_db->prepare("SELECT avg(PostCount),avg(FriendCount) FROM Friends WHERE Sex = :sex AND FBuid IN (SELECT FB_FBuid FROM Users_Friends WHERE APP_FBuid = ".$user.')');
    $sex = array('male', 'female');
    $result = array();
    foreach ($sex as $type) {
      $SQL->execute(array('sex' => $type));
      $res = $SQL->fetch(PDO::FETCH_NUM);
      $result[$type] = array('friends' => floor($res[0]), 'posts' => floor($res[1]));
    }
    if((($result['male']['friends'] >= $result['female']['friends']) && ($result['male']['posts'] >= $result['female']['posts'])) || (($result['male']['friends'] < $result['female']['friends']) && ($result['male']['posts'] < $result['female']['posts']))) {
      echo '<p>En moyenne les hommes ont '.$result['male']['friends'].' amis et les femmes '.$result['female']['friends'].'. Et ils ont '.$result['male']['posts'].' posts sur leur mur alors que les femmes '.$result['female']['posts'].'.</p>';
    } else {
      echo '<p>En moyenne les hommes ont '.$result['male']['friends'].' amis et les femmes '.$result['female']['friends'].'. Seulement elles ont '.$result['female']['posts'].' posts sur leur mur alors que les hommes '.$result['male']['posts'].'.</p>';    
    }
  }

  // Liste des getters
 
  // Constructeur
  public function __construct($db) {
    $this->setDb($db);
  }

  public function setDb(PDO $db) {
    $this->_db = $db;
  }

  
}