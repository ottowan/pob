<?php

  require_once "Room.class.php";
  require_once "Facility.class.php";
  
  class RoomFacility {
    private $room;
    private $facility;
    private $roomFacility = array();
    private $rooms = array();
    private $facilities = array();
    
    function __construct(){
      $this->room = new Room();
      $this->facility = new Facility();
    }  
    

    public function addRoomFacility($room, $facility){
      //Add Room object
      $this->room = $room;
      
      $this->rooms[] = $this->room;
      
      //Add Facility object
      $this->facility = $facility;
      $this->facilities[] = $this->facility;
      
      $this->roomFacility[room] = $this->rooms;
      $this->roomFacility[facility] = $this->facilities;
    }



    public function readFacility(){
      //Add Room object
      return $this->rooms;
    }
    

    public function readRoom(){
      return $this->facilities;
    }
    
    public function readAllRoomFacility(){
      return $this->roomFacility;
    }
  }


?>