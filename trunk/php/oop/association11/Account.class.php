<?php
  require_once "DebitCard.class.php";
  class Account {
    //Attribute
    private $name;
    private $debitCard;

    function __construct($debitCard){
      $this->debitCard = new DebitCard();
      $this->debitCard = $debitCard;
    }
    
    //Method
    public function getName(){
      return $this->name;
    }
    
    public function setName($name){
      $this->name = $name;
    }
    
    public function getDebitStatus(){
      return $this->debitCard->getStatus();
    }
    
  }


?>