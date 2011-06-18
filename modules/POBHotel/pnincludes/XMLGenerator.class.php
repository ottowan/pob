<?php
  /**
  * 
  * 
  * 
  */
Class HotelDescContentGenerator {
  private $hotelObject = '';
  function __construct(){
    
  }
  public function getContent(){
    return $this->genHotelDescriptive();
  }
  private function genHotelDescriptive(){
    $xml = new DOMDocument('1.0','utf-8');
      //OTA_HotelDescriptiveContentNotifRQ
    $OTA_HotelDescriptiveContentNotifRQ = $xml->createElement("OTA_HotelDescriptiveContentNotifRQ");
    // Set the attributes.
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("Version", "2.001");

    $HotelDescriptiveContents = $xml->createElement("HotelDescriptiveContents","HotelDescriptiveContents");
    $HotelDescriptiveContent = $xml->createElement("HotelDescriptiveContent","HotelDescriptiveContent");
    
    $HotelDescriptiveContent->setAttribute("BrandCode","MHRS");
    $HotelDescriptiveContent->setAttribute("BrandName","Phuket City Hotel");
    $HotelDescriptiveContent->setAttribute("CurrencyCode","THB");
    $HotelDescriptiveContent->setAttribute("HotelCode","BOSCO");
    $HotelDescriptiveContent->setAttribute("HotelName","Phuket City Hotel Resort and Spa");
    $HotelDescriptiveContent->setAttribute("LanguageCode","TH");
    
    $HotelDescriptiveContent->appendChild($this->genHotelInfo());
    $HotelDescriptiveContent->appendChild($this->genFacilityInfo());
    $HotelDescriptiveContent->appendChild($this->genPolicies());
    $HotelDescriptiveContent->appendChild($this->genAreaInfo());
    $HotelDescriptiveContent->appendChild($this->genContractInfo());
    
    $HotelDescriptiveContents->appendChild($HotelDescriptiveContent);
    $OTA_HotelDescriptiveContentNotifRQ->appendChild($HotelDescriptiveContents);
    $xml->appendChild($OTA_HotelDescriptiveContentNotifRQ);
    
    return $xml;
  }
  private function genFacilityInfo(){
    $xml = new DOMDocument();
    $xml->createElement("FacilityInfo","FacilityInfo");
    return $xml;
  }
  private function genHotelInfo(){
    $xml = new DOMDocument();
    $xml->createElement("HotelInfo","HotelInfo");
    return $xml;
  }
  private function genPolicies(){
    $xml = new DOMDocument();
    $xml->createElement("Policies","Policies");
    return $xml;
  }
  private function genAreaInfo(){
    $xml = new DOMDocument();
    $xml->createElement("AreaInfo","AreaInfo");
    return $xml;
  }
  private function genContractInfo(){
    $xml = new DOMDocument();
    $xml->createElement("ContractInfo","ContractInfo");
    return $xml;
  }
}
?>