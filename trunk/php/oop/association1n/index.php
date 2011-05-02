<?php
  require_once "Team.class.php";
  require_once "Player.class.php";
  
  $team = new Team();
  
  $team->setName("Manchester United");

  $rooney = new Player();
  $rooney->setName("Wayne Rooney");
  $rooney->setNumber("10");
  $team->addPlayer($rooney);
  
  $chicha = new Player();
  $chicha->setName("Javier Hernandez");
  $chicha->setNumber("14");
  $team->addPlayer($chicha);
  
  $messi = new Player();
  $messi->setName("messi");
  $messi->setNumber("12");
  $team->addPlayer($messi);
  
  $playerArray = $team->readAllPlayer();
  var_dump($playerArray);

/*
  echo $team->getName()."<br>";
  echo $team->getPlayerName()."<br>";

*/

  
?>