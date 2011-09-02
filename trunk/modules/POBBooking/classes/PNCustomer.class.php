<?php
class PNCustomer extends PNObject {
    function PNCustomer($init=null, $where='') {
        $this->PNObject();

        $this->_objType       = 'pobbooking_customer';
        $this->_objField      = 'id';
        $this->_objPath       = 'form';

        $this->_init($init, $where);
    }

    private function encrypt($source){
      $fp=fopen("modules/POBBooking/pnincludes/pob.public.pem","r"); 
      $pub_key=fread($fp,8192); 
      fclose($fp); 
      $key_resource = openssl_get_publickey($pub_key); 
      if (!$key_resource) {
        echo "Cannot get public key"; exit;
      }

      openssl_public_encrypt($source,$crypttext, $key_resource ); 

      if (!empty($crypttext)) {
        openssl_free_key($key_resource);
        return base64_encode($crypttext); 
        //echo "Encryption OK!";
      }else{
        echo "Cannot Encrypt"; exit;
      }
    }



    function insertPreProcess() {
        //get
        $form = FormUtil::getPassedValue ('form', false );
        $card_exp_month = $form['card_exp_month'];//$this->_objData['card_exp_month'];
        $card_exp_year = substr($form['card_exp_month'], -2);
        $cardexpiredate = $card_exp_month.$card_exp_year;
        $datenow = date("Y-m-d H:i:s");
		//var_dump($datenow);
        //exit;
	  	  $roomstays = $form['roomstays'];
        $total_rooms = 0;
        foreach($roomstays as $item) {
        $total_rooms+=$item[numberofunits];
        }

        ///// Encryption Payment detail //////
        $cardexpire = $this->encrypt($cardexpiredate);
        $cardcode = $this->encrypt($form['cardcode']);
        $cardnumber = $this->encrypt($form['cardnumber']);
        $cardid = $this->encrypt($form['cardid']);
        $cardholdername = $this->encrypt($form['cardholdername']);

        //$roomstays = $form['roomstays'];
        //insert
//        echo " --> Insert Data"."\n";
        $this->_objData['cardexpire'] = $cardexpire;
        $this->_objData['cardcode'] = $cardcode;
        $this->_objData['cardnumber'] = $cardnumber;
        $this->_objData['cardsecurecode'] = $cardid;
        $this->_objData['cardholdername'] = $cardholdername;
        $this->_objData['booking_date'] = $datenow;
        $this->_objData['total_rooms'] = $total_rooms;
        
        return true;
    }


