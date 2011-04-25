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

        //AreaInfo
        $AreaInfo = $xml->createElement("AreaInfo");
        $OTA_HotelDescriptiveContentNotifRQ->appendChild($AreaInfo);

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

              $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
              $Transportation->appendChild($MultimediaDescriptions);

                $MultimediaDescription = $xml->createElement("MultimediaDescription");
                $MultimediaDescriptions->appendChild($MultimediaDescription);

                  $TextItems = $xml->createElement("TextItems");
                  $MultimediaDescription->appendChild($TextItems);

                    $TextItem = $xml->createElement("TextItem");
                    $TextItem->setAttribute("Title", "Directions to Highway from Property");
                    $TextItems->appendChild($TextItem);

                      $Description = $xml->createElement("Description", "Please call the hotel for directions");
                      $TextItem->appendChild($Description);
/*
*/

print $xml->saveXML();
$xml->save("test.xml");

?>