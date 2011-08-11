<?php
class PNCustomer extends PNObject {
    function PNCustomer($init=null, $where='') {
        $this->PNObject();

        $this->_objType       = 'pobbooking_customer';
        $this->_objField      = 'id';
        $this->_objPath       = 'form';

        $this->_init($init, $where);
    }


    /*
  function validate(){
    $form = FormUtil::getPassedValue ('form', false);
    $is_validate = true;
    $error = array();

    if (empty($form['identificational'])){
      $error[] = "Please enter your identificational/passport no.";
    }

    if (empty($form['titlename'])){
      $error[] = "Please select Titlename.";
    }

    if (empty($form['firstname'])){
      $error[] = "Please enter your First name.";
    }

    if (empty($form['lastname'])){
      $error[] = "Please enter your Last name/Surname.";
    }


    if (empty($form['address'])){
      $error[] = "Please enter your Address.";
    }


    if (empty($form['city'])){
      $error[] = "Please enter your Town/City.";
    }

    if (empty($form['country'])){
      $error[] = "Please select your Country.";
    }

    if (empty($form['zipcode'])){
      $error[] = "Please enter your Zipcode.";
    }

    if (empty($form['mobile'])){
      $error[] = "Please enter your Mobile Phone.";
    }

    if (empty($form['phone'])){
      $error[] = "Please enter your  ome Phone.";
    }

    if (empty($form['email'])){
      $error[] = "Please enter your Email.";
    }

    $session_captcha = SessionUtil::getVar('SECURITY_CAPTCHA');
    if (empty($form['captcha'])){
      $error[] = "Please enter Verification text.";
    }else if( $session_captcha != md5(base64_encode($form['captcha']))){
      //$error[] = "Verification text is not correct.";
      $error[] = "Captcha failed.";
    }

    if (empty($form['cardType'])){
      $error[] = "Please select your card type.";
    }

    if (empty($form['card1']) || empty($form['card2']) || empty($form['card3']) || empty($form['card4'])){
      $error[] = "Please enter your card number.";
    }

    if (empty($form['cardid'])){
      $error[] = "Please enter your card type.";
    }

    if (empty($form['card_holder_number'])){
      $error[] = "Please enter your holder number.";
    }

    if (empty($form['card_expire_month'])){
      $error[] = "Please select expire month.";
    }

    if (empty($form['card_expire_year'])){
      $error[] = "Please select expire year.";
    }

    if (empty($form['card_bank_name'])){
      $error[] = "Please enter your bank name.";
    }


    if (empty($form['card_issuing_country'])){
      $error[] = "Please select issuing country.";
    }

    //Conclude the result
    if($error){
      $is_validate = false;
      SessionUtil::setVar('ERROR_MESSAGE', $error, '/', true, true);
    }else{
      $is_validate = true;
    }


    //$is_validate = false;

    /*
    return $is_validate;
  }
    */


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

        //$roomstays = $form['roomstays'];
        //insert
        $this->_objData['cardexpire'] = $cardexpiredate;
        $this->_objData['cr_date'] = $datenow;
        $this->_objData['total_rooms'] = $total_rooms;
        return true;
    }


    function insertPostProcess() {
        Loader::loadClass('DataUtilEx', "modules/POBBooking/pnincludes");

        $id = DBUtil::getInsertID ($this->_objType, $this->_objField);
        $refid = "B".($id+1000);
        //var_dump($refid);
        //exit;
        $object = array('refid'=>$refid);
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
                    'cus_id'              => $id,
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
                    'cardcode'            => $form['cardcode'],
                    'cardnumber'          => $form['cardnumber'],
                    'cardholdername'      => $form['cardholdername'],
                    'cardexpire'          => $cardexpire,
                    'issue_date'          => $datenow
            );
            
            DBUtil::insertObject($objects,'pobbooking_booking');

            //count room for each type
            

