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
                                                  'titlename'           => 'cus_titlename',
                                                  'firstname'           => 'cus_firstname',
                                                  'lastname'            => 'cus_lastname',
                                                  'address'             => 'cus_address',
                                                  'city'                => 'cus_city',
                                                  'country'             => 'cus_country',
                                                  'zipcode'             => 'cus_zipcode',
                                                  'mobile'              => 'cus_mobile',
                                                  'phone'               => 'cus_phone',
                                                  'email'               => 'cus_email',
                                                  'addition_request'    => 'cus_addition_request',
                                                  'card_type'           => 'cus_card_type',
                                                  'card_number'         => 'cus_card_number'
                                             );

      $pntable['pobbooking_customer_column_def'] = array(
                                                      'id'                  => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                                      'refid'               => 'TEXT',
                                                      'status_id'           => 'INT(1)',
                                                      'identificational'    => 'VARCHAR(50) DEFUALT NULL',
                                                      'titlename'           => 'VARCHAR(50) DEFUALT NULL',
                                                      'firstname'           => 'TEXT',
                                                      'lastname'            => 'TEXT',
                                                      'address'             => 'TEXT',
                                                      'city'                => 'VARCHAR(255) DEFUALT NULL',
                                                      'country'             => 'VARCHAR(255) DEFUALT NULL',
                                                      'zipcode'             => 'VARCHAR(50)  DEFUALT NULL',
                                                      'mobile'              => 'VARCHAR(255) DEFUALT NULL',
                                                      'phone'               => 'VARCHAR(255) DEFUALT NULL',
                                                      'email'               => 'TEXT',
                                                      'addition_request'    => 'TEXT',
                                                      'card_type'           => 'VARCHAR(255)',
                                                      'card_number'         => 'TEXT'

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
                                                  'total_price'         => 'boo_total_price'
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
                                                  'total_price'      => 'double'
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