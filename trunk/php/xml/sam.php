<?php

// Set the content type to be XML, so that the browser will recognise it as XML.
header( "content-type: application/xml; charset=UTF-8" );

// "Create" the document.
$xml = new DOMDocument( "1.0", "UTF-8" );

// Create some elements.
//OTA_HotelDescriptiveContentNotifRQ
$OTA_HotelDescriptiveContentNotifRQ = $xml->createElement( "OTA_HotelDescriptiveContentNotifRQ" );
// Set the attributes.
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd");
$OTA_HotelDescriptiveContentNotifRQ->setAttribute("Version", "2.001");

//HotelDescriptiveContents
$HotelDescriptiveContents = $xml->createElement( "HotelDescriptiveContents");

//HotelDescriptiveContent
$HotelDescriptiveContent = $xml->createElement( "HotelDescriptiveContent");
$HotelDescriptiveContent->setAttribute("BrandCode", "MHRS");
$HotelDescriptiveContent->setAttribute("BrandName", "Marriott Hotels &amp; Resorts");
$HotelDescriptiveContent->setAttribute("CurrencyCode", "USD");
$HotelDescriptiveContent->setAttribute("HotelCode", "BOSCO");
$HotelDescriptiveContent->setAttribute("HotelName", "Boston Marriott Copley Place");
$HotelDescriptiveContent->setAttribute("LanguageCode", "EN");

//HotelInfo
$HotelInfo = $xml->createElement( "HotelInfo");
$HotelInfo->setAttribute("HotelStatus", "Open");
$HotelInfo->setAttribute("LastUpdated", "2005-01-14T09:57:59");
$HotelInfo->setAttribute("Start", "1984-05-19");
$HotelInfo->setAttribute("WhenBuilt", "1984");
  
  $CategoryCodes = $xml->createElement( "CategoryCodes");
    $LocationCategory = $xml->createElement( "LocationCategory");
    $LocationCategory->setAttribute("Code", "3");
    $LocationCategory->setAttribute("CodeDetail", "Location Type: City");

  $Descriptions = $xml->createElement( "Descriptions");
    $Renovation = $xml->createElement( "Renovation");
    $Renovation->setAttribute("ImmediatePlans", "false");
    $Renovation->setAttribute("PercentOfRenovationCompleted", "100"); 

  $Position = $xml->createElement( "Position");
  $Position->setAttribute("Latitude", "42.347996");
  $Position->setAttribute("Longitude", "-71.07869");

  $Services = $xml->createElement( "Services");
    $Service = $xml->createElement( "Service");
    $Service->setAttribute("Code", "103");
      $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
        $MultimediaDescription = $xml->createElement("MultimediaDescription");
          $TextItems = $xml->createElement("TextItems");
            $TextItem = $xml->createElement("TextItem");
            $TextItem->setAttribute("Title", "Language Spoken By Staff");
              $Description = $xml->createElement("Description");

//Policies
$Policies = $xml->createElement( "Policies");
  $Policy = $xml->createElement( "Policy");
    $CancelPolicy = $xml->createElement( "CancelPolicy");
      $CancelPenalty = $xml->createElement( "CancelPenalty");
        $PenaltyDescription = $xml->createElement( "PenaltyDescription");
        $PenaltyDescription->setAttribute("name", "Cancellation Policy");
          $Text = $xml->createElement( "Text");

    $GuaranteePaymentPolicy = $xml->createElement( "GuaranteePaymentPolicy");
      $GuaranteePayment = $xml->createElement( "GuaranteePayment");
      $GuaranteePayment->setAttribute("PaymentCode", "2");

    $PolicyInfoCodes = $xml->createElement( "PolicyInfoCodes");
    $CheckoutCharges = $xml->createElement( "CheckoutCharges");
      $CheckoutCharge = $xml->createElement( "CheckoutCharge");
      $CheckoutCharge->setAttribute("CodeDetail", "Late Check-Out Available");
      $CheckoutCharge->setAttribute("Type", "Late");
        $Description = $xml->createElement( "Description");
        $Description->setAttribute("Name", "Late Check-Out Fees");
          $Text = $xml->createElement( "Text");

    $PolicyInfo = $xml->createElement( "PolicyInfo");
    $PolicyInfo->setAttribute("CheckInTime", "14:00:00");
    $PolicyInfo->setAttribute("CheckOutTime", "12:00:00");
    $PolicyInfo->setAttribute("KidsStayFree", "true");
    $PolicyInfo->setAttribute("TotalGuestCount", "5");
    $TaxPolicies = $xml->createElement( "TaxPolicies");
      $TaxPolicy = $xml->createElement( "TaxPolicy");
      $TaxPolicy->setAttribute("Amount", "5");
      $TaxPolicy->setAttribute("Code", "7");
      $TaxPolicy->setAttribute("Code", "10");
      $TaxPolicy->setAttribute("NightsForTaxExemptionQuantity", "90");
      $TaxPolicy->setAttribute("Percent", "5.7");
      $TaxPolicy->setAttribute("Code", "17");
      $TaxPolicy->setAttribute("Percent", "12.45");
      $TaxPolicy->setAttribute("Amount", "2.75");
     $TaxPolicy->setAttribute("Code", "27");


