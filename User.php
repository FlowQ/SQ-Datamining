<?php
class User
{
  private $_uid;
  private $_name;
  private $_fbuid;
  private $_friendcount;
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
  
  public function picture()
  {
    return $this->_picture;
  }
  public function accesstoken()
  {
    return $this->_accesstoken;
  }
  // Constructeur
  public function __construct($array) // Constructeur demandant 2 paramètres
  {
     // Message s'affichant une fois que tout objet est créé.
    $this->setName($array['name']); 
    $this->setFBuid($array['fbuid']); // Initialisation de la force.
    // Initialisation des dégâts.
    $this->setPicture($array['picture']);
    // Initialisation de l'expérience à 1.
  }

  // Liste des setters
  
  public function setUid($uid)
  {
    // On convertit l'argument en nombre entier.
    // Si c'en était déjà un, rien ne changera.
    // Sinon, la conversion donnera le nombre 0 (à quelques exceptions près, mais rien d'important ici).
    $id = (int) $id;
    
    // On vérifie ensuite si ce nombre est bien strictement positif.
    if ($id > 0)
    {
      // Si c'est le cas, c'est tout bon, on assigne la valeur à l'attribut correspondant.
      $this->_uid = $id;
    }
  }
  
  public function setName($name)
  {
    // On vérifie qu'il s'agit bien d'une chaîne de caractères.
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
      // Si c'est le cas, c'est tout bon, on assigne la valeur à l'attribut correspondant.
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