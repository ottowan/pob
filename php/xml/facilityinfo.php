<?php

        $MeetingRooms = $xml->createElement("MeetingRooms");
        $MeetingRooms->setAttribute("LargestRoomSpace", "23431");
        $MeetingRooms->setAttribute("LargestSeatingCapacity", "3640");
        $MeetingRooms->setAttribute("MeetingRoomCount", "1");
        $MeetingRooms->setAttribute("TotalRoomSpace", "65000");
        $MeetingRooms->setAttribute("UnitOfMeasure", "Square Feet");
        $FacilityInfo->appendChild($MeetingRooms);

          $MeetingRoom = $xml->createElement("MeetingRoom");
          $MeetingRoom->setAttribute("Irregular", "false");
          $MeetingRoom->setAttribute("RoomName", "Grand Ballroom");
          $MeetingRooms->appendChild($MeetingRoom);

          $Dimension = $xml->createElement("Dimension");
          $Dimension->setAttribute("Area", "23431");
          $Dimension->setAttribute("Height", "18.1");
          $Dimension->setAttribute("Length", "108");
          $Dimension->setAttribute("Width", "221");
          $MeetingRoom->appendChild($Dimension);

          $AvailableCapacities = $xml->createElement("AvailableCapacities");
          $MeetingRoom->appendChild($AvailableCapacities);

            $MeetingRoomCapacity = $xml->createElement("MeetingRoomCapacity");
            $MeetingRoomCapacity->setAttribute("MeetingRoomFormatCode", "1");
            $AvailableCapacities->appendChild($MeetingRoomCapacity);
              $Occupancy = $xml->createElement("Occupancy");
              $Occupancy->setAttribute("MaxOccupancy", "1990");
              $MeetingRoomCapacity->appendChild($Occupancy);

            $MeetingRoomCapacity = $xml->createElement("MeetingRoomCapacity");
            $MeetingRoomCapacity->setAttribute("MeetingRoomFormatCode", "2");
            $AvailableCapacities->appendChild($MeetingRoomCapacity);
              $Occupancy = $xml->createElement("Occupancy");
              $Occupancy->setAttribute("MaxOccupancy", "1866");
              $MeetingRoomCapacity->appendChild($Occupancy);

            $MeetingRoomCapacity = $xml->createElement("MeetingRoomCapacity");
            $MeetingRoomCapacity->setAttribute("MeetingRoomFormatCode", "5");
            $AvailableCapacities->appendChild($MeetingRoomCapacity);
              $Occupancy = $xml->createElement("Occupancy");
              $Occupancy->setAttribute("MaxOccupancy", "3138");
              $MeetingRoomCapacity->appendChild($Occupancy);

            $MeetingRoomCapacity = $xml->createElement("MeetingRoomCapacity");
            $MeetingRoomCapacity->setAttribute("MeetingRoomFormatCode", "7");
            $AvailableCapacities->appendChild($MeetingRoomCapacity);
              $Occupancy = $xml->createElement("Occupancy");
              $Occupancy->setAttribute("MaxOccupancy", "3640");
              $MeetingRoomCapacity->appendChild($Occupancy);


          $Codes = $xml->createElement("Codes");
          $MeetingRooms->appendChild($Codes);

            $Code = $xml->createElement("Code");
            $Code->setAttribute("Code", "92");
            $Codes->appendChild($Code);

              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Code->appendChild($MultimediaDescriptions);

                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);

                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);

                  $TextItem = $xml->createElement("TextItem");
                  $TextItem->setAttribute("Title", "Other Equipment & Facilities Available in Meeting Room");
                  $TextItems->appendChild($TextItem);


                    $Description = $xml->createElement("Description", "Red Coat Program, Event Services Hotline");
                    $MultimediaDescription->appendChild($Description);

        $GuestRooms = $xml->createElement("GuestRooms");
        $FacilityInfo->appendChild($GuestRooms);

          $GuestRoom = $xml->createElement("GuestRoom");
          $GuestRooms->appendChild($GuestRoom);

            $Amenities = $xml->createElement("Amenities");
            $GuestRoom->appendChild($Amenities);


            $Amenity = $xml->createElement("Amenity");
              $Amenity->setAttribute("RoomAmenityCode", "11");
                $Amenities->appendChild($Amenity);

                  $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
                  $Amenity->appendChild($MultimediaDescriptions);

                    $MultimediaDescription = $xml->createElement("MultimediaDescription");
                    $MultimediaDescriptions->appendChild($MultimediaDescription);

                      $TextItems = $xml->createElement("TextItems");
                      $MultimediaDescription->appendChild($TextItems);

                        $TextItem = $xml->createElement("TextItem");
                        $TextItem->setAttribute("Title", "Bathroom Amenities");
                        $MultimediaDescription->appendChild($TextItem);

                          $Description = $xml->createElement("Description", "Soap, shampoo, rinse, shower cap, lotions");
                          $TextItem->appendChild($Description);

        $GuestRoom = $xml->createElement("GuestRoom");
        $GuestRoom->setAttribute("CodeContext", "MARSHA Room Type");
        $GuestRoom->setAttribute("MaxOccupancy", "5");
        $GuestRoom->setAttribute("NonsmokingQuantity", "923");
        $GuestRoom->setAttribute("Quantity", "1033");
        $GuestRoom->setAttribute("RoomTypeName", "General Rooms");
        $GuestRooms->appendChild($GuestRoom);

          $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
          $GuestRoom->appendChild($MultimediaDescriptions);

            $MultimediaDescription = $xml->createElement("MultimediaDescription");
            $MultimediaDescriptions->appendChild($MultimediaDescription);

              $TextItems = $xml->createElement("TextItems");
              $MultimediaDescription->appendChild($TextItems);

                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Sort Order");
                $MultimediaDescription->appendChild($TextItem);

                  $Description = $xml->createElement("Description", "1");
                  $TextItem->appendChild($Description);

                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Features of Room Type");
                $MultimediaDescription->appendChild($TextItem);

                  $Description = $xml->createElement("Description", "GENR Standard 1 King Bed/or 2 Dbl beds, Coffee Maker, Hair Dryer, Desk w/ chair");
                  $TextItem->appendChild($Description);

        $GuestRoom = $xml->createElement("GuestRoom");
        $GuestRoom->setAttribute("CodeContext", "MARSHA Room Type");
        $GuestRoom->setAttribute("MaxOccupancy", "5");
        $GuestRoom->setAttribute("NonsmokingQuantity", "58");
        $GuestRoom->setAttribute("Quantity", "67");
        $GuestRoom->setAttribute("RoomTypeName", "Concierge");
        $GuestRooms->appendChild($GuestRoom);

          $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
          $GuestRoom->appendChild($MultimediaDescriptions);

            $MultimediaDescription = $xml->createElement("MultimediaDescription");
            $MultimediaDescriptions->appendChild($MultimediaDescription);

              $TextItems = $xml->createElement("TextItems");
              $MultimediaDescription->appendChild($TextItems);

                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Sort Order");
                $MultimediaDescription->appendChild($TextItem);

                  $Description = $xml->createElement("Description", "2");
                  $TextItem->appendChild($Description);

                $TextItem = $xml->createElement("TextItem");
                $TextItem->setAttribute("Title", "Features of Room Type");
                $MultimediaDescription->appendChild($TextItem);

                  $Description = $xml->createElement("Description", "CONC Concierge Upgraded Amenities &amp; Private Lounge, 1 King bed or 2 Double beds");
                  $TextItem->appendChild($Description);



        $Restaurants1 = $xml->createElement("Restaurants");
        $FacilityInfo->appendChild($Restaurants1);

          $Restaurant = $xml->createElement("Restaurant");
          $TextItem->setAttribute("OfferBreakfast", "true");
          $TextItem->setAttribute("OfferDinner", "true");
          $TextItem->setAttribute("OfferLunch", "true");
          $TextItem->setAttribute("RestaurantName", "Gourmeli&s (Casual)");
          $Restaurants1->appendChild($Restaurant);


?>