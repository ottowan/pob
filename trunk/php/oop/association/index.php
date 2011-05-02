<?php

  include "Customer.class.php";
  include "BusBooking.class.php";

  $customer = new Customer();
  $customer->setName("Sam Noob");
  echo $customer->getName()." has made ";
  $customer->confirmBookingBus();

?>