    function insertPostProcess() {
        Loader::loadClass('DataUtilEx', "modules/POBBooking/pnincludes");
//        echo " --> insertPostProcess"."\n";
        $cus_id = DBUtil::getInsertID ($this->_objType, $this->_objField);
        $refid = "B".($id+1000);
        $roomstays = $this->_objData['roomstays'];
        $availabilities = $this->_objData['availabilities'];

        //var_dump($cardexpiredate);
        //var_dump($total_rooms);
        //exit;

        $object = array('refid'=>$refid
                        );
        $where  = " cus_id = ".$id;
        DBUtil::updateObject($object,'pobbooking_customer',$where);
        if ($_POST['forward'] ) {
            $forward  = $_POST['forward'];
            //pnRedirect($list_url);
            //return true;
        }

        //Get form value to insert booking
        $form = FormUtil::getPassedValue ('form', false );
        $card_exp_month = $form['card_exp_month'];
        $card_exp_year = substr($form['card_exp_year'], -2);
        $cardexpire = $card_exp_month.$card_exp_year;
        $chaincode = $form['chaincode'];
        $roomstays = $form['roomstays'];
        $datenow = date("Y-m-d H:i:s");
        ///// Encryption Payment detail //////
        $cardexpire = $this->encrypt($cardexpire);
        $cardcode = $this->encrypt($form['cardcode']);
        $cardnumber = $this->encrypt($form['cardnumber']);
        $cardid = $this->encrypt($form['cardid']);
        $cardholdername = $this->encrypt($form['cardholdername']);
        foreach($roomstays as $item) {
            //Gennerate next id
            $current_booking_id = DBUtil::selectFieldMax( 'pobbooking_booking', 'id', 'MAX', '');
            if($current_booking_id == null) {
                $current_booking_id = 1;
            }else {
                $current_booking_id = $current_booking_id+1;
            }
            $objects = array(
                    'id'                  => $current_booking_id,
                    'cus_id'              => $cus_id,
                    'customer_refid'      => $refid,
                    'chaincode'           => $form['chaincode'],
                    'hotelname'           => $form['hotelname'],
                    'isocurrency'         => $form['isocurrency'],
                    'checkin_date'        => $item[startdate],
                    'checkout_date'       => $item[enddate],
                    'night'               => $item[night],
                    'amount_room'         => $item[numberofunits],
                    'roomtype'            => $item[invcode],
                    'adult'               => $item[adult],
                    'child'               => $item[child],
                    'room_rate'           => $item[rate],
                    'room_rate_total'     => $item[room_rate_total],
                    'identificational'    => $form['identificational'],
                    'nameprefix'          => $form['nameprefix'],
                    'givenname'           => $form['givenname'],
                    'surname'             => $form['surname'],
                    'addressline'         => $form['addressline'],
                    'cityname'            => $form['cityname'],
                    'stateprov'           => $form['stateprov'],
                    'countryname'         => $form['countryname'],
                    'postalcode'          => $form['postalcode'],
                    'mobile'              => $form['mobile'],
                    'phone'               => $form['phone'],
                    'email'               => $form['email'],
                    'addition_request'    => $form['addition_request'],
                    'cardcode'            => $cardcode,
                    'cardnumber'          => $cardnumber,
                    'cardholdername'      => $cardholdername,
                    'cardexpire'          => $cardexpire,
                    'cardsecurecode'      => $cardid,
                    'cardbankname'        => $form['cardbankname'],
                    'cardissuingcountry'  => $form['cardissuingcountry'],
                    'issue_date'          => $datenow
            );
            
            DBUtil::insertObject($objects,'pobbooking_booking');
            //creating CSV
            $this->array_to_CSV($objects);
        }
       //print_r($availabilities);exit;
        foreach($availabilities as $itemavAilabilities) {
            //Gennerate next id
            $current_booking_id = DBUtil::selectFieldMax( 'pobbooking_daybooking', 'id', 'MAX', '');
            if($current_booking_id == null) {
                $current_booking_id = 1;
            }else {
                $day_id = $current_booking_id+1;
            }
            $objects = array(
                    'id'                  => $day_id,
                    'cus_id'              => $cus_id,
                    'customer_refid'      => $refid,
                    'chaincode'           => $form['chaincode'],
                    'hotelname'           => $form['hotelname'],
                    'isocurrency'         => $form['isocurrency'],
                    'date'                => $item['date'],
                    'invcode'             => $item['invcode'],
                    'rate'                => $item['rate'],
                    'identificational'    => $form['identificational'],
                    'nameprefix'          => $form['nameprefix'],
                    'givenname'           => $form['givenname'],
                    'surname'             => $form['surname'],
                    'addressline'         => $form['addressline'],
                    'cityname'            => $form['cityname'],
                    'stateprov'           => $form['stateprov'],
                    'countryname'         => $form['countryname'],
                    'postalcode'          => $form['postalcode'],
                    'mobile'              => $form['mobile'],
                    'phone'               => $form['phone'],
                    'email'               => $form['email'],
                    'addition_request'    => $form['addition_request'],
                    'cardcode'            => $cardcode,
                    'cardnumber'          => $cardnumber,
                    'cardholdername'      => $cardholdername,
                    'cardexpire'          => $cardexpire,
                    'cardsecurecode'      => $cardid,
                    'cardbankname'        => $form['cardbankname'],
                    'cardissuingcountry'  => $form['cardissuingcountry'],
                    'issue_date'          => $datenow
            );
            
            DBUtil::insertObject($objects,'pobbooking_daybooking');
            
        }


//        echo " --> insertPostProcess sendXML"."\n";
        
        $this->sendXML();
        
        //Call sendEmail method
        //$this->sendEmail();


    }

