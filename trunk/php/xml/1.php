<?php

// Set the content type to be XML, so that the browser will recognise it as XML.
//header( "content-type: application/xml; charset=UTF-8" );

// "Create" the document.
$xml = new DOMDocument( "1.0", "UTF-8" );
$xml->preserveWhiteSpace = false; 
$xml->formatOutput = true;

// Create some elements.
//OTA_HotelDescriptiveContentNotifRQ
$OTA_HotelDescriptiveContentNotifRQ = $xml->createElement( "OTA_HotelDescriptiveContentNotifRQ" );
// Set the attributes.
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd");
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("Version", "2.001");

$xml->appendChild( $OTA_HotelDescriptiveContentNotifRQ );

//HotelDescriptiveContents
$HotelDescriptiveContents = $xml->createElement( "HotelDescriptiveContents");
$OTA_HotelDescriptiveContentNotifRQ->appendChild( $HotelDescriptiveContents );

//HotelDescriptiveContent
$HotelDescriptiveContent = $xml->createElement( "HotelDescriptiveContent");
$HotelDescriptiveContent->setAttribute("BrandCode", "MHRS");
$HotelDescriptiveContent->setAttribute("BrandName", "Marriott Hotels &amp; Resorts");
$HotelDescriptiveContent->setAttribute("CurrencyCode", "USD");
$HotelDescriptiveContent->setAttribute("HotelCode", "BOSCO");
$HotelDescriptiveContent->setAttribute("HotelName", "Boston Marriott Copley Place");
$HotelDescriptiveContent->setAttribute("LanguageCode", "EN");
  $HotelDescriptiveContents->appendChild( $HotelDescriptiveContent );

//HotelInfo
$HotelInfo = $xml->createElement( "HotelInfo");
$HotelInfo->setAttribute("HotelStatus", "Open");
$HotelInfo->setAttribute("LastUpdated", "2005-01-14T09:57:59");
$HotelInfo->setAttribute("Start", "1984-05-19");
$HotelInfo->setAttribute("WhenBuilt", "1984");
    $HotelDescriptiveContent->appendChild( $HotelInfo );

/*      $HotelInfo->appendChild( $CategoryCodes );
        $CategoryCodes->appendChild( $LocationCategory );
      $HotelInfo->appendChild( $Descriptions );
        $Descriptions->appendChild( $Renovation );
*/
$xml->saveXML();
$xml->save("1.xml")
?>