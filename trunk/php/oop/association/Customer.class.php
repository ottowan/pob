<?php
  class Customer {
    //Attribute
    private $id;
    private $name;
    private $address;
    private $phone;

    //Association attribute
    private $busBooking;

    //Method
    public function setName($name){
      $this->name = $name;
    }

    public function getName(){
      return $this->name;
    }

    public function confirmBookingBus(){
      $this->busBooking = new BusBooking();
      $this->busBooking->confirm();
    }

    public function cancelBookingBus(){
      $this->busBooking = new BusBooking();
      $this->busBooking->cancel();
    }
  }


?>