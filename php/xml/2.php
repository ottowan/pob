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

//////////////////////////////////////////////////////////////////////////////////////////////
      //HotelInfo
      $HotelInfo = $xml->createElement("HotelInfo");
      $HotelInfo->setAttribute("HotelStatus", "Open");
      $HotelInfo->setAttribute("LastUpdated", "2005-01-14T09:57:59");
      $HotelInfo->setAttribute("Start", "1984-05-19");
      $HotelInfo->setAttribute("WhenBuilt", "1984");
      $HotelDescriptiveContent->appendChild($HotelInfo);

      require_once "hotelinfo.php";


//////////////////////////////////////////////////////////////////////////////////////////////
      //FacilityInfo
      $FacilityInfo = $xml->createElement("FacilityInfo");
      $FacilityInfo->setAttribute("LastUpdated", "2004-12-04T16:00:12");
      $HotelDescriptiveContent->appendChild($FacilityInfo);

      require_once "facilityinfo.php";

//////////////////////////////////////////////////////////////////////////////////////////////
      //Policies
      $Policies = $xml->createElement("Policies");
      $HotelDescriptiveContent->appendChild($Policies);

//////////////////////////////////////////////////////////////////////////////////////////////
      //AreaInfo
      $AreaInfo = $xml->createElement("AreaInfo");
      $HotelDescriptiveContent->appendChild($AreaInfo);

//////////////////////////////////////////////////////////////////////////////////////////////
      //AffiliationInfo
      $AffiliationInfo = $xml->createElement("AffiliationInfo");
      $AffiliationInfo->setAttribute("LastUpdated", "2004-12-04T16:00:12");
      $HotelDescriptiveContent->appendChild($AffiliationInfo);

//////////////////////////////////////////////////////////////////////////////////////////////
      //ContactInfos
      $ContactInfos = $xml->createElement("ContactInfos");
      $HotelDescriptiveContent->appendChild($ContactInfos);

  print $xml->saveXML();
  $xml->save("2.xml");
?>