/*
            //update avalible booking room
            $current_avalible_booking = DBUtil::selectFieldMax( 'ihotel_roomtype', 'id', 'MAX', '');
            $pntables = pnDBGetTables();
            $column   = $pntables['ihotel_roomtype'];
            $obj = array('amount_booking' => '123 Some Street');
            $where    = "WHERE $column[name]='$item[roomtype]'";

            DBUTil::updateObject ($obj, 'customers', $where);
 */

        }

        $this->sendXML();

		$aaa = "a";
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


    function sendEmail() {
        //Get value from input
        $form = FormUtil::getPassedValue ('form', false);

        $booking = $form['roomstays'];



        //Config email
        $adminEmail = "admin@phuketcity.com";
        $toEmail = $form[email].",".$adminEmail;

        //Config email body
        $body = "Dear ".$form['nameprefix'] .' ' .$form['givenname'].",\n";
        $body.= "\tThank you for your enquiry. Your request as\n";
        $body.= "detailed below has been sent to one of our client\n";
        $body.= "service who will respond to you within 1-2 business day.\n";
        $body.= "Should you require any assistancen\n";
        $body.= "please contact us at info@phuketcity.com\n";
        $body.= "[BOOKING DETAILS]\n";
        $body.= "Request type: Make a booking\n";
        foreach($booking  as $item) {
            $body.= "           Room Type:".$item[invcode]."\n";
            $body.= "           Room:".$item[numberofunit]."\n";
            $body.= "           Night:".$item[night]."\n";
            $body.= "           Adult:".$item[adult]."\n";
            if($item[child]){
              $body.= "           Children :".$item[child]."\n";
            }
            $body.= "           Price / One room / One night:".$item[rate]."\n";
        }
        $body.= "Addition requests:".$form[addition_request]."\n";
        $body.= "[CUSTOMER INFORMATION]\n";
        $body.= "name : ".$form[nameprefix]."".$form[givenname]."\t".$form[surname]."\t\n";
        $body.= "email : ".$form[email]."\n";
        $body.= "[OTHERS]\n";
        foreach($booking  as $item) {
          $body.= "Checkin Date :".$item[startdate]."\n";
          $body.= "Checkout Date :".$item[enddate]."\n";
        }
        $body.= "--------BOOKING PRICE:".$form[totalall]." BAHT--------\n\n\n\n";
        $body.= "phuketcity.com Client Service\n";
        $body.= "info@phuketcity.com";
        $body.= "             ";

        mail($toEmail,$subject,$body,$header);
    }

    function sendXML() {
      
    $form = FormUtil::getPassedValue ('form', false );
    $chaincode = "";
    $card_exp_month = $form['card_exp_month'];
    $card_exp_year = substr($form['card_exp_year'], -2);
    $cardexpiredate = $card_exp_month.$card_exp_year;
    $roomstays = $form['roomstays'];

    /////////////////////////////////////////////////////
    //////// GEN XML ////////
    /////////////////////////////////////////////////////
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
              $POS->appendChild($Source);
              $Source->setAttribute("ISOCurrency", $form['isocurrency']);

            //HotelReservations
            $HotelReservations = $xml->createElement("HotelReservations");
            $OTA_HotelResRQ->appendChild($HotelReservations);
              $HotelReservation = $xml->createElement("HotelReservation");
              $HotelReservations->appendChild($HotelReservation);
                $RoomStays = $xml->createElement("RoomStays");
                $HotelReservation->appendChild($RoomStays);
                  $RoomStay = $xml->createElement("RoomStay");
                  $RoomStays->appendChild($RoomStay);
                  foreach($roomstays as $key => $item){
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
                    if($form['adult'] != ""){
                      $GuestCount = $xml->createElement("GuestCount");
                      $GuestCounts->appendChild($GuestCount);
                      $GuestCount->setAttribute("AgeQualifyingCode", "10");
                      $GuestCount->setAttribute("Count", $form['adult']);
                    }elseif($form['child'] != ""){
                      $GuestCount = $xml->createElement("GuestCount");
                      $GuestCounts->appendChild($GuestCount);
                      $GuestCount->setAttribute("AgeQualifyingCode", "8");
                      $GuestCount->setAttribute("Count", $form['child']);
                    }
                      $GuestCount = $xml->createElement("GuestCount");
                      $GuestCounts->appendChild($GuestCount);
                      $GuestCount->setAttribute("AgeQualifyingCode", "10");
                      $GuestCount->setAttribute("Count", $form['guests']);
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
                          $PaymentCard->setAttribute("CardCode", $form['cardcode']);
                          $PaymentCard->setAttribute("CardNumber", $form['cardnumber']);
                          $PaymentCard->setAttribute("ExpireDate", $cardexpiredate);
                            $CardHolderName = $xml->createElement("CardHolderName", $form['cardholdername']);
                            $PaymentCard->appendChild($CardHolderName);
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

    ////send xml
        $url = 'http://pob-ws.heroku.com/api/hotel_res';
        $data = $xml->saveXML();
        //$data = $data->saveXML();
        print $data;
        //$data = $data->saveXML();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        $mystring = $response;
        $findme   = 'Success';
        $pos = strpos($mystring, $findme);
        if($pos > 0){
          $forwardurl = pnModURL('POBBooking');
        return $forwardurl;
        }else{
          //Unsuccess page
          $url = pnModURL('POBBooking', 'user', 'page', array('ctrl'=>'unsuccess', 'hotel'=>$form['hotelname']));
          pnRedirect($url);
          //return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
          exit;
        }

        curl_close($ch);
    
        //print $response;
        //exit;

/*        //test success
        $mystring = $response;
        $findme   = "Success";
        $pos = strpos($mystring, $findme);
        if($pos > 0){
        $forwardurl = pnModURL('POBBooking');
        return $forwardurl;
        }else{
           //Unsuccess page
          $url = pnModURL('POBBooking', 'user', 'page', array('ctrl'=>'unsuccess', 'hotel'=>$form['hotelname']));
          pnRedirect($url);
          //return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
          exit;
        }*/
        
    }


}