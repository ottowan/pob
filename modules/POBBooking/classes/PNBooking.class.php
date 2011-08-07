<?php
class PNBooking extends PNObject {
  function PNBooking($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'pobbooking_booking';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->_init($init, $where);
  }

  function insertPostProcess(){

    $form = FormUtil::getPassedValue ('form', false );
    $chaincode = "";
    $card_exp_month = $this->_objData['card_exp_month'];
    $card_exp_year = substr($this->_objData['card_exp_year'], -2);
    $cardexpiredate = $card_exp_month.$card_exp_year;
    $roomstays = $this->_objData['roomstays'];
/*
form[isocurrency]
form[identificational]
form[nameprefix]
form[givenname]
form[surname]
form[addressline]
form[cityname]
form[stateprov]
form[countryname]
form[postalcode]
form[phonenumber][5]
form[phonenumber][1]
form[email]
form[comment]
form[profiletype]
                     
form[cardcode]
form[cardnumber]
form[roomstays]
form[cardholdername]
form[cardbankname]
form[cardissuingcountry]
                     
form[hotelcode]
*/

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
              $Source->setAttribute("ISOCurrency", $this->_objData['isocurrency']);

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
                    $Inv->setAttribute("InvCode", $item[numberofunits]);
                    $GuestCounts = $xml->createElement("GuestCounts");
                    $RoomStay->appendChild($GuestCounts);
                      $GuestCount = $xml->createElement("GuestCount");
                      $GuestCounts->appendChild($GuestCount);
                      $GuestCount->setAttribute("AgeQualifyingCode", "10");
                      $GuestCount->setAttribute("Count", "1");
                    $TimeSpan = $xml->createElement("TimeSpan");
                    $RoomStay->appendChild($TimeSpan);
                    $TimeSpan->setAttribute("End", "2011-07-29");
                    $TimeSpan->setAttribute("Start", "2011-07-28");
                    $Guarantee = $xml->createElement("Guarantee");
                    $RoomStay->appendChild($Guarantee);
                      $GuaranteesAccepted = $xml->createElement("GuaranteesAccepted");
                      $Guarantee->appendChild($GuaranteesAccepted);
                        $GuaranteeAccepted = $xml->createElement("GuaranteeAccepted");
                        $GuaranteesAccepted->appendChild($GuaranteeAccepted);
                          $PaymentCard = $xml->createElement("PaymentCard");
                          $GuaranteeAccepted->appendChild($PaymentCard);
                          $PaymentCard->setAttribute("CardCode", $this->_objData['cardcode']);
                          $PaymentCard->setAttribute("CardNumber", $this->_objData['cardnumber']);
                          $PaymentCard->setAttribute("ExpireDate", $cardexpiredate);
                            $CardHolderName = $xml->createElement("CardHolderName", $this->_objData['cardholdername']);
                            $PaymentCard->appendChild($CardHolderName);
                      $BasicPropertyInfo = $xml->createElement("BasicPropertyInfo");
                      $RoomStay->appendChild($BasicPropertyInfo);
                      $BasicPropertyInfo->setAttribute("ChainCode", $this->_objData['chaincode']);
                      $BasicPropertyInfo->setAttribute("HotelCode", $this->_objData['hotelcode']);
                      $Comments = $xml->createElement("Comments");
                      $RoomStay->appendChild($Comments);
                        $Comment = $xml->createElement("Comment");
                        $Comments->appendChild($Comment);
                          $Text = $xml->createElement("Text", $this->_objData['comment']);
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
                            $NamePrefix = $xml->createElement("NamePrefix", $this->_objData['nameprefix']);
                            $PersonName->appendChild($NamePrefix);
                            $GivenName = $xml->createElement("GivenName", $this->_objData['givenname']);
                            $PersonName->appendChild($GivenName);
                            $Surname = $xml->createElement("Surname", $this->_objData['givenname']);
                            $PersonName->appendChild($Surname);
                          $Telephone = $xml->createElement("Telephone");
                          $Customer->appendChild($Telephone);
                          if($this->_objData['phone']){
                            $Telephone->setAttribute("PhoneNumber", $this->_objData['phone']);
                            $Telephone->setAttribute("PhoneTechType", "1");
                          }
                          if($this->_objData['mobile']){
                            $Telephone->setAttribute("PhoneNumber", $this->_objData['mobile']);
                            $Telephone->setAttribute("PhoneTechType", "5");
                          }
                          $Email = $xml->createElement("Email", $this->_objData['email']);
                          $Customer->appendChild($Email);
                          $Address = $xml->createElement("Address");
                          $Customer->appendChild($Address);
                            $AddressLine = $xml->createElement("AddressLine", $this->_objData['addressline']);
                            $Address->appendChild($AddressLine);
                            $CityName = $xml->createElement("CityName", $this->_objData['cityname']);
                            $Address->appendChild($CityName);
                            $PostalCode = $xml->createElement("PostalCode", $this->_objData['postalcode']);
                            $Address->appendChild($PostalCode);
                            $StateProv = $xml->createElement("StateProv", $this->_objData['stateprov']);
                            $Address->appendChild($StateProv);
                            $CountryName = $xml->createElement("CountryName", $this->_objData['countryname']);
                            $Address->appendChild($CountryName);

    $xml->saveXML();
    print $xml->saveXML();
	//echo $xml->asXML();
    $xml->save("OTA_HotelResRQ1.xml");

////send xml
/*    $url = 'http://pob-ws.heroku.com/api/hotel_res';
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

    curl_close($ch);
*/
    //print $response;
    //exit;



    }
  }