<?php
  include "Team.class.php";
  
  $team = new Team();
  
  $team->setName("Manchester United");
  $team->setPlayerName("Chicharito");
  
  
  echo $team->getName()."<br>";
  echo $team->getPlayerName()."<br>";

  
?>