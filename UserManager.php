<?php
class UserManager
{
  private $_db; // Instance de PDO
  
  public function __construct($db)
  {
    $this->setDb($db);
  }
  
  public function add(User $user)
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
  }
  
  public function count()
  {
    return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
  }
  
  public function delete(Personnage $perso)
  {
    $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
  }
  
  public function exists($info)
  {
    if (is_int($info)) // On veut voir si tel personnage ayant pour id $info existe.
    {
      return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
    }
    
    // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
    
    $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
    $q->execute(array(':nom' => $info));
    
    return (bool) $q->fetchColumn();
  }
  
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