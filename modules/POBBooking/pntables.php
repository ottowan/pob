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
                                                  'cardcode'            => 'cus_cardcode',
                                                  'cardnumber'          => 'cus_cardnumber',
                                                  'cardholdername'      => 'cus_cardholdernamer',
                                                  'cardexpire'            => 'cus_cardexpire'
                                                  );

      $pntable['pobbooking_customer_column_def'] = array(
                                                  'id'                  => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                                  'refid'               => 'TEXT',
                                                  'identificational'    => 'VARCHAR(50) DEFUALT NULL',
                                                  'nameprefix'          => 'VARCHAR(50) DEFUALT NULL',
                                                  'givenname'           => 'TEXT',
                                                  'surname'             => 'TEXT',
                                                  'addressline'             => 'TEXT',
                                                  'stateprov'           => 'VARCHAR(255) DEFUALT NULL',
                                                  'cityname'            => 'VARCHAR(255) DEFUALT NULL',
                                                  'countryname'         => 'VARCHAR(255) DEFUALT NULL',
                                                  'postalcode'          => 'VARCHAR(50)  DEFUALT NULL',
                                                  'mobile'              => 'VARCHAR(255) DEFUALT NULL',
                                                  'phone'               => 'VARCHAR(255) DEFUALT NULL',
                                                  'email'               => 'TEXT',
                                                  'cardcode'            => 'VARCHAR(255)',
                                                  'cardnumber'          => 'TEXT',
                                                  'cardholdername'      => 'TEXT',
                                                  'cardexpire'            => 'VARCHAR(10)'
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
                                                  'customer_refid'      => 'boo_customer_refid',
                                                  'chaincode'           => 'boo_chaincode',
                                                  'isocurrency'         => 'boo_isocurrency',
                                                  'checkin_date'        => 'boo_checkin_date',
                                                  'checkout_date'       => 'boo_checkout_date',
                                                  'night'               => 'boo_night',
                                                  'amount_room'         => 'boo_amount_room',
                                                  'roomtype'            => 'boo_roomtype',
                                                  'adult'               => 'boo_adult',
                                                  'child'               => 'boo_child',
                                                  'room_rate'               => 'boo_room_rate',
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
                                                  'cardholdername'      => 'cus_cardholdernamer',
                                                  'cardexpire'          => 'cus_cardexpire',
                                                  'issue_date'          => 'cus_issue_date'
                                          ); 

      $pntable['pobbooking_booking_column_def'] = array(
                                                  'id'               => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY', 
                                                  'customer_refid'   => 'TEXT',
                                                  'chaincode'        => 'VARCHAR(10)',
                                                  'isocurrency'      => 'VARCHAR(10)',
                                                  'checkin_date'     => 'DATE',
                                                  'checkout_date'    => 'DATE',
                                                  'night'            => 'int(2)',
                                                  'amount_room'      => 'int(2)',
                                                  'roomtype'         => 'VARCHAR(255)',
                                                  'adult'            => 'int(2)',
                                                  'child'            => 'int(2)',
                                                  'room_rate'            => 'double',
                                                  'room_rate_total'      => 'double',
                                                  'identificational'    => 'VARCHAR(50) DEFUALT NULL',
                                                  'nameprefix'           => 'VARCHAR(50) DEFUALT NULL',
                                                  'givenname'           => 'TEXT',
                                                  'surname'            => 'TEXT',
                                                  'addressline'             => 'TEXT',
                                                  'stateprov'                => 'VARCHAR(255) DEFUALT NULL',
                                                  'cityname'                => 'VARCHAR(255) DEFUALT NULL',
                                                  'countryname'             => 'VARCHAR(255) DEFUALT NULL',
                                                  'postalcode'             => 'VARCHAR(50)  DEFUALT NULL',
                                                  'mobile'              => 'VARCHAR(255) DEFUALT NULL',
                                                  'phone'               => 'VARCHAR(255) DEFUALT NULL',
                                                  'email'               => 'TEXT',
                                                  'addition_request'    => 'TEXT',
                                                  'cardcode'           => 'VARCHAR(255)',
                                                  'cardnumber'         => 'TEXT',
                                                  'cardholdername'      => 'TEXT',
                                                  'cardexpire'            => 'VARCHAR(10)',
                                                  'issue_date'          => 'DATETIME'
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
                                                  'id'                  => 'sta_id',
                                                  'name'                => 'sta_name'
                                             );

      $pntable['pobbooking_status_column_def'] = array(
                                                      'id'               => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                                      'name'             => 'VARCHAR(50)'
                                                  );
      $pntable['pobbooking_status_primary_key_column'] = 'id';

      // add standard data fields
      ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobbooking_status_column'], 'sta_');
      ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobbooking_status_column_def']);

      return $pntable;
  }


?>