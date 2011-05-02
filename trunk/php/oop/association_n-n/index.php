<?php
  require_once "Room.class.php";
  require_once "Facility.class.php";
  require_once "RoomFacility.class.php";
  
  $r1 = new Room();
  $f1 = new Facility();
  $r2 = new Room();
  $f2 = new Facility();
  $r3 = new Room();
  $f3 = new Facility();
  
  $rf = new RoomFacility();
  
  $r1->setName("Suprerior");
  $r1->setNumber("101");
  $f1->setName("pool");
  $rf->addRoomFacility($r1, $f1);
  
  $r2->setName("Suprerior");
  $r2->setNumber("102");
  $f2->setName("Smoking");
  $rf->addRoomFacility($r2, $f2);
  
  $r3->setName("Suprerior");
  $r3->setNumber("102");
  $f3->setName("Seaview");
  $rf->addRoomFacility($r3, $f3);
  
  var_dump($rf->readFacility());
  var_dump($rf->readRoom());
  

  /*
  $room->setName("Supreme");
  $facility->setName("Pool");
  $roomFacility->addRoomFacility($room, $facility);
  var_dump($roomFacility->readFacility());
  var_dump($roomFacility->readRoom());
  
  $room->setName("Supreme");
  $facility->setName("Smoking");
  $roomFacility->addRoomFacility($room, $facility);
  var_dump($roomFacility->readFacility());
  var_dump($roomFacility->readRoom());
*/
?>