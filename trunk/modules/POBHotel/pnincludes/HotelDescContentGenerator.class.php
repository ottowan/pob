<?php
  /**
  * 
  * 
  * 
  */
Class HotelDescContentGenerator {
  private $hotelObject = NULL;
  private $locationObject = NULL;
  private $amenity = NULL;
  
  function __construct($hotelId=''){
    $pntables = pnDBGetTables();


    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelArray] ...');
      
    $hotelObjectArray = new $class;
    $hotelObjectArray->get(' WHERE hotel_id = '.$hotelId);
    $this->hotelObject = $hotelObjectArray->_objData[0];
    
    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelLocationArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelLocationArray] ...');
    
    $locationObjectArray = new $class;
    $locationObjectArray->get(' WHERE '.$pntables['pobhotel_hotel_location_column']["hotel_id"].' = '.$hotelId);
    $this->locationObject = $locationObjectArray->_objData;
    
    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelAmenityArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelAmenityArray] ...');
    
    $amenityObjectArray = new $class;
    $amenityObjectArray->get(' WHERE '.$pntables['pobhotel_hotel_amenity_column']["hotel_id"].' = '.$hotelId);
    $this->amenity = $amenityObjectArray->_objData;
    
    
    
    
    
    //var_dump($this->hotelObject);
    //var_dump($this->locationObject);
    //var_dump($this->amenity);
  }
  public function getContent(){
    return $this->genHotelDescriptive();
  }
  private function genHotelDescriptive(){
    $xml = new DOMDocument('1.0','utf-8');
    $xml->formatOutput = true;
      //OTA_HotelDescriptiveContentNotifRQ
    $OTA_HotelDescriptiveContentNotifRQ = $xml->createElement("OTA_HotelDescriptiveContentNotifRQ");
    // Set the attributes.
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("Version", "2.001");

    $HotelDescriptiveContents = $xml->createElement("HotelDescriptiveContents");
    $HotelDescriptiveContent = $xml->createElement("HotelDescriptiveContent");
    
    $HotelDescriptiveContent->setAttribute("BrandCode","MHRS");
    $HotelDescriptiveContent->setAttribute("BrandName",$this->hotelObject["name"]);
    $HotelDescriptiveContent->setAttribute("CurrencyCode","THB");
    $HotelDescriptiveContent->setAttribute("HotelCode","BOSCO");
    $HotelDescriptiveContent->setAttribute("HotelName",$this->hotelObject["descriptions"]);
    $HotelDescriptiveContent->setAttribute("LanguageCode","TH");
    
    $LoadedXML = DOMDocument::loadXML($this->genHotelInfo());
    $HotelInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("HotelInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($HotelInfoNode);
    
    $LoadedXML = DOMDocument::loadXML($this->genFacilityInfo());
    $FacilityInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("FacilityInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($FacilityInfoNode);
    
    $LoadedXML = DOMDocument::loadXML($this->genPolicies());
    $PoliciesNode = $xml->importNode($LoadedXML->getElementsByTagName("Policies")->item(0), true);
    $HotelDescriptiveContent->appendChild($PoliciesNode);
    
    $LoadedXML = DOMDocument::loadXML($this->genAreaInfo());
    $AreaInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("AreaInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($AreaInfoNode);
    
    $LoadedXML = DOMDocument::loadXML($this->genContractInfo());
    $ContractInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("ContractInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($ContractInfoNode);
    
    $HotelDescriptiveContents->appendChild($HotelDescriptiveContent);
    $OTA_HotelDescriptiveContentNotifRQ->appendChild($HotelDescriptiveContents);
    $xml->appendChild($OTA_HotelDescriptiveContentNotifRQ);
    
    return $xml;
  }
  private function genFacilityInfo(){
    $xml = new DOMDocument();
    $xml->formatOutput = true;
    $HotelInfo = $xml->createElement("FacilityInfo");
    $xml->appendChild($HotelInfo);
    return $xml->saveXML();
  }
  private function genHotelInfo(){
    $xml = new DOMDocument();
    //$xml->formatOutput = true;
    $HotelInfo = $xml->createElement("HotelInfo");
    $HotelInfo->setAttribute("HotelStatus",$this->hotelObject["status_name"]);
    $HotelInfo->setAttribute("LastUpdated",str_replace(" ","T",$this->hotelObject["lu_date"]));
    $HotelInfo->setAttribute("Start",$this->hotelObject["start"]);
    $HotelInfo->setAttribute("WhenBuilt",substr($this->hotelObject["when_built"],0,4));
    
    $CategoryCodes = $xml->createElement("CategoryCodes");

    foreach($this->locationObject AS $key=>$value){
      $LocationCategory = $xml->createElement("LocationCategory");
      $LocationCategory->setAttribute("Code",$value["location_id"]);
      $LocationCategory->setAttribute("CodeDetail","Location Type: ".$value["location_name"]);
      
      $CategoryCodes->appendChild($LocationCategory);
    }
    
    $Position = $xml->createElement("Position");
    $Position->setAttribute("Latitude",$this->hotelObject["position_latitude"]);
    $Position->setAttribute("Longitude",$this->hotelObject["position_longitude"]);
    
    $Services = $xml->createElement("Services");
    foreach($this->amenity AS $key=>$value){
      $Service = $xml->createElement("Service");
      $Service->setAttribute("Code",$value['amenity_id']);
      $Services->appendChild($Service);
    }


    $HotelInfo->appendChild($CategoryCodes);
    $HotelInfo->appendChild($Position);
    $HotelInfo->appendChild($Services);
    $xml->appendChild($HotelInfo);
    return $xml->saveXML();
  }
  private function genPolicies(){
    $xml = new DOMDocument();
    $xml->formatOutput = true;
    $HotelInfo = $xml->createElement("Policies");
    $xml->appendChild($HotelInfo);
    return $xml->saveXML();
  }
  private function genAreaInfo(){
    $xml = new DOMDocument();
    $xml->formatOutput = true;
    $HotelInfo = $xml->createElement("AreaInfo");
    $xml->appendChild($HotelInfo);
    return $xml->saveXML();
  }
  private function genContractInfo(){
    $xml = new DOMDocument();
    $xml->formatOutput = true;
    $HotelInfo = $xml->createElement("ContractInfo");
    $xml->appendChild($HotelInfo);
    return $xml->saveXML();
  }

}
?>