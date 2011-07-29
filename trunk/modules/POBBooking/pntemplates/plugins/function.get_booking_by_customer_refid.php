<?php
/**
count field by where clause
<!--[count_field_by_table table="tour_hotelseason" count_field="sea_id"]-->
*/
function smarty_function_get_booking_by_customer_refid ($params, &$smarty) {
    //Query data
    $pntables = pnDBGetTables();
      $sql = "
              SELECT 
              boo_id, 
              boo_cus_id, 
              boo_status_id, 
              boo_refid, 
              boo_checkin_date, 
              boo_checkout_date, 
              boo_night, 
              boo_amount_room, 
              boo_roomtype, 
              boo_bedtype, 
              boo_breakfast, 
              boo_adult, 
              boo_child_age, 
              boo_price, 
              boo_total_price
            FROM 
              $pntables[pobbooking_booking]
            WHERE 
                boo_cus_id = $params[cus_refid]";

      $column = array('id', 
                      'cus_id', 
                      'status_id', 
                      'refid', 
                      'checkin_date', 
                      'checkout_date', 
                      'night', 
                      'amount_room', 
                      'roomtype', 
                      'bedtype', 
                      'breakfast', 
                      'adult', 
                      'child_age', 
                      'price', 
                      'total_price');
      $result = DBUtil::executeSQL($sql);

      //Initial data to Array
      $bookingArray = DBUtil::marshallObjects ($result, $column);

      $smarty->assign("bookingArray", $bookingArray);
      $smarty->assign("booking_id", $bookingArray[0]['id']);
      $smarty->assign("refid", $bookingArray[0]['refid']);

      $smarty->assign("book_status_id", $bookingArray[0]['status_id']);
      $smarty->assign("checkin_date", $bookingArray[0]['checkin_date']);
      $smarty->assign("checkout_date", $bookingArray[0]['checkout_date']);
      $smarty->assign("amount_room", $bookingArray[0]['amount_room']);
      $smarty->assign("night", $bookingArray[0]['night']);

}