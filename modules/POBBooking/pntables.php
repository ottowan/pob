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
                                                  'nameprefix'           => 'cus_nameprefix',
                                                  'givenname'           => 'cus_givenname',
                                                  'surname'            => 'cus_surname',
                                                  'addressline'             => 'cus_addressline',
                                                  'cityname'                => 'cus_cityname',
                                                  'stateprov'             => 'cus_stateprov',
                                                  'countryname'             => 'cus_countryname',
                                                  'postalcode'             => 'cus_postalcode',
                                                  'mobile'              => 'cus_mobile',
                                                  'phone'               => 'cus_phone',
                                                  'email'               => 'cus_email',
                                                  'addition_request'    => 'cus_addition_request',
                                                  'cardcode'           => 'cus_cardcode',
                                                  'cardnumber'         => 'cus_cardnumber'
                                             );

      $pntable['pobbooking_customer_column_def'] = array(
                                                      'id'                  => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                                      'refid'               => 'TEXT',
                                                      'status_id'           => 'INT(1)',
                                                      'identificational'    => 'VARCHAR(50) DEFUALT NULL',
                                                      'nameprefix'           => 'VARCHAR(50) DEFUALT NULL',
                                                      'givenname'           => 'TEXT',
                                                      'surname'            => 'TEXT',
                                                      'address'             => 'TEXT',
                                                      'stateprov'                => 'VARCHAR(255) DEFUALT NULL',
                                                      'cityname'                => 'VARCHAR(255) DEFUALT NULL',
                                                      'countryname'             => 'VARCHAR(255) DEFUALT NULL',
                                                      'postalcode'             => 'VARCHAR(50)  DEFUALT NULL',
                                                      'mobile'              => 'VARCHAR(255) DEFUALT NULL',
                                                      'phone'               => 'VARCHAR(255) DEFUALT NULL',
                                                      'email'               => 'TEXT',
                                                      'addition_request'    => 'TEXT',
                                                      'cardcode'           => 'VARCHAR(255)',
                                                      'cardnumber'         => 'TEXT'

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
                                                  'status_id'           => 'boo_status_id',
                                                  'refid'               => 'boo_refid',
                                                  'checkin_date'        => 'boo_checkin_date',
                                                  'checkout_date'       => 'boo_checkout_date',
                                                  'night'               => 'boo_night',
                                                  'amount_room'         => 'boo_amount_room',
                                                  'roomtype'            => 'boo_roomtype',
                                                  'bedtype'             => 'boo_bedtype',
                                                  'breakfast'           => 'boo_breakfast',
                                                  'adult'               => 'boo_adult',
                                                  'child_age'           => 'boo_child_age',
                                                  'price'               => 'boo_price',
                                                  'total_price'         => 'boo_total_price',
                                                  'nameprefix'           => 'cus_nameprefix',
                                                  'givenname'           => 'cus_givenname',
                                                  'surname'            => 'cus_surname',
                                                  'addressline'             => 'cus_addressline',
                                                  'cityname'                => 'cus_cityname',
                                                  'stateprov'             => 'cus_stateprov',
                                                  'countryname'             => 'cus_countryname',
                                                  'postalcode'             => 'cus_postalcode',
                                                  'mobile'              => 'cus_mobile',
                                                  'phone'               => 'cus_phone',
                                                  'email'               => 'cus_email',
                                                  'addition_request'    => 'cus_addition_request',
                                                  'cardcode'           => 'cus_cardcode',
                                                  'cardnumber'         => 'cus_cardnumber'
                                          ); 

      $pntable['pobbooking_booking_column_def'] = array(
                                                  'id'               => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY', 
                                                  'cus_id'           => 'INT(11) NOTNULL',
                                                  'status_id'        => 'INT(11) NOTNULL',
                                                  'refid'            => 'TEXT',
                                                  'checkin_date'     => 'DATE',
                                                  'checkout_date'    => 'DATE',
                                                  'night'            => 'int(2)',
                                                  'amount_room'      => 'int(2)',
                                                  'roomtype'         => 'VARCHAR(255)',
                                                  'bedtype'          => 'VARCHAR(255)',
                                                  'breakfast'        => 'VARCHAR(10)',
                                                  'adult'            => 'int(2)',
                                                  'child_age'        => 'int(2)',
                                                  'price'            => 'double',
                                                  'total_price'      => 'double',
												  'nameprefix'           => 'VARCHAR(50) DEFUALT NULL',
												  'givenname'           => 'TEXT',
												  'surname'            => 'TEXT',
												  'address'             => 'TEXT',
												  'stateprov'                => 'VARCHAR(255) DEFUALT NULL',
												  'cityname'                => 'VARCHAR(255) DEFUALT NULL',
												  'countryname'             => 'VARCHAR(255) DEFUALT NULL',
												  'postalcode'             => 'VARCHAR(50)  DEFUALT NULL',
												  'mobile'              => 'VARCHAR(255) DEFUALT NULL',
												  'phone'               => 'VARCHAR(255) DEFUALT NULL',
												  'email'               => 'TEXT',
												  'addition_request'    => 'TEXT',
												  'cardcode'           => 'VARCHAR(255)',
												  'cardnumber'         => 'TEXT'
                                              );
      $pntable['pobbooking_booking_primary_key_column'] = 'id';
      // add standard data fields
      ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobbooking_booking_column'], 'boo_');
      ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobbooking_booking_column_def']);

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