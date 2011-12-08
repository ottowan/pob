<?php
  /**
  *
  *
  *
  */
Class HotelDescContentGenerator {
  private $hotelObject = NULL;
  private $locationObject = NULL;
  private $hotelAmenity = NULL;
  private $facilityInfoObject = NULL;

  function __construct($hotelId=''){

    $pntables = pnDBGetTables();

    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelArray] ...');

    $objectArray = new $class;
    $objectArray->get(' WHERE hotel_id = '.$hotelId);
    $this->hotelObject = $objectArray->_objData[0];

    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelLocationArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelLocationArray] ...');

    $objectArray = new $class;
    $objectArray->get();
    $this->locationObject = $objectArray->_objData;

    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelAmenityArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelAmenityArray] ...');

    $objectArray = new $class;
    $objectArray->get();
    $this->hotelAmenity = $objectArray->_objData;

    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelImageArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelImageArray] ...');

    $objectArray = new $class;
    $objectArray->get();
    $this->imageObject  = $objectArray->_objData;

    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelAttractionArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelAttractionArray] ...');

    $objectArray = new $class;
    $objectArray->get();
    $this->facilityInfoObject  = $objectArray->_objData;

  }
  public function getContent(){
    return $this->genHotelDescriptive();
  }
  public function sendContent(){
    $url = 'http://pob-ws.heroku.com/api/hotel_descriptive_content_notif';
    $data = $this->genHotelDescriptive();
    $data = $data->saveXML();
    //header("Content-type: text/xml");
    //print $data;
    //exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
  }
  private function genHotelDescriptive(){
    $xml = new DOMDocument('1.0','utf-8');
    //$xml->formatOutput = true;
      //OTA_HotelDescriptiveContentNotifRQ
    $OTA_HotelDescriptiveContentNotifRQ = $xml->createElement("OTA_HotelDescriptiveContentNotifRQ");
    // Set the attributes.
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("Version", "2.001");

    $POS = $xml->createElement("POS");
    $OTA_HotelDescriptiveContentNotifRQ->appendChild($POS);
    $Source = $xml->createElement("Source");
    $POS->appendChild($Source);
    $RequestorID = $xml->createElement("RequestorID");
    $RequestorID->setAttribute("Type", "1");
    $RequestorID->setAttribute("ID", "638fdJa7vRmkLs5");
    $Source->appendChild($RequestorID);

    $HotelDescriptiveContents = $xml->createElement("HotelDescriptiveContents");
    $HotelDescriptiveContent = $xml->createElement("HotelDescriptiveContent");

    $HotelDescriptiveContent->setAttribute("BrandCode",htmlentities($this->hotelObject["name"]));
    $HotelDescriptiveContent->setAttribute("BrandName",htmlentities($this->hotelObject["name"]));
    $HotelDescriptiveContent->setAttribute("CurrencyCode","THB");
    $HotelDescriptiveContent->setAttribute("HotelCode",htmlentities($this->hotelObject["code"]));
    $HotelDescriptiveContent->setAttribute("HotelName",htmlentities($this->hotelObject["name"]));
    $HotelDescriptiveContent->setAttribute("LanguageCode","TH");

    if(!is_null($this->hotelObject)){
      $LoadedXML = DOMDocument::loadXML($this->genHotelInfo());
      $HotelInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("HotelInfo")->item(0), true);
      $HotelDescriptiveContent->appendChild($HotelInfoNode);
    }

    $LoadedXML = DOMDocument::loadXML($this->genContractInfo());
    $ContractInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("ContactInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($ContractInfoNode);


    $LoadedXML = DOMDocument::loadXML($this->genPolicy());
    $PoliciesNode = $xml->importNode($LoadedXML->getElementsByTagName("Policies")->item(0), true);
    $HotelDescriptiveContent->appendChild($PoliciesNode);
	
    $HotelDescriptiveContents->appendChild($HotelDescriptiveContent);
    $OTA_HotelDescriptiveContentNotifRQ->appendChild($HotelDescriptiveContents);
    $xml->appendChild($OTA_HotelDescriptiveContentNotifRQ);
    return $xml;
  }
  private function genHotelInfo(){

    $xml = new DOMDocument('1.0','utf-8');
    //$xml->formatOutput = true;
    $xml->encoding = 'utf-8';


    $HotelInfo = $xml->createElement("HotelInfo");
    $HotelInfo->setAttribute("HotelStatus",htmlentities($this->hotelObject["status_name"]));
    $HotelInfo->setAttribute("LastUpdated",str_replace(" ","T",$this->hotelObject["lu_date"]));
    $HotelInfo->setAttribute("Start",htmlentities($this->hotelObject["start"]));
    $HotelInfo->setAttribute("WhenBuilt",substr($this->hotelObject["when_built"],0,4));

    $CategoryCodes = $xml->createElement("CategoryCodes");

    foreach($this->locationObject AS $key=>$value){
      if(!is_null($value["location_id"])){
        $LocationCategory = $xml->createElement("LocationCategory");
        $LocationCategory->setAttribute("Code",$value["location_id"]);
        $LocationCategory->setAttribute("CodeDetail","Location Type: ".htmlentities($value["location_name"]));

        $CategoryCodes->appendChild($LocationCategory);
      }
    }

    $Position = $xml->createElement("Position");
    $Position->setAttribute("Latitude",$this->hotelObject["position_latitude"]);
    $Position->setAttribute("Longitude",$this->hotelObject["position_longitude"]);

    $Services = $xml->createElement("Services");

    foreach($this->hotelAmenity AS $key=>$value){
      $Service = $xml->createElement("Service");
      $Service->setAttribute("Code",$value['amenity_id']);
      $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
      $MultimediaDescription = $xml->createElement("MultimediaDescription");
      $TextItems = $xml->createElement("TextItems");
      $TextItem = $xml->createElement("TextItem");
      $TextItem->setAttribute("Title",htmlentities($value["amenity_name"]));
      $Description = $xml->createElement("Description",$value["description"]);
      $TextItem->appendChild($Description);
      $TextItems->appendChild($TextItem);
      $MultimediaDescription->appendChild($TextItems);
      $MultimediaDescriptions->appendChild($MultimediaDescription);
      $Service->appendChild($MultimediaDescriptions);
      $Services->appendChild($Service);
    }

    $Descriptions = $xml->createElement("Descriptions");
    $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");

    $MultimediaDescription = $xml->createElement("MultimediaDescription");
    $TextItems = $xml->createElement("TextItems");
    
    $TextItemTH = $xml->createElement("TextItem");
    $TextItemTH->setAttribute("Title","Description");
    $TextItemTH->setAttribute("Language",'th');
    $DescriptionTH = $xml->createElement("Description",$this->hotelObject["descriptions"]);
    $TextItemTH->appendChild($DescriptionTH);
    
    
    $TextItemEN = $xml->createElement("TextItem");
    $TextItemEN->setAttribute("Title","Description");
    $TextItemEN->setAttribute("Language",'en');
    $DescriptionEN = $xml->createElement("Description",$this->hotelObject["descriptions_en"]);
    $TextItemEN->appendChild($DescriptionEN);
    
    $TextItems->appendChild($TextItemTH);
    $TextItems->appendChild($TextItemEN);

    $MultimediaDescription->appendChild($TextItems);
    $MultimediaDescriptions->appendChild($MultimediaDescription);
    $MultimediaDescription = $xml->createElement("MultimediaDescription");
    $TextItems = $xml->createElement("TextItems");
    foreach($this->facilityInfoObject AS $key=>$value){
      $TextItem = $xml->createElement("TextItem");
      $TextItem->setAttribute("Title","FacilityInfo");
      $TextItem->setAttribute("Name",htmlentities($value["attraction_name"]));
      $Description = $xml->createElement("Description",$value["description"]);
      $TextItem->appendChild($Description);
      $TextItems->appendChild($TextItem);
    }
    
    $MultimediaDescription->appendChild($TextItems);
    $MultimediaDescriptions->appendChild($MultimediaDescription);

    $MultimediaDescription = $xml->createElement("MultimediaDescription");
    $TextItems = $xml->createElement("TextItems");
    $TextItem = $xml->createElement("TextItem");
    $TextItem->setAttribute("Title","Rating");
    $Description = $xml->createElement("Description",htmlentities($this->hotelObject["rating"]));
    $TextItem->appendChild($Description);
    $TextItems->appendChild($TextItem);
    $MultimediaDescription->appendChild($TextItems);
    $MultimediaDescriptions->appendChild($MultimediaDescription);

    if($this->imageObject){
      // Image
      $MultimediaDescription = $xml->createElement("MultimediaDescription");
      $ImageItems = $xml->createElement("ImageItems");
      $ImageItem = $xml->createElement("ImageItem");
      // Original
      foreach($this->imageObject AS $key=>$value){

        $ImageItem->setAttribute("Category",6);
        $ImageFormat = $xml->createElement("ImageFormat");
        $Url = $xml->createElement("URL",htmlentities("http://images.phuketcity.com/".$value["filepath"].$value["filename"]));
        $ImageFormat->appendChild($Url);
        $ImageItem->appendChild($ImageFormat);

      }
      $ImageItems->appendChild($ImageItem);

      // Thumbnail
      $ImageItem = $xml->createElement("ImageItem");
      foreach($this->imageObject AS $key=>$value){

        $ImageItem->setAttribute("Category",1);
        $ImageFormat = $xml->createElement("ImageFormat");
        $Url = $xml->createElement("URL",htmlentities("http://images.phuketcity.com/".$value["thumbpath"].$value["thumbname"]));
        $ImageFormat->appendChild($Url);
        $ImageItem->appendChild($ImageFormat);

      }
      $ImageItems->appendChild($ImageItem);
      $MultimediaDescription->appendChild($ImageItems);
      $MultimediaDescriptions->appendChild($MultimediaDescription);
    }

    $Descriptions->appendChild($MultimediaDescriptions);

    $HotelInfo->appendChild($CategoryCodes);
    $HotelInfo->appendChild($Descriptions);
    $HotelInfo->appendChild($Position);
    $HotelInfo->appendChild($Services);
    $xml->appendChild($HotelInfo);
    //header("Content-type: text/xml");
    return $xml->saveXML();

  }
  private function genContractInfo(){

      $xml = new DOMDocument('1.0','utf-8');

      $ContractInfo = $xml->createElement("ContactInfo");
      $ContractInfo->setAttribute("ContactProfileType","Property Info");

      $Addresses = $xml->createElement("Addresses");

      $Address = $xml->createElement("Address");
      $Address->setAttribute("UseType",7);
      $AddressLine = $xml->createElement("AddressLine",$this->hotelObject["address_line"]);
      $CityName = $xml->createElement("CityName",$this->hotelObject["city_name"]);
      $PostalCode = $xml->createElement("PostalCode",$this->hotelObject["postal_code"]);
      $StateProv = $xml->createElement("StateProv");
      $StateProv->setAttribute("StateCode",$this->hotelObject["state_province"]);
      $CountryName = $xml->createElement("CountryName",$this->hotelObject["country"]);

      $Email = $xml->createElement("Email",$this->hotelObject["email"]);


      //Phone Section
      $Phones = $xml->createElement("Phones");

      $Phone = $xml->createElement("Phone");
      $Phone->setAttribute("PhoneNumber",$this->hotelObject["phone_number"]);
      $Phone->setAttribute("PhoneTechType",1);

      $Mobile = $xml->createElement("Phone");
      $Mobile->setAttribute("PhoneNumber",$this->hotelObject["mobile_number"]);
      $Mobile->setAttribute("PhoneTechType",5);

      $Fax = $xml->createElement("Phone");
      $Fax->setAttribute("PhoneNumber",$this->hotelObject["fax_number"]);
      $Fax->setAttribute("PhoneTechType",3);

      $Phones->appendChild($Phone);
      $Phones->appendChild($Mobile);
      $Phones->appendChild($Fax);

      $Address->appendChild($AddressLine);
      $Address->appendChild($CityName);
      $Address->appendChild($PostalCode);
      $Address->appendChild($StateProv);
      $Address->appendChild($CountryName);
      $Address->appendChild($Email);
      $Addresses->appendChild($Address);
      $ContractInfo->appendChild($Addresses);
      $xml->appendChild($ContractInfo);


      return $xml->saveXML();
  }

  private function genPolicy(){
    if(isset($this->hotelObject["policy"])){
      $policy = $this->hotelObject["policy"];
    }else{
      $policy = "Cancellations or changes to bookings for this room must be made at least 72 hours prior to the check in date and time or you will be charged the full amount of the first changed or cancelled night.";
    }
    if(isset($this->hotelObject["policy_en"])){
      $policy_en = $this->hotelObject["policy_en"];
    }else{
      $policy_en = "Cancellations or changes to bookings for this room must be made at least 72 hours prior to the check in date and time or you will be charged the full amount of the first changed or cancelled night.";
    }


    $xml = new DOMDocument('1.0','utf-8');

    $Policies = $xml->createElement("Policies");
    $Policy = $xml->createElement("Policy");
    $CancelPolicy = $xml->createElement("CancelPolicy");
    $CancelPenalty = $xml->createElement("CancelPenalty");
    
    $PenaltyDescriptionTH = $xml->createElement("PenaltyDescription");
    $TextTH = $xml->createElement("Text",$policy);
    $TextTH->setAttribute("Language",'th');
    $PenaltyDescriptionTH->appendChild($TextTH);
    
    $PenaltyDescriptionEN = $xml->createElement("PenaltyDescription");
    $TextEN = $xml->createElement("Text",$policy_en);
    $TextEN->setAttribute("Language",'en');
    $PenaltyDescriptionEN->appendChild($TextEN);
    
    $CancelPenalty->appendChild($PenaltyDescriptionTH);
    $CancelPenalty->appendChild($PenaltyDescriptionEN);
    $CancelPolicy->appendChild($CancelPenalty);
    $Policy->appendChild($CancelPolicy);
    $Policies->appendChild($Policy);
    $xml->appendChild($Policies);
    return $xml->saveXML();
  }
}
?>