    function updatePostProcess() {
        $form = FormUtil::getPassedValue ('form', false );
        $refid = $form['refid'];
        $status = $form['status_id'];
        if($refid) {
            $object = array('status_id' => $status);
            $where = "WHERE boo_refid = '$refid'";
            DBUtil::updateObject($object,'pobbooking_booking',$where);
        }
        if($refid) {
            $object = array('status_id' => $status);
            $where = "WHERE cus_refid = '$refid'";
            DBUtil::updateObject($object,'pobbooking_customer',$where);
        }
    }



    function sendXML() {
//      echo " --> func sendXML"."\n";
    $form = FormUtil::getPassedValue ('form', false );
    $chaincode = "";
    $card_exp_month = $form['card_exp_month'];
    $card_exp_year = substr($form['card_exp_year'], -2);
    $cardexpiredate = $card_exp_month.$card_exp_year;
    $roomstays = $form['roomstays'];

//////////////////////////////////////
///// Encryption Payment detail //////
//////////////////////////////////////
//echo " --> func sendXML before Encryption"."\n";
$cardexpire = $this->encrypt($cardexpiredate);
$cardcode = $this->encrypt($form['cardcode']);
$cardnumber = $this->encrypt($form['cardnumber']);
$cardid = $this->encrypt($form['cardid']);
$cardholdername = $this->encrypt($form['cardholdername']);
$rqtype = $this->encrypt('1');
$rqid = $this->encrypt('638fdJa7vRmkLs5');
//echo " --> func sendXML ALL Encrypted"."\n";


////////////////////////////
//////// GEN XML ///////////
////////////////////////////
    // Set the content type to be XML, so that the browser will recognise it as XML.
    // "Create" the document.
    $xml = new DOMDocument("1.0", "UTF-8");
    $xml->preserveWhiteSpace = false; 
    $xml->formatOutput = true;

        // Create some elements.
        //OTA_HotelResRQ.xml
        $OTA_HotelResRQ = $xml->createElement("OTA_HotelResRQ");
        // Set the attributes.
        $OTA_HotelResRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
        $OTA_HotelResRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $OTA_HotelResRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelResRQ.xsd");
        $OTA_HotelResRQ->setAttribute("Version", "1.000");
        $xml->appendChild($OTA_HotelResRQ);

             //POS
        $POS = $xml->createElement("POS");
        $OTA_HotelResRQ->appendChild($POS);
          $Source = $xml->createElement("Source");
          $Source->setAttribute("ISOCurrency", $form['isocurrency']);
          $POS->appendChild($Source);
          $RequestorID = $xml->createElement("RequestorID");
          $RequestorID->setAttribute("Encrypt", "1");
          $RequestorID->setAttribute("Type", $rqtype);
          $RequestorID->setAttribute("ID", $rqid);
          $Source->appendChild($RequestorID);
          //$POS = $xml->createElement("POS");

            //HotelReservations
            $HotelReservations = $xml->createElement("HotelReservations");
            $OTA_HotelResRQ->appendChild($HotelReservations);
              $HotelReservation = $xml->createElement("HotelReservation");
              $HotelReservations->appendChild($HotelReservation);
                $RoomStays = $xml->createElement("RoomStays");
                $HotelReservation->appendChild($RoomStays);
                foreach($roomstays as $key => $item){
                  $RoomStay = $xml->createElement("RoomStay");
                  $RoomStays->appendChild($RoomStay);
                    $RoomTypes = $xml->createElement("RoomTypes");
                    $RoomStay->appendChild($RoomTypes);
                      $RoomType= $xml->createElement("RoomType");
                      $RoomTypes->appendChild($RoomType);
                      $RoomType->setAttribute("NumberOfUnits", $item[numberofunits]);
                    $Inv = $xml->createElement("Inv");
                    $RoomStay->appendChild($Inv);
                    $Inv->setAttribute("InvCode", $item[invcode]);
                    $GuestCounts = $xml->createElement("GuestCounts");
                    $RoomStay->appendChild($GuestCounts);
                    if($item[adult] > 0){
                      $GuestCount = $xml->createElement("GuestCount");
                      $GuestCounts->appendChild($GuestCount);
                      $GuestCount->setAttribute("AgeQualifyingCode", "10");
                      $GuestCount->setAttribute("Count", $item[adult]);
                    }elseif($item[child] > 0){
                      $GuestCount = $xml->createElement("GuestCount");
                      $GuestCounts->appendChild($GuestCount);
                      $GuestCount->setAttribute("AgeQualifyingCode", "8");
                      $GuestCount->setAttribute("Count", $item[child]);
                    }
                    $TimeSpan = $xml->createElement("TimeSpan");
                    $RoomStay->appendChild($TimeSpan);
                    $TimeSpan->setAttribute("End", $item['enddate']);
                    $TimeSpan->setAttribute("Start", $item['startdate']);
                    $Guarantee = $xml->createElement("Guarantee");
                    $RoomStay->appendChild($Guarantee);
                      $GuaranteesAccepted = $xml->createElement("GuaranteesAccepted");
                      $Guarantee->appendChild($GuaranteesAccepted);
                        $GuaranteeAccepted = $xml->createElement("GuaranteeAccepted");
                        $GuaranteesAccepted->appendChild($GuaranteeAccepted);
                          $PaymentCard = $xml->createElement("PaymentCard");
                          $GuaranteeAccepted->appendChild($PaymentCard);
                          $PaymentCard->setAttribute("Encrypt", '1');
                          $PaymentCard->setAttribute("CardCode", $cardcode);
                          $PaymentCard->setAttribute("CardNumber", $cardnumber);
                          $PaymentCard->setAttribute("ExpireDate", $cardexpire);
                          $PaymentCard->setAttribute("CVV", $cardid);
                            $CardHolderName = $xml->createElement("CardHolderName", $cardholdername);
                            $PaymentCard->appendChild($CardHolderName);
                            $CardHolderName->setAttribute("Encrypt", '1');
                      $BasicPropertyInfo = $xml->createElement("BasicPropertyInfo");
                      $RoomStay->appendChild($BasicPropertyInfo);
                      $BasicPropertyInfo->setAttribute("ChainCode", $form['chaincode']);
                      $BasicPropertyInfo->setAttribute("HotelCode", $form['hotelcode']);
                      $Comments = $xml->createElement("Comments");
                      $RoomStay->appendChild($Comments);
                        $Comment = $xml->createElement("Comment");
                        $Comments->appendChild($Comment);
                          $Text = $xml->createElement("Text", $form['addition_request']);
                          $Comment->appendChild($Text);
                  }
                $ResGuests = $xml->createElement("ResGuests");
                $HotelReservation->appendChild($ResGuests);
                  $ResGuest = $xml->createElement("ResGuest");
                  $ResGuests->appendChild($ResGuest);
                    $Profiles = $xml->createElement("Profiles");
                    $ResGuest->appendChild($Profiles);
                    $ProfileInfo = $xml->createElement("ProfileInfo");
                    $Profiles->appendChild($ProfileInfo);
                      $Profile = $xml->createElement("Profile");
                      $ProfileInfo->appendChild($Profile);
                      $Profile->setAttribute("ProfileType", "1");
                        $Customer = $xml->createElement("Customer");
                        $Profile->appendChild($Customer);
                          $PersonName = $xml->createElement("PersonName");
                          $Customer->appendChild($PersonName);
                            $NamePrefix = $xml->createElement("NamePrefix", $form['nameprefix']);
                            $PersonName->appendChild($NamePrefix);
                            $GivenName = $xml->createElement("GivenName", $form['givenname']);
                            $PersonName->appendChild($GivenName);
                            $Surname = $xml->createElement("Surname", $form['givenname']);
                            $PersonName->appendChild($Surname);
                          $Telephone = $xml->createElement("Telephone");
                          $Customer->appendChild($Telephone);
                          if($form['phone']){
                            $Telephone->setAttribute("PhoneNumber", $form['phone']);
                            $Telephone->setAttribute("PhoneTechType", "1");
                          }
                          if($form['mobile']){
                            $Telephone->setAttribute("PhoneNumber", $form['mobile']);
                            $Telephone->setAttribute("PhoneTechType", "5");
                          }
                          $Email = $xml->createElement("Email", $form['email']);
                          $Customer->appendChild($Email);
                          $Address = $xml->createElement("Address");
                          $Customer->appendChild($Address);
                            $AddressLine = $xml->createElement("AddressLine", $form['addressline']);
                            $Address->appendChild($AddressLine);
                            $CityName = $xml->createElement("CityName", $form['cityname']);
                            $Address->appendChild($CityName);
                            $PostalCode = $xml->createElement("PostalCode", $form['postalcode']);
                            $Address->appendChild($PostalCode);
                            $StateProv = $xml->createElement("StateProv", $form['stateprov']);
                            $Address->appendChild($StateProv);
                            $CountryName = $xml->createElement("CountryName", $form['countryname']);
                            $Address->appendChild($CountryName);

        //$xml->saveXML();
        //print $xml->saveXML();
        //echo $xml->asXML();
        //exit;
        //$xml->save("OTA_HotelResRQ1.xml");

////////send xml
        $url = 'http://pob-ws.heroku.com/api/hotel_res';
        $data = $xml->saveXML();
        //$data = $data->saveXML();
        //print $data;
        //$data = $data->saveXML();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        //header("Content-type: text/xml");
        //echo $response; exit;
        //echo "**************Response recieved******************";
        //echo $response;exit;
////////get booking id
        $objxml = simplexml_load_string($response);
        //print_r($sxml); exit;
        $bookingid = $objxml->HotelReservations->HotelReservation->ResGlobalInfo->HotelReservationIDs->HotelReservationID->attributes()->BookingID;
        //print_r($bookingid); exit;
        //$render = pnRender::getInstance('POBPortal');
        //$render->assign("bookingid", $bookingid );


        //echo "***BookingID*** : ".$bookingid; exit;
////////update booking id to table
        Loader::loadClass('DataUtilEx', "modules/POBBooking/pnincludes");
        $id = DBUtil::getInsertID ($this->_objType, $this->_objField);
        $object = array('booking_id'=>$bookingid);
        $where  = " cus_id = ".$id;
        DBUtil::updateObject($object,'pobbooking_customer',$where);
        DBUtil::updateObject($object,'pobbooking_daybooking',$where);
        DBUtil::updateObject($object,'pobbooking_booking',$where);
        
        $mystring = $response;
        $findme   = '<Success>';
        $pos = strpos($mystring, $findme);
        curl_close($ch);
        
        
        $this->updatePostCalendar($id);
        
        if($pos > 0){
          $url = pnModURL('POBBooking', 'user', 'page', array('ctrl'=>'success', 'bid'=>$bookingid));
          pnRedirect($url);
          exit;
        }else{
          //Unsuccess page
          $url = pnModURL('POBBooking', 'user', 'page', array('ctrl'=>'unsuccess', 'hotel'=>$form['hotelname']));
          pnRedirect($url);
          exit;
        }
        //print $response;
        //exit;

    }

    function array_to_CSV($data)
    {
        $outstream = fopen("php://temp", 'r+');
        fputcsv($outstream, $data, ',', '"');
        rewind($outstream);
        $csv = fgets($outstream);
        fclose($outstream);
        return $csv;
    }

    private function updatePostCalendar($cus_id=''){
      //load class
      if (!($class = Loader::loadClassFromModule ('POBHotel', 'DayBookingArray', false)))
        return LogUtil::registerError ('Unable to load class [DayBookingArray] ...');

      $object = new $class ();
      $object->get(" cus_id = ".$cus_id);
      
      pnModAPIFunc('PostCalendar', 'user', 'insertBooking', $object->_objData);
    }

}