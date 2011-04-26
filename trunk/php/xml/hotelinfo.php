<?php
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


?>