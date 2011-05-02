<?php

  class Player {
    //Attribute
    private $name;
    private $number;

    //Method
    public function getName(){
      return $this->name;
    }
    
    public function setName($name){
      $this->name = $name;
    }

    public function getNumber(){
      return $this->number;
    }
    
    public function setNumber($number){
      $this->number = $number;
    }
  }


?>