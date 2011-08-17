<?php
  function POBBooking_pntables() {
      // Initialise table array
      $pntable = array();

      $customer = createPOBBookingCustomerTable();
      $booking  = createPOBBookingTable();
      $status   = createPOBBookingStatusTable();

      $pntable = array_merge($customer, $booking, $status);
      //$pntable = array_merge($customer, $booking);
      unset($customer);
      unset($booking);
      unset($status);

      return $pntable;
  }

  /**
   * This function is create booking table.
   * It use for adds in the information of bookings.
   */
  function &createPOBBookingCustomerTable() {

      //create level 2
      //table definition booking customr
      $pntable['pobbooking_customer'] = DBUtil::getLimitedTablename('pobbooking_customer');
      $pntable['pobbooking_customer_column'] = array(
                                                  'id'                  => 'cus_id',
                                                  'refid'               => 'cus_refid',
                                                  'status_id'           => 'cus_status_id',
                                                  'identificational'    => 'cus_identificational',
                                                  'nameprefix'          => 'cus_nameprefix',
                                                  'givenname'           => 'cus_givenname',
                                                  'surname'             => 'cus_surname',
                                                  'addressline'         => 'cus_addressline',
                                                  'cityname'            => 'cus_cityname',
                                                  'stateprov'           => 'cus_stateprov',
                                                  'countryname'         => 'cus_countryname',
                                                  'postalcode'          => 'cus_postalcode',
                                                  'mobile'              => 'cus_mobile',
                                                  'phone'               => 'cus_phone',
                                                  'email'               => 'cus_email',
                                                  'total_rooms'         => 'cus_total_rooms',
                                                  'total_price'         => 'cus_total_price',
                                                  'cardcode'            => 'cus_cardcode',
                                                  'cardnumber'          => 'cus_cardnumber',
                                                  'cardholdername'      => 'cus_cardholdernamer',
                                                  'cardexpire'          => 'cus_cardexpire'
                                                  );

      $pntable['pobbooking_customer_column_def'] = array(
                                                  'id'                  => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                                  'refid'               => 'TEXT',
                                                  'status_id'           => 'INT(2) DEFAULT 2',
                                                  'identificational'    => 'VARCHAR(50) DEFUALT NULL',
                                                  'nameprefix'          => 'VARCHAR(50) DEFUALT NULL',
                                                  'givenname'           => 'TEXT',
                                                  'surname'             => 'TEXT',
                                                  'addressline'         => 'TEXT',
                                                  'stateprov'           => 'VARCHAR(255) DEFUALT NULL',
                                                  'cityname'            => 'VARCHAR(255) DEFUALT NULL',
                                                  'countryname'         => 'VARCHAR(255) DEFUALT NULL',
                                                  'postalcode'          => 'VARCHAR(50)  DEFUALT NULL',
                                                  'mobile'              => 'VARCHAR(255) DEFUALT NULL',
                                                  'phone'               => 'VARCHAR(255) DEFUALT NULL',
                                                  'email'               => 'TEXT',
                                                  'email'               => 'TEXT',
                                                  'total_rooms'         => 'INT(11)',
                                                  'total_price'         => 'DOUBLE',
                                                  'cardcode'            => 'VARCHAR(255)',
                                                  'cardnumber'          => 'TEXT',
                                                  'cardholdername'      => 'TEXT',
                                                  'cardexpire'          => 'VARCHAR(10)'
                                                  );
      $pntable['pobbooking_customer_primary_key_column'] = 'id';
      // add standard data fields
      ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobbooking_customer_column'], 'cus_');
      ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobbooking_customer_column_def']);

      return $pntable;
  }


  function &createPOBBookingTable() {

      //create level 2
      //table definition booking
      $pntable['pobbooking_booking'] = DBUtil::getLimitedTablename('pobbooking_booking');

      $pntable['pobbooking_booking_column'] = array(
                                                  'id'                  => 'boo_id',
                                                  'cus_id'              => 'boo_cus_id',
                                                  'customer_refid'      => 'boo_customer_refid',
                                                  'status_id'           => 'boo_status_id',
                                                  'chaincode'           => 'boo_chaincode',
                                                  'hotelname'           => 'boo_hotelname',
                                                  'isocurrency'         => 'boo_isocurrency',
                                                  'checkin_date'        => 'boo_checkin_date',
                                                  'checkout_date'       => 'boo_checkout_date',
                                                  'night'               => 'boo_night',
                                                  'amount_room'         => 'boo_amount_room',
                                                  'roomtype'            => 'boo_roomtype',
                                                  'adult'               => 'boo_adult',
                                                  'child'               => 'boo_child',
                                                  'room_rate'           => 'boo_room_rate',
                                                  'room_rate_total'     => 'boo_room_rate_total',
                                                  'identificational'    => 'boo_identificational',
                                                  'nameprefix'          => 'boo_nameprefix',
                                                  'givenname'           => 'boo_givenname',
                                                  'surname'             => 'boo_surname',
                                                  'addressline'         => 'boo_addressline',
                                                  'cityname'            => 'boo_cityname',
                                                  'stateprov'           => 'boo_stateprov',
                                                  'countryname'         => 'boo_countryname',
                                                  'postalcode'          => 'boo_postalcode',
                                                  'mobile'              => 'boo_mobile',
                                                  'phone'               => 'boo_phone',
                                                  'email'               => 'boo_email',
                                                  'addition_request'    => 'boo_addition_request',
                                                  'cardcode'            => 'boo_cardcode',
                                                  'cardnumber'          => 'boo_cardnumber',
                                                  'cardholdername'      => 'boo_cardholdernamer',
                                                  'cardexpire'          => 'boo_cardexpire',
                                                  'issue_date'          => 'boo_issue_date'
                                          ); 

      $pntable['pobbooking_booking_column_def'] = array(
                                                  'id'               => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY', 
                                                  'cus_id'           => 'INT(11)', 
                                                  'customer_refid'   => 'TEXT',
                                                  'status_id'        => 'INT(2) default 2',
                                                  'chaincode'        => 'VARCHAR(10)',
                                                  'hotelname'        => 'VARCHAR(255)',
                                                  'isocurrency'      => 'VARCHAR(10)',
                                                  'checkin_date'     => 'DATE',
                                                  'checkout_date'    => 'DATE',
                                                  'night'            => 'INT(2)',
                                                  'amount_room'      => 'INT(2)',
                                                  'roomtype'         => 'VARCHAR(255)',
                                                  'adult'            => 'INT(2)',
                                                  'child'            => 'INT(2)',
                                                  'room_rate'        => 'DOUBLE',
                                                  'room_rate_total'  => 'DOUBLE',
                                                  'identificational' => 'VARCHAR(50) DEFUALT NULL',
                                                  'nameprefix'       => 'VARCHAR(50) DEFUALT NULL',
                                                  'givenname'        => 'TEXT',
                                                  'surname'          => 'TEXT',
                                                  'addressline'      => 'TEXT',
                                                  'stateprov'        => 'VARCHAR(255) DEFUALT NULL',
                                                  'cityname'         => 'VARCHAR(255) DEFUALT NULL',
                                                  'countryname'      => 'VARCHAR(255) DEFUALT NULL',
                                                  'postalcode'       => 'VARCHAR(50)  DEFUALT NULL',
                                                  'mobile'           => 'VARCHAR(255) DEFUALT NULL',
                                                  'phone'            => 'VARCHAR(255) DEFUALT NULL',
                                                  'email'            => 'TEXT',
                                                  'addition_request' => 'TEXT',
                                                  'cardcode'         => 'VARCHAR(255)',
                                                  'cardnumber'       => 'TEXT',
                                                  'cardholdername'   => 'TEXT',
                                                  'cardexpire'       => 'VARCHAR(10)',
                                                  'issue_date'       => 'DATETIME'
                                                                      );
      $pntable['pobbooking_booking_primary_key_column'] = 'id';

      return $pntable;

  }

  /**
   * This function is create booking table.
   * It use for adds in the information of bookings.
   */
 
  function &createPOBBookingStatusTable() {

      //create level 2
      //table definition booking customr
      $pntable['pobbooking_status'] = DBUtil::getLimitedTablename('pobbooking_status');
      $pntable['pobbooking_status_column'] = array(
                                                  'id'               => 'sta_id',
                                                  'name'             => 'sta_name'
                                             );

      $pntable['pobbooking_status_column_def'] = array(
                                                      'id'           => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                                      'name'         => 'VARCHAR(50)'
                                                  );
      $pntable['pobbooking_status_primary_key_column'] = 'id';

      // add standard data fields
      ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobbooking_status_column'], 'sta_');
      ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobbooking_status_column_def']);

      return $pntable;
  }


?>