//AreaInfo
$AreaInfo = $xml->createElement( "AreaInfo");
  $RefPoints = $xml->createElement( "RefPoints");
    $RefPoint = $xml->createElement( "RefPoint");
    $RefPoint->setAttribute("Distance", "3");
    $RefPoint->setAttribute("IndexPointCode", "3");
    $RefPoint->setAttribute("Name", "Expway, Route 93, Mass. Turnpike");
      $Transportations = $xml->createElement( "Transportations");
        $Transportation = $xml->createElement( "Transportation");
          $MultimediaDescriptions = $xml->createElement( "MultimediaDescriptions");
            $MultimediaDescription = $xml->createElement( "MultimediaDescription");
              $TextItems = $xml->createElement( "TextItems");
                $TextItem = $xml->createElement( "TextItem");
                $TextItem->setAttribute("Title", "Directions to Highway from Property");


  $Attractions = $xml->createElement( "Attractions");
  $Attractions->setAttribute("LastUpdated", "2004-11-10T15:50:30");
    $Attraction = $xml->createElement( "Attraction");
    $Attraction->setAttribute("AttractionCategoryCode", "1");
    $Attraction->setAttribute("AttractionName", "Boston");
    $Attraction->setAttribute("Code", "BOS");
    $Attraction->setAttribute("CourtesyPhone", "true");
    $Attraction->setAttribute("AttractionCategoryCode", "BOS");
    $Attraction->setAttribute("AttractionName", "Quincy Market/Faneuil Hal");
      $Contact = $xml->createElement( "Contact");
        $Addresses = $xml->createElement( "Addresses");
          $CityName = $xml->createElement( "CityName");
          $StateProv = $xml->createElement( "CountryName");
        $Phones = $xml->createElement( "Phones");
          $Phone = $xml->createElement( "Phone");
          $Phone->setAttribute("PhoneLocationType", "2");
          $Phone->setAttribute("PhoneNumber", "8002353426");
          $Phone->setAttribute("PhoneTechType", "1");
          $Phone->setAttribute("PhoneUseType", "5");
        $MultimediaDescriptions = $xml->createElement( "MultimediaDescriptions");
          $MultimediaDescription = $xml->createElement( "MultimediaDescription");
            $TextItems = $xml->createElement( "TextItems");
              $TextItem = $xml->createElement( "TextItem");
              $TextItem->setAttribute("Title", "Sort Order");
                $Description = $xml->createElement( "Description");

//AffiliationInfo
$AffiliationInfo = $xml->createElement( "AreaInfo");
$AffiliationInfo->setAttribute("LastUpdated", "2004-12-18T15:50:43");

//ContactInfos
$ContactInfos = $xml->createElement( "ContactInfos");

$xml->appendChild( $OTA_HotelDescriptiveContentNotifRQ );
$OTA_HotelDescriptiveContentNotifRQ->appendChild( $HotelDescriptiveContents );
  $HotelDescriptiveContents->appendChild( $HotelDescriptiveContent );
    $HotelDescriptiveContent->appendChild( $HotelInfo );
      $HotelInfo->appendChild( $CategoryCodes );
        $CategoryCodes->appendChild( $LocationCategory );
      $HotelInfo->appendChild( $Descriptions );
        $Descriptions->appendChild( $Renovation );

      $HotelInfo->appendChild( $Position );
      $HotelInfo->appendChild( $Services );
        $Services->appendChild( $Service );
          $Service->appendChild( $MultimediaDescriptions );
            $MultimediaDescriptions->appendChild( $MultimediaDescription );
              $MultimediaDescription->appendChild( $TextItems );
                $TextItems->appendChild( $TextItem );
                  $TextItem->appendChild( $Description );

$HotelDescriptiveContent->appendChild( $Policies );
  $Policies->appendChild( $Policy );
    $Policy->appendChild( $CancelPolicy );
      $CancelPolicy->appendChild( $CancelPenalty );
        $CancelPenalty->appendChild( $PenaltyDescription );
          $PenaltyDescription->appendChild( $Text );
    $Policy->appendChild( $GuaranteePaymentPolicy );
      $GuaranteePaymentPolicy->appendChild( $GuaranteePayment );

    $Policy->appendChild( $PolicyInfoCodes );
    $Policy->appendChild( $CheckoutCharges );
      $CheckoutCharges->appendChild( $CheckoutCharge );
        $CheckoutCharge->appendChild( $Description );
          $Description->appendChild( $Text );
    $Policy->appendChild( $PolicyInfo );
    $Policy->appendChild( $TaxPolicies );
      $TaxPolicies->appendChild( $TaxPolicy );

$HotelDescriptiveContent->appendChild( $AreaInfo );
  $AreaInfo->appendChild( $RefPoints );
    $RefPoints->appendChild( $RefPoint );
      $RefPoint->appendChild( $Transportations );
        $Transportations->appendChild( $Transportation );
          $Transportation->appendChild( $MultimediaDescriptions );
            $MultimediaDescriptions->appendChild( $MultimediaDescription );
              $MultimediaDescription->appendChild( $TextItems );
                $TextItems->appendChild( $TextItem );

  $AreaInfo->appendChild( $Attractions );
    $Attractions->appendChild( $Attraction );
      $Attraction->appendChild( $Contact );
        $Contact->appendChild( $Addresses );
          $Addresses->appendChild( $CityName );
          $Addresses->appendChild( $StateProv );
          $Addresses->appendChild( $CountryName );
        $Contact->appendChild( $Phones );
          $Phones->appendChild( $Phone );
      $Attraction->appendChild( $MultimediaDescriptions );
      $MultimediaDescriptions->appendChild( $MultimediaDescription );
        $MultimediaDescription->appendChild( $TextItems );
          $TextItems->appendChild( $TextItem );
            $TextItem->appendChild( $Description );

      $Attraction->appendChild( $RefPoints );

  $AreaInfo->appendChild( $Recreations );

$HotelDescriptiveContent->appendChild( $AffiliationInfo );
$HotelDescriptiveContent->appendChild( $ContactInfos );

print $xml->saveXML();

?>
