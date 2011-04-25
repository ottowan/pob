<?php

// Set the content type to be XML, so that the browser will recognise it as XML.
header("content-type: application/xml; charset=UTF-8");

// "Create" the document.
$xml = new DOMDocument("1.0", "UTF-8");
$xml->preserveWhiteSpace = false; 
$xml->formatOutput = true;

/////////////////////////////////////////////////////
//Create node
/////////////////////////////////////////////////////
  //OTA_HotelDescriptiveContentNotifRQ
  $OTA_HotelDescriptiveContentNotifRQ = $xml->createElement("OTA_HotelDescriptiveContentNotifRQ");
  // Set the attributes.
  $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
  $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
  $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd");
  $OTA_HotelDescriptiveContentNotifRQ->setAttribute("Version", "2.001");
  $xml->appendChild($OTA_HotelDescriptiveContentNotifRQ);

  $HotelDescriptiveContents = $xml->createElement("HotelDescriptiveContents");
  $OTA_HotelDescriptiveContentNotifRQ->appendChild($HotelDescriptiveContents);

    $HotelDescriptiveContent = $xml->createElement("HotelDescriptiveContent");
    $HotelDescriptiveContent->setAttribute("BrandCode", "MHRS");
    $HotelDescriptiveContent->setAttribute("BrandName", "Marriott Hotels &amp; Resorts");
    $HotelDescriptiveContent->setAttribute("CurrencyCode", "USD");
    $HotelDescriptiveContent->setAttribute("HotelCode", "BOSCO");
    $HotelDescriptiveContent->setAttribute("HotelName", "Boston Marriott Copley Place");
    $HotelDescriptiveContent->setAttribute("LanguageCode", "EN");
    $HotelDescriptiveContents->appendChild($HotelDescriptiveContent);

      $HotelInfo = $xml->createElement("HotelInfo");
      $HotelInfo->setAttribute("HotelStatus", "Open");
      $HotelInfo->setAttribute("LastUpdated", "2005-01-14T09:57:59");
      $HotelInfo->setAttribute("Start", "1984-05-19");
      $HotelInfo->setAttribute("WhenBuilt", "1984");
      $HotelDescriptiveContent->appendChild($HotelInfo);

        $CategoryCodes = $xml->createElement("CategoryCodes");
        $HotelInfo->appendChild($CategoryCodes);

          $LocationCategory = $xml->createElement("LocationCategory");
          $LocationCategory->setAttribute("Code", "3");
          $LocationCategory->setAttribute("CodeDetail", "Location Type: City");
          $CategoryCodes->appendChild($LocationCategory);

        $Descriptions = $xml->createElement("Descriptions");
        $HotelInfo->appendChild($Descriptions);

          $Renovation = $xml->createElement("Renovation");
          $Renovation->setAttribute("ImmediatePlans", "false");
          $Renovation->setAttribute("PercentOfRenovationCompleted", "100"); 
          $Descriptions->appendChild($Renovation);

          $Renovation = $xml->createElement("Renovation");
          $Descriptions->appendChild($Renovation);

            $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
            $Renovation->appendChild($MultimediaDescriptions);
              //MultimediaDescription 1
              $MultimediaDescription = $xml->createElement("MultimediaDescription");
              $MultimediaDescriptions->appendChild($MultimediaDescription);
                $TextItems = $xml->createElement("TextItems");
                $MultimediaDescription->appendChild($TextItems);
                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Renovation Area Completion Date 1");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "2000-02-13");
                    $TextItem->appendChild($Description);

              //MultimediaDescription 2
              $MultimediaDescription = $xml->createElement("MultimediaDescription");
              $MultimediaDescriptions->appendChild($MultimediaDescription);
                $TextItems = $xml->createElement("TextItems");
                $MultimediaDescription->appendChild($TextItems);
                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Renovation Area Completion Date 2");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "Guest Rooms");
                    $TextItem->appendChild($Description);

              //MultimediaDescription 3
              $MultimediaDescription = $xml->createElement("MultimediaDescription");
              $MultimediaDescriptions->appendChild($MultimediaDescription);
                $TextItems = $xml->createElement("TextItems");
                $MultimediaDescription->appendChild($TextItems);
                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Renovation Area Completion Date 1");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "2002-10-01");
                    $TextItem->appendChild($Description);


          $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
          $Descriptions->appendChild($MultimediaDescriptions);
            $MultimediaDescription = $xml->createElement("MultimediaDescription");
            $MultimediaDescriptions->appendChild($MultimediaDescription);
              $TextItems = $xml->createElement("TextItems");
              $MultimediaDescription->appendChild($TextItems);

                //Item 1
                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Description");
                $TextItems->appendChild($TextItem);
                  $Description = $xml->createElement("Description", "The Boston Marriott Copley Place Hotel is the perfect destination for business or pleasure.  We&apos;re located in Boston&apos;s Back Bay, off the Mass. Trnpk. at Exit 22, 4 mi from Logan Airport, &amp; in close proximity to subway.");
                  $TextItem->appendChild($Description);

                //Item 2
                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "PropertyLongDescription");
                $TextItems->appendChild($TextItem);
                  $Description = $xml->createElement("Description", "Boston Marriott Copley Place hotel is ideally located in Boston&apos;s Back Bay. This Copley Square hotel is easily accessible for business convenience. Centrally located in Copley Place for leisure activities. And technology-driven for dynamic meetings. For your trip to Boston, Massachusetts, the Copley Marriott is the perfect choice.");
                  $TextItem->appendChild($Description);

                //Item 3
                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Top Selling Feature 1");
                $TextItems->appendChild($TextItem);
                  $Description = $xml->createElement("Description", "Boston&apos;s historic Back Bay, just off the Massachusetts Turnpike;  four miles from Logan Airport");
                  $TextItem->appendChild($Description);


                //Item 4
                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Top Selling Feature 2");
                $TextItems->appendChild($TextItem);
                  $Description = $xml->createElement("Description", ",147 rooms and suites, the largest hotel ballroom in the area, and 65,000 square feet of event room");
                  $TextItem->appendChild($Description);


                //Item 5
                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Property Service Level");
                $TextItems->appendChild($TextItem);
                  $Description = $xml->createElement("Description", "Service");
                  $TextItem->appendChild($Description);


                //Item 6
                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Guest Room Highlights");
                $TextItems->appendChild($TextItem);
                  $Description = $xml->createElement("Description", "internet access");
                  $Description->setAttribute("ListItem", "1");
                  $TextItem->appendChild($Description);

                  $Description = $xml->createElement("Description", "&quot; color TV with cable movies, in-room pay movies, Web TV and Gameboy");
                  $Description->setAttribute("ListItem", "2");
                  $TextItem->appendChild($Description);

                  $Description = $xml->createElement("Description", "and refrigerators");
                  $Description->setAttribute("ListItem", "3");
                  $TextItem->appendChild($Description);

                //Item 7
                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Business/Group Highlights");
                $TextItems->appendChild($TextItem);
                  $Description = $xml->createElement("Description", "&amp; Service Team consistently awarded the highest Guest Satisfaction Scores");
                  $Description->setAttribute("ListItem", "1");
                  $TextItem->appendChild($Description);

                  $Description = $xml->createElement("Description", "the Gold Key Award and the Corporate &amp; lncentive Travel Award of Excellence");
                  $Description->setAttribute("ListItem", "2");
                  $TextItem->appendChild($Description);

                  $Description = $xml->createElement("Description", "Exhibit Hall 22,500 square feet");
                  $Description->setAttribute("ListItem", "3");
                  $TextItem->appendChild($Description);


        $Position = $xml->createElement("Position");
        $Position->setAttribute("Latitude", "42.347996");
        $Position->setAttribute("Longitude", "-71.07869");
        $HotelInfo->appendChild($Position);



        $Services = $xml->createElement("Services");
        $HotelInfo->appendChild($Services);

          //Service 1
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "103");
          $Services->appendChild($Service);
            $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
            $Service->appendChild($MultimediaDescriptions);
              $MultimediaDescription = $xml->createElement("MultimediaDescription");
              $MultimediaDescriptions->appendChild($MultimediaDescription);
                $TextItems = $xml->createElement("TextItems");
                $MultimediaDescription->appendChild($TextItems);
                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Language Spoken By Staff");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "Spanish, French, Vietnamese, English, Russian");
                    $TextItem->appendChild($Description);

          //Service 2
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "164");
          $Services->appendChild($Service);
            $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
            $Service->appendChild($MultimediaDescriptions);
              $MultimediaDescription = $xml->createElement("MultimediaDescription");
              $MultimediaDescriptions->appendChild($MultimediaDescription);
                $TextItems = $xml->createElement("TextItems");
                $MultimediaDescription->appendChild($TextItems);
                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Number of Food and Beverage Outlets");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "2");
                    $TextItem->appendChild($Description);


          //Service 3
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "165");
          $Services->appendChild($Service);
            $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
            $Service->appendChild($MultimediaDescriptions);
              $MultimediaDescription = $xml->createElement("MultimediaDescription");
              $MultimediaDescriptions->appendChild($MultimediaDescription);
                $TextItems = $xml->createElement("TextItems");
                $MultimediaDescription->appendChild($TextItems);
                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Number of Lounges/Bars");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "1");
                    $TextItem->appendChild($Description);


          //Service 4
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "63");
          $Service->setAttribute("CodeDetail", "Fee");
          $Services->appendChild($Service);
            $RelativePosition = $xml->createElement("RelativePosition");
            $RelativePosition->setAttribute("Distance", ".1");
            $RelativePosition->setAttribute("Name", "Copley Place Garage");
            $Service->appendChild($RelativePosition);


          //Service 5
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "64");
          $Service->setAttribute("CodeDetail", "Fee");
          $Services->appendChild($Service);
            $OperationSchedules = $xml->createElement("OperationSchedules");
            $Service->appendChild($OperationSchedules);
              $OperationSchedule = $xml->createElement("OperationSchedule");
              $OperationSchedules->appendChild($OperationSchedule);
                $Charge = $xml->createElement("Charge");
                $Charge->setAttribute("Amount", "29");
                $Charge->setAttribute("ChargeUnit", "1");
                $OperationSchedule->appendChild($Charge);

          //Service 6
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "97");
          $Service->setAttribute("CodeDetail", "Fee");
          $Services->appendChild($Service);
            $OperationSchedules = $xml->createElement("OperationSchedules");
            $Service->appendChild($OperationSchedules);
              $OperationSchedule = $xml->createElement("OperationSchedule");
              $OperationSchedules->appendChild($OperationSchedule);
                $Charge = $xml->createElement("Charge");
                $Charge->setAttribute("Amount", "37");
                $Charge->setAttribute("ChargeUnit", "1");
                $OperationSchedule->appendChild($Charge);


          //Service 7
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "80");
          $Services->appendChild($Service);
            $Features = $xml->createElement("Features");
            $Service->appendChild($Features);
              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("SecurityCode", "2");
              $Features->appendChild($Feature);
              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Feature->appendChild($MultimediaDescriptions);
                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);
                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);
                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Address of Nearest Police Station");
                    $TextItems->appendChild($TextItem);
                      $Description = $xml->createElement("Description", "Area D, District 4, 7 Warren Ave.");
                      $TextItem->appendChild($Description);

              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("CodeDetail", "Yes");
              $Feature->setAttribute("SecurityCode", "7");
              $Features->appendChild($Feature);

              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("SecurityCode", "8");
              $Features->appendChild($Feature);

              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("SecurityCode", "75");
              $Features->appendChild($Feature);

              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("SecurityCode", "9");
              $Features->appendChild($Feature);

            $Features = $xml->createElement("Features");
            $Service->appendChild($Features);
              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("SecurityCode", "11");
              $Features->appendChild($Feature);
              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Feature->appendChild($MultimediaDescriptions);
                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);
                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);
                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Dist to Nearest Police Station");
                    $TextItems->appendChild($TextItem);
                      $Description = $xml->createElement("Description", "2");
                      $TextItem->appendChild($Description);


              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("SecurityCode", "12");
              $Features->appendChild($Feature);

              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("CodeDetail", "Yes");
              $Feature->setAttribute("SecurityCode", "15");
              $Features->appendChild($Feature);


            $Features = $xml->createElement("Features");
            $Service->appendChild($Features);
              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("SecurityCode", "35");
              $Features->appendChild($Feature);
              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Feature->appendChild($MultimediaDescriptions);
                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);
                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);
                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Phone # of Nearest Police Station");
                    $TextItems->appendChild($TextItem);
                      $Description = $xml->createElement("Description", "343-4250");
                      $TextItem->appendChild($Description);



          //Service 8
          $Service = $xml->createElement("Service");
          $Service->setAttribute("Code", "47");
          $Services->appendChild($Service);
            $Features = $xml->createElement("Features");
            $Service->appendChild($Features);
              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("AccessibleCode", "3");
              $Features->appendChild($Feature);
              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Feature->appendChild($MultimediaDescriptions);
                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);
                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);
                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Bathroom Vanity in Guest rooms for wheel chaired person height");
                    $TextItems->appendChild($TextItem);
                      $Description = $xml->createElement("Description", "32");
                      $TextItem->appendChild($Description);

            $Feature = $xml->createElement("Feature");
            $Feature->setAttribute("Accessible", "18");
            $Feature->setAttribute("CodeDetail", "39");
            $Features->appendChild($Feature);

            $Features = $xml->createElement("Features");
            $Service->appendChild($Features);
              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("AccessibleCode", "25");
              $Features->appendChild($Feature);
              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Feature->appendChild($MultimediaDescriptions);
                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);
                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);
                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Other Services Available for Disabled");
                    $TextItems->appendChild($TextItem);
                      $Description = $xml->createElement("Description", "The hotel does provide shower chairs for handicapped guests.   The beds in the hotel are not elevated any higher than a normal mattress height.");
                      $TextItem->appendChild($Description);

            $Feature = $xml->createElement("Feature");
            $Feature->setAttribute("Accessible", "27");
            $Feature->setAttribute("CodeDetail", "41");
            $Features->appendChild($Feature);

            $Feature = $xml->createElement("Feature");
            $Feature->setAttribute("Accessible", "31");
            $Feature->setAttribute("CodeDetail", "36");
            $Features->appendChild($Feature);

            $Features = $xml->createElement("Features");
            $Service->appendChild($Features);
              $Feature = $xml->createElement("Feature");
              $Feature->setAttribute("AccessibleCode", "37");
              $Features->appendChild($Feature);
              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Feature->appendChild($MultimediaDescriptions);
                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);
                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);
                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Floors with Handicapped Rooms");
                    $TextItems->appendChild($TextItem);
                      $Description = $xml->createElement("Description", "Yes");
                      $TextItem->appendChild($Description);

            $Feature = $xml->createElement("Feature");
            $Feature->setAttribute("Accessible", "41");
            $Feature->setAttribute("CodeDetail", "39");
            $Features->appendChild($Feature);


      //Policies
      $Policies = $xml->createElement("Policies");
      $HotelDescriptiveContent->appendChild($Policies);

        $Policy = $xml->createElement("Policy");
        $Policies->appendChild($Policy);

          //CancelPolicy
          $CancelPolicy = $xml->createElement("CancelPolicy");
          $Policy->appendChild($CancelPolicy);

            $CancelPenalty = $xml->createElement("CancelPenalty");
            $CancelPolicy->appendChild($CancelPenalty);

              $PenaltyDescription = $xml->createElement("PenaltyDescription");
              $PenaltyDescription->setAttribute("name", "Cancellation Policy");
              $CancelPenalty->appendChild($PenaltyDescription);

                $Text = $xml->createElement("Text", "6:00 PM");
                $PenaltyDescription->appendChild($Text);


          //GuaranteePaymentPolicy
          $GuaranteePaymentPolicy = $xml->createElement("GuaranteePaymentPolicy");
          $Policy->appendChild($GuaranteePaymentPolicy);

            $GuaranteePayment = $xml->createElement("GuaranteePayment");
            $GuaranteePayment->setAttribute("PaymentCode", "2");
            $GuaranteePaymentPolicy->appendChild($GuaranteePayment);

          //PolicyInfoCodes
          $PolicyInfoCodes = $xml->createElement("PolicyInfoCodes");
          $Policy->appendChild($PolicyInfoCodes);

          $PolicyInfoCode = $xml->createElement("PolicyInfoCode");
          $PolicyInfoCodes->appendChild($PolicyInfoCode);

          $Description = $xml->createElement("Description");
            $Description->setAttribute("Name", "Oversold - Phone Call Home/Business");
            $PolicyInfoCode->appendChild($Description);
              $Text = $xml->createElement("Text", "Y");
              $Description->appendChild($Text);

          $PolicyInfoCode = $xml->createElement("PolicyInfoCode");
          $PolicyInfoCode->setAttribute("Name", "OversoldArrangeAccommodations");
          $PolicyInfoCodes->appendChild($PolicyInfoCode);

          $PolicyInfoCode = $xml->createElement("PolicyInfoCode");
          $PolicyInfoCode->setAttribute("Name", "OversoldPayOneNightRoom");
          $PolicyInfoCodes->appendChild($PolicyInfoCode);

          $PolicyInfoCode = $xml->createElement("PolicyInfoCode");
          $PolicyInfoCode->setAttribute("Name", "OversoldArrangeTransportation");
          $PolicyInfoCodes->appendChild($PolicyInfoCode);

          //CheckoutCharges
          $CheckoutCharges = $xml->createElement("CheckoutCharges");
          $Policy->appendChild($CheckoutCharges);

            $CheckoutCharge = $xml->createElement("CheckoutCharge");
            $CheckoutCharge->setAttribute("CodeDetail", "Late Check-Out Available");
            $CheckoutCharge->setAttribute("Type", "Late");
            $CheckoutCharges->appendChild($CheckoutCharge);

              $Description = $xml->createElement("Description");
              $Description->setAttribute("Name", "Late Check-Out Fees");
              $CheckoutCharge->appendChild($Description);

                $Text = $xml->createElement("Text");
                $Description->appendChild($Text);

          //PolicyInfo
          $PolicyInfo = $xml->createElement("PolicyInfo");
          $PolicyInfo->setAttribute("CheckInTime", "14:00:00");
          $PolicyInfo->setAttribute("CheckOutTime", "12:00:00");
          $PolicyInfo->setAttribute("KidsStayFree", "true");
          $PolicyInfo->setAttribute("TotalGuestCount", "5");
          $Policy->appendChild($PolicyInfo);


          //TaxPolicies
          $TaxPolicies = $xml->createElement("TaxPolicies");
          $Policy->appendChild($TaxPolicies);

            $TaxPolicy = $xml->createElement("TaxPolicy");
            $TaxPolicy->setAttribute("Amount", "5");
            $TaxPolicy->setAttribute("Code", "7");
            $TaxPolicies->appendChild($TaxPolicy);

            $TaxPolicy = $xml->createElement("TaxPolicy");
            $TaxPolicy->setAttribute("Code", "10");
            $TaxPolicy->setAttribute("NightsForTaxExemptionQuantity", "90");
            $TaxPolicy->setAttribute("Percent", "5.7");
            $TaxPolicies->appendChild($TaxPolicy);

            $TaxPolicy = $xml->createElement("TaxPolicy");
            $TaxPolicy->setAttribute("Code", "17");
            $TaxPolicy->setAttribute("Percent", "12.45");
            $TaxPolicies->appendChild($TaxPolicy);

            $TaxPolicy = $xml->createElement("TaxPolicy");
            $TaxPolicy->setAttribute("Amount", "2.75");
            $TaxPolicy->setAttribute("Code", "27");
            $TaxPolicies->appendChild($TaxPolicy);




      //AreaInfo
      $AreaInfo = $xml->createElement("AreaInfo");
      $HotelDescriptiveContent->appendChild($AreaInfo);


        //RefPoints
        $RefPoints = $xml->createElement("RefPoints");
        $AreaInfo->appendChild($RefPoints);
          $RefPoint = $xml->createElement("RefPoint");
          $RefPoint->setAttribute("Distance", "3");
          $RefPoint->setAttribute("IndexPointCode", "3");
          $RefPoint->setAttribute("Name", "Expway, Route 93, Mass. Turnpike");
          $RefPoints->appendChild($RefPoint);

          $Transportations = $xml->createElement("Transportations");
          $RefPoint->appendChild($Transportations);

            $Transportation = $xml->createElement("Transportation");
            $Transportations->appendChild($Transportation);

              $multi = $xml->createElement("MultimediaDescriptions");
              $Transportation->appendChild($multi);

                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $multi->appendChild($MultimediaDescription);

                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);

                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Directions to Highway from Property");
                    $TextItems->appendChild($TextItem);

                      $Description = $xml->createElement("Description", "Please call the hotel for directions");
                      $TextItem->appendChild($Description);

        //Attractions
        $Attractions = $xml->createElement("Attractions");
        $Attractions->setAttribute("LastUpdated", "2004-11-10T15:50:30");
        $AreaInfo->appendChild($Attractions);

          $Attraction = $xml->createElement("Attraction");
          $Attraction->setAttribute("AttractionCategoryCode", "1");
          $Attraction->setAttribute("AttractionName", "Boston");
          $Attraction->setAttribute("Code", "BOS");
          $Attraction->setAttribute("CourtesyPhone", "true");
          $Attraction->setAttribute("AttractionCategoryCode", "BOS");
          $Attraction->setAttribute("AttractionName", "Quincy Market/Faneuil Hal");
          $Attractions->appendChild($Attraction);

            $Contact = $xml->createElement("Contact");
            $Attraction->appendChild($Contact);

              $Addresses = $xml->createElement("Addresses");
              $Contact->appendChild($Addresses);
                $Address = $xml->createElement("Address");
                $Addresses->appendChild($Address);
                  $CityName = $xml->createElement("CityName", "BOSTON");
                  $Address->appendChild($CityName);
                  $StateProv = $xml->createElement("StateProv", "MA");
                  $Address->appendChild($StateProv);
                  $CountryName = $xml->createElement("CountryName", "US");
                  $Address->appendChild($CountryName);

              $Phones = $xml->createElement("Phones");
              $Contact->appendChild($Phones);
                $Phone = $xml->createElement("Phone");
                $Phone->setAttribute("PhoneLocationType", "2");
                $Phone->setAttribute("PhoneNumber", "8002353426");
                $Phone->setAttribute("PhoneTechType", "1");
                $Phone->setAttribute("PhoneUseType", "5");
                $Phones->appendChild($Phone);


            $TextItem = $xml->createElement("MultimediaDescriptions");
            $Attraction->appendChild($MultimediaDescriptions);

              $multi1 = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($multi1);

                $TextItems = $xml->createElement("TextItems");
                $MultimediaDescription->appendChild($TextItems);

                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Sort Order");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "1");
                    $TextItem->appendChild($Description);

                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "AirportName/Code");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "Boston (BOS)");
                    $TextItem->appendChild($Description);

                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Driving Instructions from Airport");
                  $TextItems->appendChild($TextItem);
                    $Description = $xml->createElement("Description", "East on Mass Turnpike (Route 90).  Take the Copley Square exit which exits onto Stuart Street.  At the first light turn left onto Dartmouth Street.  At the next light turn left onto Huntington Avenue and stay in the left lane.  At the light under the skybridge make a u-turn to the left.  The hotel is in the right.");
                    $TextItem->appendChild($Description);

      //Recreations
      $Recreations = $xml->createElement("Recreations");
      $AreaInfo->appendChild($Recreations);
        //Recreation
        $Recreation = $xml->createElement("Recreation");
        $Recreations->appendChild($Recreation);

  //AffiliationInfo
  $AffiliationInfo = $xml->createElement( "AreaInfo");
  $AffiliationInfo->setAttribute("LastUpdated", "2004-12-18T15:50:43");
  $HotelDescriptiveContent->appendChild( $AffiliationInfo );

  //ContactInfos
  $ContactInfos = $xml->createElement( "ContactInfos");
  $HotelDescriptiveContent->appendChild( $ContactInfos );

    $ContactInfo = $xml->createElement( "ContactInfo");
    $ContactInfos->appendChild( $ContactInfo );
/////////////////////////////////////////////////////
//Add node
/////////////////////////////////////////////////////














print $xml->saveXML();
$xml->save("1.xml");
?>