<?php
  require_once "Account.class.php";
  require_once "DebitCard.class.php";
  
  //include "DebitCard.class.php";
  
  $debit = new DebitCard();
  $debit->setStatus("true");
  
  $account = new Account($debit);
  $account->setName("Sammy");
  
  echo "Account : ".$account->getName()." can create debit ".$account->getDebitStatus();
  
  
?>