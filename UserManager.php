<?php
 include ('Toolbox.php');
class UserManager extends Toolbox
{
  private $_db; // Instance de PDO
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
 /* public function add(User $user)
  {
    // Préparation de la requête d'insertion.
    $listUserDB = $this->_db->prepare("SELECT FBuid from Users WHERE FBuid = :fbuid");
    $addUser = $this->_db->prepare("INSERT INTO Users (FBuid, Name, FriendCount, PostCount, Picture) VALUES (:fbuid, :name, 120, 120,:picture)");  
    $listUserDB->execute(array('fbuid' => $user->fbuid()));
    if($already = $listUserDB->fetch(PDO::FETCH_COLUMN, 0)) {
      echo "inscrit";
    } else {
      echo "ajoute";
      // Exécution de la requête.
      $addUser->bindValue(':fbuid', $user->fbuid(), PDO::PARAM_INT);
      $addUser->bindValue(':name', $user->name());
      $addUser->bindValue(':picture', $user->picture());
      $addUser->execute();
    }
  }*/
  public function add($access_token){
    
    $meFB = "SELECT name,uid,friend_count,wall_count,pic_big FROM user WHERE uid=me()";
    $listUserDB = $this->_db->prepare("SELECT FBuid from Users WHERE FBuid = :fbuid");
    $addUser = $this->_db->prepare("INSERT INTO Users (FBuid, Name, FriendCount, PostCount, Picture) VALUES (:fbuid, :name, :friendcount, :postcount, :picture)");
    $result = $this -> queryRun($meFB, $access_token)['data'][0];
    //$user_bdd = new User($result);
    $listUserDB->execute(array('fbuid' => $result['uid']));
    if($already = $listUserDB->fetch(PDO::FETCH_COLUMN, 0)) {
      echo "inscrit";
    } else {
      echo "ajoute";
      $addUser->execute(array('fbuid' => $result['uid'], 'name' => $result['name'], 'friendcount' => $result['friend_count'],'postcount' => $result['wall_count'], 'picture' => $result['pic_big']));
    }
    return $result;
  }
  function sdf($user, $access_token) {
    $query = 'SELECT uid,name,mutual_friend_count,education.school,current_location.city,current_location.country,hometown_location.country,pic_big,sex,work.employer,likes_count,friend_count,sex,wall_count,birthday FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me())';
    $addFriend = $this->_db->prepare("INSERT INTO Friends (FBuid, Name, FriendCount, PostCount, Sex, Birthday, Picture,CurrentCountry, CurrentCity, OriginCountry, WorkCompany, School, AddUser) ".
                              "VALUES (:fbuid, :name, :fcount, :pcount, :sex, :birthday, :picture, :ccountry, :ccity, :ocountry, :company, :school, :adduser)");
    $modifyFriend = $this->_db->prepare("UPDATE Friends SET FriendCount = :fcount, PostCount = :pcount, CurrentCountry = :ccountry, CurrentCity = :ccity, WorkCompany = :company, School = :school, UpdateDate = :udate WHERE FBuid = :fbuid");
    $isInDB = $this->_db->prepare("SELECT FBuid FROM Friends WHERE FBuid = :fbuid");

    $addRelationship = $this->_db->prepare("INSERT INTO App_FB_Users (App_FBuid, FB_FBuid, MutualFriends) VALUES ($user, :friend, :mfriend)");

    $result = $this->queryRun($query, $access_token);
    foreach ($result['data'] as $friend ) {
      $isInDB->execute(array('fbuid' => $friend['uid']));
      if(($t = $isInDB->fetch(PDO::FETCH_COLUMN, 0)) && true) {
        echo "<p>".print_r($friend)."</p>";
      } else {
       $addFriend->execute(array('fbuid' => $friend['uid'], 'name' => $this->exists($friend['name']), 'fcount' => $this->exists($friend['friend_count']), 'pcount' => $this->exists($friend['wall_count']), 
                                  'sex' => $this->exists($friend['sex']), 'birthday' => $this->dateFQLtoSQL($this->exists($friend['birthday'])), 'picture' => $this->exists($friend['pic_big']), 'ccountry' => $this->exists($friend['current_location']['country']), 
                                  'ccity' => $this->exists($friend['current_location']['city']), 'ocountry' => $this->exists($friend['hometown_location']['country']), 'company' => $this->exists($friend['work'][0]['employer']['name']), 
                                  'school' => $this->exists($friend['education'][0]['school']['name']), 'adduser' => $this->exists($user)));
      }
      $addRelationship->execute(array('friend' => $this->exists($friend['uid']), 'mfriend' => $this->exists($friend['mutual_friend_count'])));
   
    }
  }
  public function count()
  {
    return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
  }
  
  public function delete(Personnage $perso)
  {
    $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
  }
  
 /* public function exists($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
    }
    
    // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
    
    $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
    $q->execute(array(':nom' => $info));
    
    return (bool) $q->fetchColumn();
  }*/
  
  public function get($info)
  {
    if (is_int($info))
    {
      $q = $this->_db->query('SELECT id, nom, degats FROM personnages WHERE id = '.$info);
      $donnees = $q->fetch(PDO::FETCH_ASSOC);
      
      return new Personnage($donnees);
    }
    else
    {
      $q = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom = :nom');
      $q->execute(array(':nom' => $info));
    
      return new Personnage($q->fetch(PDO::FETCH_ASSOC));
    }
  }
  
  public function getList($nom)
  {
    $persos = array();
    
    $q = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom <> :nom ORDER BY nom');
    $q->execute(array(':nom' => $nom));
    
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      $persos[] = new Personnage($donnees);
    }
    
    return $persos;
  }
  
  public function update(Personnage $perso)
  {
    $q = $this->_db->prepare('UPDATE personnages SET degats = :degats WHERE id = :id');
    
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);
    
    $q->execute();
  }
  
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}