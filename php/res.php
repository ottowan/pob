<?php
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
              $Source->setAttribute("ISOCurrency", "THB");

            //HotelReservations
            $HotelReservations = $xml->createElement("HotelReservations");
            $OTA_HotelResRQ->appendChild($HotelReservations);
              $HotelReservation = $xml->createElement("HotelReservation");
              $HotelReservations->appendChild($HotelReservation);
                $RoomStays = $xml->createElement("RoomStays");
                $HotelReservation->appendChild($RoomStays);
                  $RoomStay = $xml->createElement("RoomStay");
                  $RoomStays->appendChild($RoomStay);
                    $RoomTypes = $xml->createElement("RoomTypes");
                    $RoomStay->appendChild($RoomTypes);
                      $RoomType= $xml->createElement("RoomType");
                      $RoomTypes->appendChild($RoomType);
                      $RoomType->setAttribute("NumberOfUnits", "1");
                    $Inv = $xml->createElement("Inv");
                    $RoomStay->appendChild($Inv);
                    $Inv->setAttribute("InvCode", "Deluxe");
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
                          $PaymentCard->setAttribute("CardCode", "VS");
                          $PaymentCard->setAttribute("CardNumber", "4111111111111202");
                          $PaymentCard->setAttribute("ExpireDate", "0506");
                            $CardHolderName = $xml->createElement("CardHolderName", "Costello");
                            $PaymentCard->appendChild($CardHolderName);
                      $BasicPropertyInfo = $xml->createElement("BasicPropertyInfo");
                      $RoomStay->appendChild($BasicPropertyInfo);
                      $BasicPropertyInfo->setAttribute("ChainCode", "MC");
                      $BasicPropertyInfo->setAttribute("HotelCode", "RJBH001");
                      $Comments = $xml->createElement("Comments");
                      $RoomStay->appendChild($Comments);
                        $Comment = $xml->createElement("Comment");
                        $Comments->appendChild($Comment);
                          $Text = $xml->createElement("Text", "non-smoking room requested;king bed");
                          $Comment->appendChild($Text);
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
                            $NamePrefix = $xml->createElement("NamePrefix", "Ms.");
                            $PersonName->appendChild($NamePrefix);
                            $GivenName = $xml->createElement("GivenName", "Charlotte");
                            $PersonName->appendChild($GivenName);
                            $Surname = $xml->createElement("Surname", "Costello");
                            $PersonName->appendChild($Surname);
                          $Telephone = $xml->createElement("Telephone");
                          $Customer->appendChild($Telephone);
                          $Telephone->setAttribute("PhoneNumber", "8145556123");
                          $Telephone->setAttribute("PhoneTechType", "1");
                          $Email = $xml->createElement("Email", "charlotte.costello@corp.com");
                          $Customer->appendChild($Email);
                          $Address = $xml->createElement("Address");
                          $Customer->appendChild($Address);
                            $AddressLine = $xml->createElement("AddressLine", "123 Locust St.");
                            $Address->appendChild($AddressLine);
                            $CityName = $xml->createElement("CityName", "Hyndman");
                            $Address->appendChild($CityName);
                            $PostalCode = $xml->createElement("PostalCode", "15545");
                            $Address->appendChild($PostalCode);
                            $StateProv = $xml->createElement("StateProv", "PA");
                            $Address->appendChild($StateProv);
                            $CountryName = $xml->createElement("CountryName", "USA");
                            $Address->appendChild($CountryName);

     //$xml->saveXML();
    //print $xml->saveXML();
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

    curl_close($ch);

    //print $response;
    //exit;


?>
