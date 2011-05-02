<?php
  include "Player.class.php";
  class Team {
    //Attribute
    private $name;
    private $ranking;
    private $player;
    private $allPlayer = array();
    
    function __construct(){
      $this->player = new Player();
    }
    
    //Method
    public function getName(){
      return $this->name;
    }
    
    //Method
    public function setName($name){
      $this->name = $name;
    }
    
    //Method
    public function getPlayerName(){
      return $this->player->getName();
    }
    
    //Method
    public function setPlayerName($name){
      return $this->player->setName($name);
    }
    //Method
    public function getAllPlayerName(){
      return $this->player->getAllName();
    }
  }
?>