<?php
  function POBHotel_init(){
    if (!DBUtil::createTable('pobhotel_hotel')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_amenity')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_amenity')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_status')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_location_category')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_location')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_image')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_address_use_type')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_attraction')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_fee_tax_type')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_index_point')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_main_cuisine')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_meeting_room')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_meeting_room_format')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_payment_type')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_recreation_srvc_detail')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_recreation_srvc_type')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_restaurant_category')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_room_amenity_type')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_segment_category')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_transportation')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_room')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_room_image')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_season')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_rate')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_index_point')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_attraction')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_fee_tax')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_main_cuisine')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_meeting_room')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_payment_type')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_recreation_srvc')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_restaurant')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_room')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_segment')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_transportation')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_m_room_m_room_format')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_room_room_amenity')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_r_srvc_r_srvc_detail')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_hotel_rate_season')) {
      return false;
    }
    if (!DBUtil::createTable('pobhotel_member')) {
      return false;
    }
    $flgCreate1 = mkdir("pnTemp/pobhotel_upload");
    $flgCreate2 = mkdir("pnTemp/pobhotel_upload/image");
    $flgCreate3 = mkdir("pnTemp/pobhotel_upload/image/large");
    $flgCreate4 = mkdir("pnTemp/pobhotel_upload/image/thumb");

    POBHotel_init_data();

    return true;
  }

  function POBHotel_delete(){
    DBUtil::dropTable('pobhotel_hotel');
    DBUtil::dropTable('pobhotel_hotel_amenity');
    DBUtil::dropTable('pobhotel_amenity');
    DBUtil::dropTable('pobhotel_status');
    DBUtil::dropTable('pobhotel_location_category');
    DBUtil::dropTable('pobhotel_hotel_location');
    DBUtil::dropTable('pobhotel_hotel_image');
    DBUtil::dropTable('pobhotel_address_use_type');
    DBUtil::dropTable('pobhotel_attraction');
    DBUtil::dropTable('pobhotel_fee_tax_type');
    DBUtil::dropTable('pobhotel_index_point');
    DBUtil::dropTable('pobhotel_main_cuisine');
    DBUtil::dropTable('pobhotel_meeting_room');
    DBUtil::dropTable('pobhotel_meeting_room_format');
    DBUtil::dropTable('pobhotel_payment_type');
    DBUtil::dropTable('pobhotel_recreation_srvc_detail');
    DBUtil::dropTable('pobhotel_recreation_srvc_type');
    DBUtil::dropTable('pobhotel_restaurant_category');
    DBUtil::dropTable('pobhotel_room_amenity_type');
    DBUtil::dropTable('pobhotel_segment_category');
    DBUtil::dropTable('pobhotel_transportation');
    DBUtil::dropTable('pobhotel_room');
    DBUtil::dropTable('pobhotel_room_image');
    DBUtil::dropTable('pobhotel_season');
    DBUtil::dropTable('pobhotel_rate');
    DBUtil::dropTable('pobhotel_hotel_index_point');
    DBUtil::dropTable('pobhotel_hotel_attraction');
    DBUtil::dropTable('pobhotel_hotel_fee_tax');
    DBUtil::dropTable('pobhotel_hotel_main_cuisine');
    DBUtil::dropTable('pobhotel_hotel_meeting_room');
    DBUtil::dropTable('pobhotel_hotel_payment_type');
    DBUtil::dropTable('pobhotel_hotel_recreation_srvc');
    DBUtil::dropTable('pobhotel_hotel_restaurant');
    DBUtil::dropTable('pobhotel_hotel_room');
    DBUtil::dropTable('pobhotel_hotel_segment');
    DBUtil::dropTable('pobhotel_hotel_transportation');
    DBUtil::dropTable('pobhotel_m_room_m_room_format');
    DBUtil::dropTable('pobhotel_room_room_amenity');
    DBUtil::dropTable('pobhotel_r_srvc_r_srvc_detail');
    DBUtil::dropTable('pobhotel_hotel_rate_season');
    DBUtil::dropTable('pobhotel_member');
return true;
  }

  function POBHotel_init_data(){
       //Init amenity data
      $file = "modules/POBHotel/data/amenity_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_amenity', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/location_category_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_location_category', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/hotel_status_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_status', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/address_use_type.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_address_use_type', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/attraction_category_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_attraction', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/fee_tax_type.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_fee_tax_type', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/index_point_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_index_point', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/main_cuisine_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_main_cuisine', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/meeting_room_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_meeting_room', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/meeting_room_format_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_meeting_room_format', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/payment_type.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_payment_type', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/recreation_srvc_detail_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_recreation_srvc_detail', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/recreation_srvc_type.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_recreation_srvc_type', true);
        unset($amenityArray);
        unset($file);
        unset($data);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/restaurant_category_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_restaurant_category', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/room_amenity_type.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_room_amenity_type', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }

      $file = "modules/POBHotel/data/segment_category_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_segment_category', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }
      $file = "modules/POBHotel/data/transportation_code.csv";
      $handle = fopen($file, "r");
      if($handle!==false){
        $amenityArray =array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $amenityArray[] = array(
                                    'id' => $data[0],
                                    'name' => $data[1]
                            );
        }
        DBUtil::insertObjectArray($amenityArray, 'pobhotel_transportation', true);
        unset($amenityArray);
        unset($data);
        unset($file);
        fclose($handle);
      }


  }

    /*  DROP TABLE `zk_pobhotel_address_use_type`;
        DROP TABLE `zk_pobhotel_amenity`;
        DROP TABLE `zk_pobhotel_attraction`;
        DROP TABLE `zk_pobhotel_attraction_category`;
        DROP TABLE `zk_pobhotel_hotel_location`;
        DROP TABLE `zk_pobhotel_hotel_amenity`;
        DROP TABLE `zk_pobhotel_fee_tax_type`;
        DROP TABLE `zk_pobhotel_hotel`;
        DROP TABLE `zk_pobhotel_hotel_image`;
        DROP TABLE `zk_pobhotel_hotel_locations`;
        DROP TABLE `zk_pobhotel_index_point`;
        DROP TABLE `zk_pobhotel_location_category`;
        DROP TABLE `zk_pobhotel_main_cuisine`;
        DROP TABLE `zk_pobhotel_meeting_room`;
        DROP TABLE `zk_pobhotel_meeting_room_format`;
        DROP TABLE `zk_pobhotel_recreation_srvc_detail`;
        DROP TABLE `zk_pobhotel_recreation_srvc_type`;
        DROP TABLE `zk_pobhotel_restaurant_category`;
        DROP TABLE `zk_pobhotel_room_amenity_type`;
        DROP TABLE `zk_pobhotel_segment_category`;
        DROP TABLE `zk_pobhotel_status`;
        DROP TABLE `zk_pobhotel_transportation`;
        DROP TABLE `zk_pobhotel_payment_type`;
        DROP TABLE `zk_pobhotel_room`;
        DROP TABLE `zk_pobhotel_room_image`;
        DROP TABLE `zk_pobhotel_season`;
        DROP TABLE `zk_pobhotel_rate`;
        DROP TABLE `zk_pobhotel_hotel_index_point`;
        DROP TABLE `zk_pobhotel_hotel_attraction`;
        DROP TABLE `zk_pobhotel_hotel_fee_tax`;
        DROP TABLE `zk_pobhotel_hotel_main_cuisine`;
        DROP TABLE `zk_pobhotel_hotel_meeting_room`;
        DROP TABLE `zk_pobhotel_hotel_payment_type`;
        DROP TABLE `zk_pobhotel_hotel_recreation_srvc`;
        DROP TABLE `zk_pobhotel_hotel_restaurant`;
        DROP TABLE `zk_pobhotel_hotel_room`;
        DROP TABLE `zk_pobhotel_hotel_segment`;
        DROP TABLE `zk_pobhotel_hotel_transportation`;
        DROP TABLE `zk_pobhotel_m_room_m_room_format`;
        DROP TABLE `zk_pobhotel_room_room_amenity`;
        DROP TABLE `zk_pobhotel_r_srvc_r_srvc_detail`;
        DROP TABLE `zk_pobhotel_hotel_rate_season`;
*/


?>