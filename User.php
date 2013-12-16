<?php
class User
{
  private $_uid;
  private $_name;
  private $_fbuid;
  private $_friendcount;
  private $_postcount;
  private $_picture;
  private $_accesstoken;
  

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
   // Liste des getters
  public function uid()
  {
    return $this->_uid;
  }
  
  public function name()
  {
    return $this->_name;
  }
  
  public function fbuid()
  {
    return $this->_fbuid;
  }
  
  public function friendcount()
  {
    return $this->_friendcount;
  }
   public function postcount()
  {
    return $this->_postcount;
  }
  
  public function picture()
  {
    return $this->_picture;
  }
  public function accesstoken()
  {
    return $this->_accesstoken;
  }

  // Constructeur
  public function __construct($array) {
    $this->setName($array['name']); 
    $this->setFBuid($array['uid']); 
    $this->setPicture($array['pic_big']);
    $this->setFriendCount($array['friend_count']);
    $this->setPostCount($array['wall_count']);
  }

  // Liste des setters
  public function setUid($uid)
  {
    $id = (int) $id;  
    if ($id > 0)
    {
      $this->_uid = $id;
    }
  }
  
  public function setName($name)
  {
    if (is_string($name))
    {
      $this->_name = $name;
    }
  }
  
  public function setFBuid($fbuid)
  {
    $fbuid = (int) $fbuid;
    
      if ($fbuid > 0)
    {
      $this->_fbuid = $fbuid;
    }
  }
  
  public function setFriendCount($friendcount)
  {
    $friendcount = (int) $friendcount;
    
    if ($friendcount >= 0 )
    {
      $this->_friendcount = $friendcount;
    }
  }
    public function setPostCount($postcount)
  {
    $postcount = (int) $postcount;
    
    if ($postcount >= 0 )
    {
      $this->_postcount = $postcount;
    }
  }
  public function setPicture($picture)
  {
    if (is_string($picture))
    {
      $this->_picture = $picture;
    }
  }
  
  public function setAccesToken($accesstoken)
  {
    $accesstoken = (int) $accesstoken;
    
    if ($accesstoken > 0)
    {
      $this->_accesstoken = $accesstoken;
    }
  }

  
}